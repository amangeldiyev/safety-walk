<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditItem extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(AuditQuestion::class, 'audit_question_id', 'id');
    }
}
