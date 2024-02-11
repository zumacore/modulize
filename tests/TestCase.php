<?php

namespace Zuma\Modulize\Tests;

use Illuminate\Encryption\Encrypter;
use Orchestra\Testbench\TestCase as Orchestra;
use Zuma\Modulize\Console\Commands\Make\MakeModule;
use Zuma\Modulize\Support\DatabaseFactoryHelper;
use Zuma\Modulize\Support\Facades\Modules;
use Zuma\Modulize\Support\ModuleCommandsServiceProvider;
use Zuma\Modulize\Support\ModuleConfig;
use Zuma\Modulize\Support\ModuleServiceProvider;

abstract class TestCase extends Orchestra
{
  protected function setUp(): void
  {
    parent::setUp();

    Modules::reload();

    $config = $this->app['config'];

    // Add encryption key for HTTP tests
    $config->set('app.key', 'base64:'.base64_encode(Encrypter::generateKey('AES-128-CBC')));

    // Add stubs to view
    // $this->app['view']->addLocation(__DIR__.'/Feature/stubs');
  }

  protected function tearDown(): void
  {
    $this->app->make(DatabaseFactoryHelper::class)->resetResolvers();

    parent::tearDown();
  }

  protected function makeModule(string $name = 'test-module'): ModuleConfig
  {
    $this->artisan(MakeModule::class, [
      'name' => $name,
      '--accept-namespace' => true,
    ]);

    return Modules::module($name);
  }

  protected function requiresLaravelVersion(string $minimum_version)
  {
    if (version_compare($this->app->version(), $minimum_version, '<')) {
      $this->markTestSkipped("Only applies to Laravel {$minimum_version} and above.");
    }

    return $this;
  }

  protected function getPackageProviders($app)
  {
    return [
      ModuleServiceProvider::class,
      ModuleCommandsServiceProvider::class,
    ];
  }

  protected function getPackageAliases($app)
  {
    return [
      'Modules' => Modules::class,
    ];
  }
}
