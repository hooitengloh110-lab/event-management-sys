<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventImage;
use App\Models\Registration;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
            'role' => 'admin'
        ]);

        $organisers = User::factory(3)->create([
            'role' => 'organiser'
        ]);

        foreach ($organisers as $organiser) {
            // 5 Events each
            Event::factory(5)->create([
                'organiser_id' => $organiser->id
            ]);
        }

        $attendees = User::factory(10)->create([
            'role' => 'attendee'
        ]);

        $publishedEvents = Event::where('status', 'published')->get();

        foreach ($attendees as $attendee) {
            foreach ($publishedEvents->random(3) as $event) {
                Registration::factory()->create([
                    'event_id' => $event->id,
                    'attendee_id' => $attendee->id,
                    'status' => 'confirmed',
                ]);

                // Review only if event ended
                if ($event->end_datetime->isPast()) {
                    Review::factory()->create([
                        'event_id' => $event->id,
                        'attendee_id' => $attendee->id,
                    ]);
                }
            }
        }

        // $events = collect();
        // foreach ($organisers as $organiser) {
        //     $organiserEvents = Event::factory(5)->create([
        //         'organiser_id' => $organiser->id,
        //         'status' => fake()->randomElement(['draft', 'published']),
        //     ]);
        //     $events = $events->merge($organiserEvents);
        // }

        // foreach ($attendees as $attendee) {
        //     $publishedPastEvents = $events->where('status', 'published')
        //                                 ->where('event_datetime', '<', now());
        //     if ($publishedPastEvents->isEmpty()) {
        //         $publishedPastEvents = $events->where('status', 'published');
        //     }
        //     $pastPublishedEvent = $publishedPastEvents->random();
        //     Registration::factory()->create([
        //         'event_id' => $pastPublishedEvent->id,
        //         'attendee_id' => $attendee->id,
        //         'status' => 'confirmed',
        //     ]);

        //     Review::factory()->create([
        //         'event_id' => $pastPublishedEvent->id,
        //         'attendee_id' => $attendee->id,
        //     ]);
        //     $availableEvents = $events->whereNotIn('id', [$pastPublishedEvent->id]);
        //     $additionalEvents = $availableEvents->random(rand(1, min(3, $availableEvents->count())));

        //     foreach ($additionalEvents as $event) {
        //         $status = fake()->randomElement(['pending', 'confirmed', 'cancelled']);

        //         if($event->status === 'published') {
        //             Registration::factory()->create([
        //                 'event_id' => $event->id,
        //                 'attendee_id' => $attendee->id,
        //                 'status' => $status,
        //             ]);
        //         }

        //         if ($status === 'confirmed' && $event->status === 'published' && $event->event_datetime < now()) {
        //             Review::factory()->create([
        //                 'event_id' => $event->id,
        //                 'attendee_id' => $attendee->id,
        //             ]);
        //         }
        //     }
        // }

    }
}
