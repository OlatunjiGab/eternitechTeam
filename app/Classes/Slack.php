<?php

namespace App\Classes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Slack
{
    // Import Notifiable Trait
    use Notifiable;

    public function routeNotificationForSlack()
    {
        return env('SLACK_HOOK', 'https://hooks.slack.com/services/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');
    }

    public static function send(Notification $notification) {
        $handler = new self();
        $handler->notify($notification);
    }
}
