<?php

namespace App\Models;

use App\Enums\AuditStatus;
use App\Models\Scopes\AuditFinishedScope;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new AuditFinishedScope);

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
}
