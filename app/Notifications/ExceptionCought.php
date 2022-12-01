<?php

namespace App\Notifications;

use App\Helpers\ShortLink;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ExceptionCought extends Notification
{
    use Queueable;

    private $exception;

    /**
     * Create a new notification instance.
     * ExceptionCought constructor.
     *
     * @param $exception
     */
    public function __construct(\Exception $exception, $requestUrl="")
    {
        $this->exception = $exception;
        $this->requestUrl =$requestUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $exception = $this->exception;
        $requestUrl = $this->requestUrl;
        $url = url(ShortLink::SHORTLINK_BASE_DOMAIN);
        $slackChannel = env('SLACK_PRODUCTION_CHANNEL','#errors');
        if(\App::environment() == 'local') {
            $slackChannel = env('SLACK_LOCAL_CHANNEL','#staging-errors');
        }
        return (new SlackMessage)
            ->error()
            ->from('CRM-Bot', ':ghost:')
            ->to($slackChannel)
            ->content($exception->getMessage())
            ->attachment(function ($attachment) use ($exception,$requestUrl) {
                $attachment->title('Exception: '.$exception->getMessage())
                    ->content('URL :'.$requestUrl." \n".$exception->getTraceAsString());
            });
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
    }

//    /**
//     * Get the array representation of the notification.
//     *
//     * @param  mixed  $notifiable
//     * @return array
//     */
//    public function toArray($notifiable)
//    {
//        return [
//            'title' => $this->exception->getMessage(),
//            'description' => $this->exception->getTraceAsString(),
//        ];
//    }
}
