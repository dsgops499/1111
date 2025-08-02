<?php

namespace Modules\Manga\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyOnNewChapter extends Notification implements ShouldQueue
{
    use Queueable;
    
    private $manga;
    private $chapterNumber;
    private $chapterUrl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($manga, $chapterNumber, $chapterUrl)
    {
        $this->manga = $manga;
        $this->chapterNumber = $chapterNumber;
        $this->chapterUrl= $chapterUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->manga->name . ' Chapter ' . $this->chapterNumber . ' is available')
                    ->greeting(trans('messages.email.notif.greetings', array('name' => $notifiable->username)))
                    ->line(trans('messages.email.notif.body', ['manga' => $this->manga->name, 'chapter' => $this->chapterNumber]))
                    ->action($this->manga->name.' #'.$this->chapterNumber, $this->chapterUrl)
                    ->salutation(trans('messages.email.notif.best-regards'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
