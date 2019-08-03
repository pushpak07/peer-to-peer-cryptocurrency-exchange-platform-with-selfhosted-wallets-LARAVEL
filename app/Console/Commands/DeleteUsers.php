<?php

namespace App\Console\Commands;

use App\Models\Trade;
use App\Models\User;
use App\Notifications\Authentication\UserSoftDeleted;
use Illuminate\Console\Command;

class DeleteUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all scheduled users';

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
        User::where('schedule_delete', true)
            ->get()->each(function ($user) {

                $trades = Trade::whereIn('status', ['active', 'dispute'])
                    ->where(function ($query) use ($user) {
                        $query->where('partner_id', $user->id)
                            ->orWhere('user_id', $user->id);
                    });

                if (!$trades->count()) {
                    $user->schedule_delete = false;
                    $user->save();

                    $user->notify(new UserSoftDeleted());

                    $user->delete();
                }

            });
    }
}
