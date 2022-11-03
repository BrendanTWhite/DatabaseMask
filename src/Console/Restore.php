<?php

namespace BrendanTWhite\DatabaseMask\Console;

use BrendanTWhite\DatabaseMask\Actions\RestoreDatabase;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Spatie\DbSnapshots\SnapshotRepository;

class Restore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbm:restore {filename? : The backup file to restore}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database (and mask sensitive data with Faker)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RestoreDatabase $restoreDatabase)
    {
        // If we don't have a filename specified, give the user a list to choose from

        $filename = $this->argument('filename');
        if (! $filename) {
            try {
                $this->line('Retreiving list of backups...');
                $list_of_backups = app(SnapshotRepository::class)->getAll();
                $this->line('... list retrived.');
            } catch (Exception $exception) {
                $this->warn("The dbm:restore command requires spatie/laravel-db-snapshots to be installed");
    
                return Command::INVALID;
            }
    
            if ($list_of_backups->isEmpty()) {
                $this->warn('No backups found.');
                $this->warn('Run `php artisan dbm:backup` from your production environment to create a backup.');

                return Command::INVALID;
            }

            define('MOST_RECENT_BACKUP', 0);
            $filename = $this->choice(
                'Which backup should we restore?',
                $list_of_backups->pluck('name')->all(),
                MOST_RECENT_BACKUP
            );
        }

        // OK. We have a filename. Let's try to get the snapshot with that name.

        try {
            $restoreDatabase($filename);
        } catch (Exception $exception) {
            $this->warn($exception->getMessage());

            return Command::INVALID;
        }

        // All done. The restore completed successfully. Let's tell the user.

        $environment = App::environment();
        $this->info("This `{$environment}` environment has been refreshed from `{$filename}`.");

        return Command::SUCCESS;
    }
}
