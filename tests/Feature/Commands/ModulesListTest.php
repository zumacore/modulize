<?php

use Zuma\Modulize\Console\Commands\ModulesList;

uses(Zuma\Modulize\Tests\Concerns\WritesToAppFilesystem::class);

test('it writes to cache file', function () {
  $this->makeModule('test-module');

  $this->artisan(ModulesList::class)
      ->expectsOutput('You have 1 module installed.')
      ->assertExitCode(0);

  $this->makeModule('test-module-two');

  $this->artisan(ModulesList::class)
      ->expectsOutput('You have 2 modules installed.')
      ->assertExitCode(0);
});
