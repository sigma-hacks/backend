<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate users for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::factory()->count(3)->create();
        return 0;
    }
}
