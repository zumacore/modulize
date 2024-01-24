<?php

use Zuma\Modulize\Console\Commands\ModulesCache;
use Zuma\Modulize\Console\Commands\ModulesClear;

uses(Zuma\Modulize\Tests\Concerns\WritesToAppFilesystem::class);

test('it writes to cache file', function () {
  $this->artisan(ModulesCache::class);

  $expected_path = $this->getBasePath().$this->normalizeDirectorySeparators('bootstrap/cache/modules.php');

  expect($expected_path)->toBeFile();

  $this->artisan(ModulesClear::class);

  $this->assertFileDoesNotExist($expected_path);
});
