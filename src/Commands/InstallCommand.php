<?php

namespace Kataki\Syber_pay\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

use Kataki\Syber_pay\SyberServiceProvider;

class InstallCommand extends Command
{
   

    //protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'Syber_pay:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the kataki/Syber_pay package';

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production', null],
            ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
        ];
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Publishing the kataki/Syber_pay assets, database, and config files');

        

        $this->call('vendor:publish', ['--provider' =>SyberServiceProvider::class]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate', ['--force' => $this->option('force')]);

        
        $this->info('Successfully installed kataki/Syber_pay! Enjoy');
    }
}