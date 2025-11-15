<?php

namespace App\Models;

use App\Enums\AuditMode;
use App\Enums\AuditStatus;
use App\Models\Scopes\AuditFinishedScope;
use App\Notifications\AuditCreated;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Notification;
use Storage;

class Audit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new AuditFinishedScope());

        static::creating(function ($audit) {
            $audit->user_id = Auth::user()->id;
            $audit->status = AuditStatus::DRAFT;
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withoutGlobalScopes()
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->firstOrFail();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contactUser()
    {
        return $this->belongsTo(User::class, 'contact');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function items()
    {
        return $this->hasMany(AuditItem::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function getCategoryAttribute()
    {
        switch ($this->mode) {
            case AuditMode::VEHICLE_SAFETY->value:
                return 'vehicle';

            default:
                return 'general';
        }
    }

    public function saveAttachments(array $attachments)
    {
        foreach ($attachments as $file) {
            $path = $file->store('attachments', 'public');
            $this->attachments()->create([
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }
    }

    public function saveSignature(string $signature): string
    {
        $image = str_replace('data:image/png;base64,', '', $signature);
        $image = str_replace(' ', '+', $image);
        $imageName = 'signatures/signature_' . uniqid() . '.png';

        Storage::disk('public')->put($imageName, base64_decode($image));

        return $imageName;
    }

    public function notify()
    {
        try {
            $receiver = $this->contactUser->email;

            $emails = array_filter(array_map('trim', explode(',', setting('admin.notification_emails'))));

            if (!empty($emails)) {
                $receiver = implode(',', array_merge([$receiver], $emails));
            }

            Notification::route('mail', $receiver)
                ->notify(new AuditCreated($this));
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }
    }
}
