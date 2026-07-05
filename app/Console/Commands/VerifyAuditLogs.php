<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class VerifyAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifies the integrity of the audit logs hash chain (Tamper-Evident).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Audit Log Integrity Check...');

        $logs = \App\Models\AuditLog::orderBy('id', 'asc')->get();
        
        if ($logs->isEmpty()) {
            $this->info('No audit logs found.');
            return;
        }

        $expectedPreviousHash = str_repeat('0', 64);
        $errors = 0;

        $bar = $this->output->createProgressBar(count($logs));
        $bar->start();

        foreach ($logs as $log) {
            // Verificamos si alguien manipuló la fila usando NULL (logs antiguos previo a la migración)
            // Si el log es antiguo (ambos hashes NULL), ignoramos o advertimos
            if ($log->previous_hash === null && $log->current_hash === null) {
                // Logs heredados antes de la fase 1.5, los omitimos o asumimos válidos
                $bar->advance();
                continue;
            }

            // Verificar la cadena
            if ($log->previous_hash !== $expectedPreviousHash) {
                $this->newLine();
                $this->error("BROKEN CHAIN at Log ID {$log->id}: Previous hash mismatch.");
                $errors++;
            }

            // Recalcular el current_hash
            $calculatedHash = \App\Models\AuditLog::calculateHash($log->previous_hash, $log->toArray());

            if ($log->current_hash !== $calculatedHash) {
                $this->newLine();
                $this->error("TAMPERED DATA at Log ID {$log->id}: Data payload does not match current_hash.");
                $errors++;
            }

            $expectedPreviousHash = $log->current_hash;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        if ($errors > 0) {
            $this->error("INTEGRITY CHECK FAILED: Found {$errors} discrepancies in the audit trail.");
            return 1;
        } else {
            $this->info('INTEGRITY CHECK PASSED: All audit logs are mathematically sound.');
            return 0;
        }
    }
}
