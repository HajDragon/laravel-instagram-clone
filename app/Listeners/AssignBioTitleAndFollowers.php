<?php
/**
 * Class AssignBioTitleAndFollowers
 *
 * This listener is triggered after a user is registered. It performs the following actions:
 * 1. Assigns a random bio title to the user if they don't already have one. If no bio titles exist, it creates a new one.
 * 2. Makes some existing users follow the new user (up to 10 followers).
 * 3. Makes the new user follow some existing users (up to 10 followings).
 *
 * Any exceptions during this process are logged for debugging purposes.
 *
 * @package App\Listeners
 */
namespace App\Listeners;

use App\Models\fakeBioTitle;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AssignBioTitleAndFollowers implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        try {
            $user = $event->user;
            Log::info('AssignBioTitleAndFollowers triggered for user: ' . $user->id);

            // 1. Assign a random bio title if none exists
            if (!$user->bio_title_id) {
                // Get a random bio title or create one if none exists
                $bioTitle = fakeBioTitle::inRandomOrder()->first();

                if (!$bioTitle) {
                    Log::warning('No fakeBioTitle found, creating a new one');
                    $bioTitle = fakeBioTitle::factory()->instagramStyle()->create();
                }

                $user->bio_title_id = $bioTitle->id;
                $user->save();

                Log::info('Assigned bio title ID: ' . $bioTitle->id . ' to user: ' . $user->id);
            }

            // Check if there are other users in the system before trying to create follows
            $userCount = User::where('id', '!=', $user->id)->count();

            if ($userCount > 0) {
                // 2. Make some existing users follow this new user (up to 10 followers)
                $followerCount = min(10, $userCount);
                $followers = User::where('id', '!=', $user->id)
                    ->inRandomOrder()
                    ->take($followerCount)
                    ->get();

                foreach ($followers as $follower) {
                    Follower::firstOrCreate([
                        'user_id' => $follower->id,
                        'following_id' => $user->id
                    ]);
                }
                Log::info('Added ' . $followers->count() . ' followers to user: ' . $user->id);

                // 3. Make this user follow some existing users (up to 10 followings)
                $followingCount = min(10, $userCount);
                $followings = User::where('id', '!=', $user->id)
                    ->inRandomOrder()
                    ->take($followingCount)
                    ->get();

                foreach ($followings as $following) {
                    Follower::firstOrCreate([
                        'user_id' => $user->id,
                        'following_id' => $following->id
                    ]);
                }
                Log::info('User ' . $user->id . ' is now following ' . $followings->count() . ' users');
            } else {
                Log::info('No other users found to establish follow relationships');
            }
        } catch (\Exception $e) {
            Log::error('Error in AssignBioTitleAndFollowers: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}

namespace App\Providers;

use App\Listeners\AssignBioTitleAndFollowers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            AssignBioTitleAndFollowers::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}