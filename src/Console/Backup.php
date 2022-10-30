<?php

namespace BrendanTWhite\DatabaseMask\Console;

use BrendanTWhite\DatabaseMask\Actions\BackupDatabase;
use Illuminate\Console\Command;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbm:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Database';

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
    public function handle(BackupDatabase $backupDatabase)
    {
        $this->line('Starting backup...');
        $backup = $backupDatabase();
        $this->line('... backup finished.');

        $size = Format::humanReadableSize($backup->size());

        $this->info("Successfully backed up the database to file '$backup->fileName' (size $size).");

        return Command::SUCCESS;
    }
}
