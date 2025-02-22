<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuditRequest;
use App\Http\Requests\UpdateAuditRequest;
use App\Models\Audit;
use App\Models\AuditItem;
use App\Models\AuditQuestion;
use App\Models\QuestionSegment;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $audits = Audit::with(['site', 'user'])->get();

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
        Audit::create($request->validated());

        return redirect()->route('audits.index')->with('success', 'Audit created successfully.');
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
        $audit->update($request->validated());

        return redirect()->route('audits.index')->with('success', 'Audit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Audit $audit)
    {
        $audit->delete();

        return redirect()->route('audits.index')->with('success', 'Audit deleted successfully.');
    }

    /**
     * Store an attachment to the specified audit.
     */
    public function storeAttachment(Request $request, Audit $audit)
    {
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
        $attachment = $audit->attachments()->findOrFail($attachmentId);

        \Storage::disk('public')->delete($attachment->file_path);

        $attachment->delete();

        return redirect()->route('audits.show', $audit)->with('success', 'Attachment deleted successfully.');
    }

    /**
     * Display the questions for the specified audit.
     */
    public function questions(Audit $audit)
    {
        $segments = QuestionSegment::with('auditQuestions')->get();
        $audit->load('items');
        // dd($audit->items);

        return view('audits.questions', compact('audit', 'segments'));
    }

    /**
     * Store the questions for the specified audit.
     */
    public function storeQuestions(Request $request, Audit $audit)
    {
        $questions = $request->input('questions', []);

        foreach ($questions as $questionId => $answer) {
            if ($answer !== null) {
                AuditItem::updateOrCreate(
                    ['audit_id' => $audit->id, 'audit_question_id' => $questionId],
                    ['answer' => $answer]
                );
            }
        }

        return redirect()->route('audits.index')->with('success', 'Questions saved successfully.');
    }
}
