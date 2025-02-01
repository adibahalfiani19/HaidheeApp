<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QadaNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $startDate;
    public $endDate;
    public $qadaSalat;

    public function __construct($userName, $startDate, $endDate, $qadaSalat)
    {
        $this->userName = $userName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->qadaSalat = $qadaSalat;
    }

    public function build()
    {
        return $this->view('emails.qada_notification')
                    ->subject('Jangan Lupa Qada Salatmu, Kak ' . $this->userName . '!')
                    ->with([
                        'userName' => $this->userName,
                        'startDate' => $this->startDate,
                        'endDate' => $this->endDate,
                        'qadaSalat' => $this->qadaSalat,
                        'appLink' => 'https://haidhee.com/',
                    ]);
    }
}

