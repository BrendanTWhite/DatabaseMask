<?php

namespace BrendanTWhite\DatabaseMask;

use Illuminate\Support\ServiceProvider;
use BrendanTWhite\DatabaseMask\Console\Backup;
use BrendanTWhite\DatabaseMask\Console\Restore;
use BrendanTWhite\DatabaseMask\Console\Mask;

class DatabaseMaskServiceProvider extends ServiceProvider
{
  public function register()
  {
    //
  }

  public function boot()
  {
    // Register the command if we are using the application via the CLI
    if ($this->app->runningInConsole()) {
        $this->commands([
          Backup::class,
          Restore::class,
          Mask::class,
        ]);
    }
  }
}
