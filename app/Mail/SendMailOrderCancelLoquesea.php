<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailOrderCancelLoquesea extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $customer_details;
    public $order_details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $customer_details,$order_details)
    {
        $this->title = $title;
        $this->customer_details= $customer_details;
        $this->order_details = $order_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // customer_mail is the name of template
        return $this->subject($this->title) ->view('ordercancel_loquesea_mail');
    }
}