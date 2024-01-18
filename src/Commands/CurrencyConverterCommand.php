<?php

namespace Mgcodeur\CurrencyConverter\Commands;

use Illuminate\Console\Command;

class CurrencyConverterCommand extends Command
{
    public $signature = 'currency-converter:install';

    public $description = 'Install the laravel currency converter package';

    public function handle(): int
    {
        $this->comment('Publishing Laravel currency converter config file...');
        $this->callSilent('vendor:publish', ['--tag' => 'currency-converter-config']);
        $this->info('Laravel currency converter config file published successfully.');

        //TODO: Uncomment this when persistence feature is ready
        //$this->comment('Publishing laravel currency converter migrations...');
        //$this->callSilent('vendor:publish', ['--tag' => 'currency-converter-migrations']);
        //this->info('Laravel currency converter migrations published successfully.');

        $this->comment('All done!');

        return self::SUCCESS;
    }
}
