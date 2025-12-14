<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'in_progress', 'completed'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'tags' => json_encode(['laravel', 'api', 'backend', 'senior']),
            'is_recurring' => $this->faker->boolean(30), // 30% chance true
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}