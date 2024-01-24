<?php

namespace Zuma\Modulize\Exceptions;

use Throwable;

class CannotFindModuleForPathException extends Exception
{
  public function __construct(string $path, Throwable $previous = null)
  {
    parent::__construct("Unable to determine module for '{$path}'", 0, $previous);
  }
}
