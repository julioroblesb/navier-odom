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
        \Illuminate\Support\Facades\DB::transaction(function () {
            // Obtener el último registro y bloquear la fila para prevenir race conditions
            $lastLog = \App\Models\AuditLog::lockForUpdate()->latest('id')->first();
            
            $previousHash = $lastLog ? ($lastLog->current_hash ?? str_repeat('0', 64)) : str_repeat('0', 64);
            
            $currentHash = \App\Models\AuditLog::calculateHash($previousHash, $this->data);
            
            $this->data['previous_hash'] = $previousHash;
            $this->data['current_hash'] = $currentHash;

            \App\Models\AuditLog::create($this->data);
        });
    }
}
