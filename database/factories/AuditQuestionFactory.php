<?php

namespace Database\Factories;

use App\Models\AuditQuestion;
use App\Models\QuestionSegment;
use Illuminate\Database\Eloquent\Factories\Factory;


class AuditQuestionFactory extends Factory
{
    protected $model = AuditQuestion::class;

    public function definition()
    {
        return [
            'question_segment_id' => QuestionSegment::inRandomOrder()->first()->id,
            'question' => $this->faker->sentence(),
        ];
    }
}