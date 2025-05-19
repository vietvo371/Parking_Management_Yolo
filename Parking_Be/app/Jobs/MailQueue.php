<?php

namespace App\Jobs;

use App\Mail\GuiThongBao;
use App\Models\CuDan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class MailQueue implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels,Dispatchable;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void

    {
        $cu_dan = CuDan::find($this->data['id_cu_dan']);
        $data = [
            'ten_cu_dan' => $cu_dan->ho_va_ten,
            'email' => $cu_dan->email,
            'noi_dung' => $this->data['noi_dung'],
        ];
        Mail::to($cu_dan->email)->send(new GuiThongBao($data));

    }
}
