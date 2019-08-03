<?php

namespace App\Console\Commands;

use App\Models\Trade;
use App\Models\User;
use App\Notifications\Authentication\UserDeactivated;
use App\Notifications\Authentication\UserSoftDeleted;
use Illuminate\Console\Command;

class DeactivateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:deactivate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate all scheduled users';

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
        User::withTrashed()->where('schedule_deactivate', true)
            ->get()->each(function ($user) {

                $trades = Trade::whereIn('status', ['active', 'dispute'])
                    ->where(function ($query) use ($user) {
                        $query->where('partner_id', $user->id)
                            ->orWhere('user_id', $user->id);
                    });

                if (!$trades->count()) {
                    $user->status = 'inactive';
                    $user->schedule_deactivate = false;
                    $user->save();

                    $user->notify(new UserDeactivated());
                }

            });
    }
}
