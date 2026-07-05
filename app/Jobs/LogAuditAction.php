<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class LogAuditAction implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        // set timestamp if not provided
        if (!isset($this->data['created_at'])) {
            $this->data['created_at'] = now();
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \App\Models\AuditLog::create($this->data);
    }
}
