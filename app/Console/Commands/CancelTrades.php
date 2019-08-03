<?php

namespace App\Console\Commands;

use App\Events\TradeStatusUpdated;
use App\Models\Trade;
use App\Notifications\Trades\Expired;
use Illuminate\Console\Command;

class CancelTrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trades:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel all unconfirmed trades';

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
        Trade::where('status', 'active')
            ->has('user')->has('partner')
            ->where('confirmed', false)->get()
            ->each(function ($trade){
                if($trade->created_at->addMinutes($trade->deadline) < now()){
                    $trade->status = 'cancelled';
                    $trade->save();

                    broadcast(new TradeStatusUpdated($trade));

                    $trade->buyer()->notify(new Expired($trade));

                    $this->info("Cancelled Trade: {$trade->token}");
                }
            });
    }
}
