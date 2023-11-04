<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Appearance;

class UpdateDatabaseCssVariablesFromFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateDatabaseCssVariablesFromFile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'recompiles custom css';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            Appearance::updateDatabaseCssVariablesFromFile();
    }
}
