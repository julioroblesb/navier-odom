<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class AuditCheckpoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:checkpoint {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sella y envía el último hash de auditoría por correo para inmutabilidad externa.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastLog = \App\Models\AuditLog::latest('id')->first();
        
        if (!$lastLog) {
            $this->info('No audit logs available to checkpoint.');
            return;
        }

        $email = $this->argument('email') ?? config('mail.from.address') ?? 'admin@localhost';
        $hash = $lastLog->current_hash;
        $id = $lastLog->id;
        $date = now()->toDateTimeString();

        try {
            \Illuminate\Support\Facades\Mail::raw(
                "Checkpoint de Auditoría - NAVIER SaaS\n\nFecha: {$date}\nÚltimo ID de Log: {$id}\nÚltimo Hash (SHA-256): {$hash}\n\nGuarde este correo como evidencia criptográfica inmutable de la integridad de sus registros hasta esta fecha.",
                function ($message) use ($email) {
                    $message->to($email)
                            ->subject('Checkpoint de Seguridad NAVIER - ' . now()->toDateString());
                }
            );
            $this->info("Checkpoint email sent to {$email} with hash: {$hash}");
        } catch (\Exception $e) {
            $this->error("Failed to send checkpoint email: " . $e->getMessage());
            // Optionally log this error
        }
    }
}
