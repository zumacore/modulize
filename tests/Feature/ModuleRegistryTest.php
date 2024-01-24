<?php

use Zuma\Modulize\Support\ModuleConfig;
use Zuma\Modulize\Support\ModuleRegistry;

uses(\Zuma\Modulize\Tests\Concerns\WritesToAppFilesystem::class);

test('it resolves modules', function () {
  $this->makeModule('test-module');
  $this->makeModule('test-module-two');

  $registry = $this->app->make(ModuleRegistry::class);

  expect($registry->module('test-module'))->toBeInstanceOf(ModuleConfig::class);
  expect($registry->module('test-module-two'))->toBeInstanceOf(ModuleConfig::class);
  expect($registry->module('non-existant-module'))->toBeNull();

  expect($registry->modules())->toHaveCount(2);

  $module = $registry->moduleForPath($this->getModulePath('test-module', 'foo/bar'));
  expect($module)->toBeInstanceOf(ModuleConfig::class);
  expect($module->name)->toEqual('test-module');

  $module = $registry->moduleForPath($this->getModulePath('test-module-two', 'foo/bar'));
  expect($module)->toBeInstanceOf(ModuleConfig::class);
  expect($module->name)->toEqual('test-module-two');

  $module = $registry->moduleForClass('Modules\\TestModule\\Foo');
  expect($module)->toBeInstanceOf(ModuleConfig::class);
  expect($module->name)->toEqual('test-module');

  $module = $registry->moduleForClass('Modules\\TestModuleTwo\\Foo');
  expect($module)->toBeInstanceOf(ModuleConfig::class);
  expect($module->name)->toEqual('test-module-two');
});
