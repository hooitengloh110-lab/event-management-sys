<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpcomingEventSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Event::factory(10)->create([
      'organiser_id' => 2,
      'status' => 'published'
    ])->each(function ($event, $index) {
      $startDatetime = Carbon::parse(
        fake()->dateTimeBetween('-1 month', '+1 month')
      )->setSeconds(0);
      $minutes = $startDatetime->minute;
      $roundedMinutes = round($minutes / 15) * 15;

      $startDatetime->setTime($startDatetime->hour, $roundedMinutes, 0);

      $durationInHours = fake()->numberBetween(1, 48);
      $endDatetime = $startDatetime->copy()->addHours($durationInHours)->setSeconds(0);

      $event->update([
        'start_datetime' => $startDatetime,
        'end_datetime' => $endDatetime,
      ]);
    });
  }
}
