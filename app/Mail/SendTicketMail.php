<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    // public $athlete;
    public $order;
    public $customer;
    public $ticket;

    public function __construct($order, $customer, $ticket)
    {
        $this->order = $order;
        $this->customer = $customer;
        $this->ticket = $ticket;
    }

    public function build()
    {
        // dd($this->order, $this->customer, $this->ticket);
        return $this->subject('MHĐ: ' . $this->order->code_order . ' Bảo tàng vũ trụ Việt Nam')->view('mail.sent_ticket', [
            'order' => $this->order,
            'customer' => $this->customer,
            'ticket' => $this->ticket
        ])->from('baotangvutru@baotangvutru.com', 'Bảo tàng vũ trụ Việt Nam');
    }
}
