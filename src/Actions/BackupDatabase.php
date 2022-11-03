<?php

namespace BrendanTWhite\DatabaseMask\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Psr\Log\LoggerInterface;
use Spatie\DbSnapshots\Snapshot;
use Spatie\DbSnapshots\SnapshotFactory;

class BackupDatabase
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Create a backup.
     *
     * @return Snapshot
     */
    public function __invoke()
    {

        // Check if SnapshotFactory has been installed
        if (! class_exists('Spatie\DbSnapshots\Snapshot')) {
            throw new \Exception("The dbm:backup command requires spatie/laravel-db-snapshots to be installed.");
        }

        $connectionName = config('db-snapshots.default_connection')
            ?? config('database.default');

        $appName = config('app.name');
        $environmentName = App::environment();
        $currentTimeString = Carbon::now()->format('Y-m-d_H-i-s');
        $snapshotName = $appName.'_'.$environmentName.'_'.$currentTimeString;

        $compress = config('db-snapshots.compress', false);

        $snapshot = app(SnapshotFactory::class)->create(
            $snapshotName,
            config('db-snapshots.disk'),
            $connectionName,
            $compress
        );

        return $snapshot;
    }
}
