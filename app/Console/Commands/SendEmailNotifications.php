<?php

namespace App\Console\Commands;

use App\Notifications\SendEmailSubscription;
use App\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendEmailNotifications extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:semails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Emails by subscription';


    public function handle()
    {
        $subscriptions = Subscription::all();

        foreach ($subscriptions as $subscription) {

//            $filmHistories = FilmsHistory::iTunesCountry('us')
//                ->filter($subscription->filter)
//                ->today()
//                ->with('film')
//                ->orderBy('created_at', 'desc')
//                ->get();
//
//            if ($filmHistories->count()) {
//                $subscription->notify(new SendEmailSubscription());
//            }
        }
    }
}
