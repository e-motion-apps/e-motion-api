<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition()
    {
        return [
            "text" => $this->faker->sentence,
            "user_id" => \App\Models\User::factory(),
        ];
    }
}
