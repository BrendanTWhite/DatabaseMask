<?php

namespace BrendanTWhite\DatabaseMask\Actions;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Psr\Log\LoggerInterface;
use Spatie\DbSnapshots\Snapshot;
use Spatie\DbSnapshots\SnapshotRepository;

class RestoreDatabase
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke($filename)
    {
        
        // Check if SnapshotFactory has been installed
        if (! class_exists('Spatie\DbSnapshots\Snapshot')) {
            throw new Exception("The dbm:restore command requires spatie/laravel-db-snapshots to be installed.");
        }

        // We will NEVER restore a backup to a Production environment

        $environment = App::environment();
        if ($environment == 'production') {
            throw new Exception("DBM will not restore to a '$environment' environment.");
        }

        // OK. We have a filename. Let's get the snapshot with that name.

        /** @var \Spatie\DbSnapshots\Snapshot $snapshot */
        $snapshot = app(SnapshotRepository::class)->findByName($filename);

        if (! $snapshot) {
            throw new Exception("Snapshot `{$filename}` does not exist!");

            return Command::INVALID;
        }

        // We are good to go. Let's load this thing.

        $snapshot->load();
    }
}
