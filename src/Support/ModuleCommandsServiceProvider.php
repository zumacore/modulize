<?php

namespace Zuma\Modulize\Support;

use Illuminate\Console\Application;
use Illuminate\Console\Application as Artisan;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand as OriginalMakeMigrationCommand;
use Illuminate\Support\ServiceProvider;
use Zuma\Modulize\Console\Commands\Database\SeedCommand;
use Zuma\Modulize\Console\Commands\Make\MakeCast;
use Zuma\Modulize\Console\Commands\Make\MakeChannel;
use Zuma\Modulize\Console\Commands\Make\MakeCommand;
use Zuma\Modulize\Console\Commands\Make\MakeComponent;
use Zuma\Modulize\Console\Commands\Make\MakeController;
use Zuma\Modulize\Console\Commands\Make\MakeEvent;
use Zuma\Modulize\Console\Commands\Make\MakeException;
use Zuma\Modulize\Console\Commands\Make\MakeFactory;
use Zuma\Modulize\Console\Commands\Make\MakeJob;
use Zuma\Modulize\Console\Commands\Make\MakeListener;
use Zuma\Modulize\Console\Commands\Make\MakeMail;
use Zuma\Modulize\Console\Commands\Make\MakeMiddleware;
use Zuma\Modulize\Console\Commands\Make\MakeMigration;
use Zuma\Modulize\Console\Commands\Make\MakeModel;
use Zuma\Modulize\Console\Commands\Make\MakeNotification;
use Zuma\Modulize\Console\Commands\Make\MakeObserver;
use Zuma\Modulize\Console\Commands\Make\MakePolicy;
use Zuma\Modulize\Console\Commands\Make\MakeProvider;
use Zuma\Modulize\Console\Commands\Make\MakeRequest;
use Zuma\Modulize\Console\Commands\Make\MakeResource;
use Zuma\Modulize\Console\Commands\Make\MakeRule;
use Zuma\Modulize\Console\Commands\Make\MakeSeeder;
use Zuma\Modulize\Console\Commands\Make\MakeTest;

class ModuleCommandsServiceProvider extends ServiceProvider
{
  protected array $overrides = [
    'command.cast.make' => MakeCast::class,
    'command.controller.make' => MakeController::class,
    'command.console.make' => MakeCommand::class,
    'command.channel.make' => MakeChannel::class,
    'command.event.make' => MakeEvent::class,
    'command.exception.make' => MakeException::class,
    'command.factory.make' => MakeFactory::class,
    'command.job.make' => MakeJob::class,
    'command.listener.make' => MakeListener::class,
    'command.mail.make' => MakeMail::class,
    'command.middleware.make' => MakeMiddleware::class,
    'command.model.make' => MakeModel::class,
    'command.notification.make' => MakeNotification::class,
    'command.observer.make' => MakeObserver::class,
    'command.policy.make' => MakePolicy::class,
    'command.provider.make' => MakeProvider::class,
    'command.request.make' => MakeRequest::class,
    'command.resource.make' => MakeResource::class,
    'command.rule.make' => MakeRule::class,
    'command.seeder.make' => MakeSeeder::class,
    'command.test.make' => MakeTest::class,
    'command.component.make' => MakeComponent::class,
    'command.seed' => SeedCommand::class,
  ];

  public function register(): void
  {
    // Register our overrides via the "booted" event to ensure that we override
    // the default behavior regardless of which service provider happens to be
    // bootstrapped first.
    $this->app->booted(function () {
      Artisan::starting(function (Application $artisan) {
        $this->registerMakeCommandOverrides();
        $this->registerMigrationCommandOverrides();
      });
    });
  }

  protected function registerMakeCommandOverrides()
  {
    foreach ($this->overrides as $alias => $class_name) {
      $this->app->singleton($alias, $class_name);
      $this->app->singleton(get_parent_class($class_name), $class_name);
    }
  }

  protected function registerMigrationCommandOverrides()
  {
    // Laravel 8
    $this->app->singleton('command.migrate.make', function ($app) {
      return new MakeMigration($app['migration.creator'], $app['composer']);
    });

    // Laravel 9
    $this->app->singleton(OriginalMakeMigrationCommand::class, function ($app) {
      return new MakeMigration($app['migration.creator'], $app['composer']);
    });
  }
}
