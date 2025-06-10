<?php

namespace App\Http\Controllers;

use App\Enums\AuditMode;
use App\Exports\AuditsExport;
use App\Http\Requests\CreateAuditRequest;
use App\Http\Requests\UpdateAuditRequest;
use App\Models\Audit;
use App\Models\AuditItem;
use App\Models\QuestionSegment;
use App\Models\Site;
use App\Models\User;
use App\Notifications\AuditCreated;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Notification;
use Storage;

class AuditController extends Controller
{
    /**
     * Display the dashboard with audit statistics.
     */
    public function dashboard()
    {
        return view('audits.dashboard');
    }

    /**
     * Display the key safety behaviour page.
     */
    public function keySafetyBehaviour()
    {
        return view('audits.safety-behaviour');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $audits = Audit::where('user_id', Auth::user()->id)->with(['site', 'user'])->get();

        return view('audits.index', compact('audits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('audits.create', [
            'sites' => Site::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAuditRequest $request)
    {
        $audit = Audit::create($request->validated());

        if ($audit->mode == (AuditMode::CONVERSATION)->value) {
            return redirect()->route('audits.details', $audit);
        } else {
            return redirect()->route('audits.questions.create', $audit);
        }

        return redirect()->route('audits.index')->with('success', 'Audit created successfully.');
    }

    /**
     * Display the details form for the specified audit.
     */
    public function details(Audit $audit)
    {
        return view('audits.details', compact('audit'));
    }

    /**
     * Store the details for the specified audit.
     */
    public function storeDetails(Request $request, Audit $audit)
    {
        if ($audit->user_id !== Auth::id()) {
            return redirect()->route('audits.index')->with('error', 'You are not authorized to update this record.');
        }

        $request->validate([
            'good_practice' => 'nullable|boolean',
            'point_of_improvement' => 'nullable|boolean',
            'signature' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^data:image\/(png|jpeg);base64,/', $value)) {
                    $fail('The ' . $attribute . ' must be a valid base64-encoded PNG or JPEG image.');
                    return;
                }
            }],
            'comment' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $audit->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        $dataURL = $request->input('signature');

        $image = str_replace('data:image/png;base64,', '', $dataURL);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signatures/signature_' . uniqid() . '.png';

        Storage::disk('public')->put($imageName, base64_decode($image));
        $request->merge(['signature' => $imageName]);

        $audit->update($request->only('comment', 'good_practice', 'point_of_improvement', 'signature', 'follow_up_date'));

        try {
            $receiver = $audit->contactUser->email;

            $emails = array_filter(array_map('trim', explode(',', setting('admin.notification_emails'))));

            if (!empty($emails)) {
                $receiver = implode(',', array_merge([$receiver], $emails));
            }

            Notification::route('mail', $receiver)
                ->notify(new AuditCreated($audit));
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }

        return redirect()->route('audits.index')->with('success', 'Details saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Audit $audit)
    {
        return view('audits.show', compact('audit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Audit $audit)
    {
        return view('audits.edit', [
            'audit' => $audit,
            'sites' => Site::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuditRequest $request, Audit $audit)
    {
        if ($audit->user_id !== Auth::id()) {
            return redirect()->route('audits.index')->with('error', 'You are not authorized to update this record.');
        }

        $audit->update($request->validated());

        return redirect()->route('audits.index')->with('success', 'Audit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Audit $audit)
    {
        if ($audit->user_id !== Auth::id()) {
            return redirect()->route('audits.index')->with('error', 'You are not authorized to delete this record.');
        }

        $audit->delete();

        return redirect()->route('audits.index')->with('success', 'Audit deleted successfully.');
    }

    /**
     * Store an attachment to the specified audit.
     */
    public function storeAttachment(Request $request, Audit $audit)
    {
        if ($audit->user_id !== Auth::id()) {
            return redirect()->route('audits.index')->with('error', 'You are not authorized to update this record.');
        }

        $request->validate([
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $path = $request->file('attachment')->store('attachments', 'public');

        $audit->attachments()->create([
            'file_path' => $path,
            'file_name' => $request->file('attachment')->getClientOriginalName(),
            'file_type' => $request->file('attachment')->getClientMimeType(),
            'file_size' => $request->file('attachment')->getSize(),
        ]);

        return redirect()->route('audits.show', $audit)->with('success', 'Attachment added successfully.');
    }

    /**
     * Remove the specified attachment from storage.
     */
    public function destroyAttachment(Request $request, Audit $audit, $attachmentId)
    {
        if ($audit->user_id !== Auth::id()) {
            return redirect()->route('audits.index')->with('error', 'You are not authorized to update this record.');
        }

        $attachment = $audit->attachments()->findOrFail($attachmentId);

        \Storage::disk('public')->delete($attachment->file_path);

        $attachment->delete();

        return redirect()->route('audits.show', $audit)->with('success', 'Attachment deleted successfully.');
    }

    /**
     * Display the segment page for the specified audit.
     */
    public function segment(Audit $audit, QuestionSegment $segment)
    {
        $segment->load('auditQuestions');
        $segments = [$segment];
        $audit->load(['items' => function ($query) use ($segment) {
            $query->whereIn('audit_question_id', $segment->auditQuestions->pluck('id'));
        }]);

        return view('audits.questions', compact('audit', 'segments'));
    }

    /**
     * Display the questions for the specified audit.
     */
    public function questions(Audit $audit)
    {
        $segments = QuestionSegment::with('auditQuestions')->get();
        $audit->load('items');

        return view('audits.questions', compact('audit', 'segments'));
    }

    /**
     * Store the questions for the specified audit.
     */
    public function storeQuestions(Request $request, Audit $audit)
    {
        if ($audit->user_id !== Auth::id()) {
            return redirect()->route('audits.index')->with('error', 'You are not authorized to update this record.');
        }

        $questions = $request->input('questions', []);

        foreach ($questions as $questionId => $answer) {
            if ($answer !== null) {
                AuditItem::updateOrCreate(
                    ['audit_id' => $audit->id, 'audit_question_id' => $questionId],
                    ['answer' => $answer]
                );
            }
        }

        return redirect()->route('audits.details', $audit);
    }

    public function export()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        return Excel::download(new AuditsExport(), "safety_walks_{$timestamp}.xlsx");
    }
}
