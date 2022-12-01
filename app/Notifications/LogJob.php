<?php

namespace App\Notifications;

use App\Helpers\ShortLink;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LogJob extends Notification
{
    use Queueable;

    const CHANNEL_EMAILS = '#crm-emails';
    const CHANNEL_JOBS = '#crm-jobs';
    const CHANNEL_PARTNERS = '#crm-partners';
    const CHANNEL_FOLLOWUP = '#crm-followup';

    private $success;
    private $message;
    private $data;
    private $logChannel;

/**
 * LogJob constructor.
 *
 * @param bool   $success
 * @param string $message
 * @param array  $data
 * @param string $logChannel
 */
    public function __construct(bool $success, string $message, array $data= [], string $logChannel = self::CHANNEL_JOBS)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->logChannel = $logChannel;
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
        $message = $this->message;
        $data = $this->data;
        $notification = (new SlackMessage)
            ->from('CRM-Bot', ':ghost:')
            ->to($this->logChannel)
            ->content($message)
            ->attachment(function ($attachment) use ($data) {
                $content = (!empty($data['url']) ? 'URL: ' . $data['url'] . " \n " : '') . ($data['content'] ?? 'no additional content');
                $attachment->title('Channel: ' . $data['channel'] ?? 'Unknown')
                    ->content($content);
            });
        if ($this->success) {
            $notification->success();
        } else {
            $notification->error();
        }

        return $notification;
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
