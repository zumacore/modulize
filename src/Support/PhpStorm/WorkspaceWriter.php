<?php

namespace Zuma\Modulize\Support\PhpStorm;

use Illuminate\Support\Str;
use Zuma\Modulize\Support\ModuleConfig;

class WorkspaceWriter extends ConfigWriter
{
  public function write(): bool
  {
    $config = simplexml_load_string(file_get_contents($this->config_path));
    if (empty($config->xpath('//component[@name="PhpWorkspaceProjectConfiguration"]//include_path//path'))) {
      return true;
    }

    $namespace = config('modules.modules_namespace', 'Modules');
    $vendor = config('modules.modules_vendor') ?? Str::kebab($namespace);
    $module_paths = $this->module_registry->modules()
        ->map(function (ModuleConfig $module) use (&$config, $vendor) {
          return '$PROJECT_DIR$/vendor/'.$vendor.'/'.$module->name;
        });

    $include_paths = $config->xpath('//component[@name="PhpWorkspaceProjectConfiguration"]//include_path//path');

    foreach ($include_paths as $key => $existing) {
      if ($module_paths->contains((string) $existing['value'])) {
        unset($include_paths[$key][0]);
      }
    }

    return false !== file_put_contents($this->config_path, $this->formatXml($config));
  }
}
