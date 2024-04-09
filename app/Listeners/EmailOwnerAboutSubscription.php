<?php

namespace App\Listeners;

use App\Events\UserSubscribed;
use Illuminate\Support\Facades\DB;
use App\Mail\UserSubscribedMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOwnerAboutSubscription
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserSubscribed $event): void
    {
        // Insert the email into the newsletter table
        DB::table('newsletter')->insert([
            'email' => $event->email,
            'created_at'=> now(),
        ]);

        // Send an email to the subscribed user
        Mail::to($event->email)->send(new UserSubscribedMessage());
    }
}
