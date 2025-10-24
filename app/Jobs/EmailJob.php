<?php

namespace App\Jobs;

use App\Mail\SentMail;
use App\Models\MailHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $html;
    protected $record;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($record, $html)
    {
        $this->record = $record;
        $this->html = $html;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $record = $this->record;
        $html = $this->html;
        $email = new SentMail($html);
        Mail::to($record->account->email)->send($email);
        MailHistory::create([
            'schedule_id' => $record->id,
            'name' => $record->account?->name,
            'message' => $html,
        ]);
    }
}
