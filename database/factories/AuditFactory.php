<?php

namespace Database\Factories;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Factories\Factory;


class AuditFactory extends Factory
{
    protected $model = Audit::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'mode' => $this->faker->numberBetween(1, 2),
            'site_id' => $this->faker->numberBetween(1, 100),
            'contact' => $this->faker->numberBetween(1, 100),
            'is_virtual' => $this->faker->boolean(),
            'comment' => $this->faker->sentence(),
            'follow_up_date' => $this->faker->optional()->date(),
        ];
    }
}