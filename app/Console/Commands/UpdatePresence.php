<?php

namespace App\Console\Commands;

use App\Events\UserPresenceUpdated;
use App\Models\User;
use Illuminate\Console\Command;

class UpdatePresence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presence:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user presence';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lifetime = config('session.lifetime');

        $users = User::whereIn('presence', ['online', 'away'])->get();

        $users->each(function ($user) use($lifetime){
            if($user->last_seen == null || now()->diffInMinutes($user->last_seen) > $lifetime){
                $user->presence = 'offline';
                $user->last_seen = now();
                $user->save();

                broadcast(new UserPresenceUpdated($user));

                $this->info("Presence Updated: {$user->name} ($user->presence)");
            }
        });

        $this->info("Task completed! Total: {$users->count()} users");
    }
}
