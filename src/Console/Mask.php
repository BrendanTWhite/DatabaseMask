<?php

namespace BrendanTWhite\DatabaseMask\Console;

use BrendanTWhite\DatabaseMask\Actions\MaskDatabase;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class Mask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbm:mask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mask sensitive data with Faker';

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
    public function handle(MaskDatabase $maskDatabase)
    {
        try {
            $maskDatabase($this);
        } catch (Exception $exception) {
            $this->warn($exception->getMessage());

            return Command::INVALID;
        }

        // All done. The masking completed successfully. Let's tell the user.
        $environment = App::environment();
        $this->info("This `{$environment}` environment has been masked.");

        return Command::SUCCESS;
    }
}
