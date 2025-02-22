<?php

namespace Database\Factories;

use App\Models\QuestionSegment;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionSegmentFactory extends Factory
{
    protected $model = QuestionSegment::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(5, true),
        ];
    }
}
