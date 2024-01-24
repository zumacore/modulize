<?php

namespace Zuma\Modulize\Support;

use SplFileInfo;
use Zuma\Modulize\Support\Facades\Modules;

class DiscoverEvents extends \Illuminate\Foundation\Events\DiscoverEvents
{
  protected static function classFromFile(SplFileInfo $file, $basePath)
  {
    if ($module = Modules::moduleForPath($file->getRealPath())) {
      return $module->pathToFullyQualifiedClassName($file->getPathname());
    }

    return parent::classFromFile($file, $basePath);
  }
}
