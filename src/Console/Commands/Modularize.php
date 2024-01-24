<?php

namespace Zuma\Modulize\Console\Commands;

use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputOption;
use Zuma\Modulize\Support\ModuleConfig;
use Zuma\Modulize\Support\ModuleRegistry;

trait Modularize
{
  protected function module(): ?ModuleConfig
  {
    if ($name = $this->option('module')) {
      $registry = $this->getLaravel()->make(ModuleRegistry::class);

      if ($module = $registry->module($name)) {
        return $module;
      }

      throw new InvalidOptionException(sprintf('The "%s" module does not exist.', $name));
    }

    return null;
  }

  protected function configure()
  {
    parent::configure();

    $this->getDefinition()->addOption(
      new InputOption(
        '--module',
        null,
        InputOption::VALUE_REQUIRED,
        'Run inside an application module'
      )
    );
  }
}
