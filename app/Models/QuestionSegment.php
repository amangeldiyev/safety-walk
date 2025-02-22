<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSegment extends Model
{
    use HasFactory;

    public function auditQuestions()
    {
        return $this->hasMany(AuditQuestion::class);
    }
}
