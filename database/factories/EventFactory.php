<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDatetime = Carbon::parse(
            fake()->dateTimeBetween('-1 month', '+1 month')
        )->setSeconds(0);
        $minutes = $startDatetime->minute;
        $roundedMinutes = round($minutes / 15) * 15;

        $startDatetime->setTime($startDatetime->hour, $roundedMinutes, 0);

        $durationInHours = fake()->numberBetween(1, 48);
        $endDatetime = $startDatetime->copy()->addHours($durationInHours)->setSeconds(0);

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'category' => fake()->randomElement([
                'Technology',
                'Business',
                'Sports',
                'Education',
                'Music'
            ]),
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
            // 'event_datetime' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'capacity' => fake()->numberBetween(20, 300),
            'price' => fake()->randomFloat(2, 0, 500),
            'status' => fake()->randomElement([
                'draft', 'published'
            ])
        ];
    }
}
