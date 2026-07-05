<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:test-scope')]
#[Description('Command description')]
class TestScope extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
