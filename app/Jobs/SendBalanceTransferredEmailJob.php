<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\BalanceTransferred;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendBalanceTransferredEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 25;
    public $maxExceptions = 3;
    public $timeout = 120;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        Mail::to($this->data->to_user->email)->send(new BalanceTransferred($this->data));

    }
}
