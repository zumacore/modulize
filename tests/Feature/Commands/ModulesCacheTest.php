<?php

use Zuma\Modulize\Console\Commands\ModulesCache;

uses(Zuma\Modulize\Tests\Concerns\WritesToAppFilesystem::class);

test('it writes to cache file', function () {
  $this->makeModule('test-module');
  $this->makeModule('test-module-two');

  $this->artisan(ModulesCache::class);

  $expected_path = $this->getBasePath().$this->normalizeDirectorySeparators('bootstrap/cache/modules.php');

  expect($expected_path)->toBeFile();

  $cache = include $expected_path;

  expect($cache)->toHaveKey('test-module');
  expect($cache)->toHaveKey('test-module-two');
});
