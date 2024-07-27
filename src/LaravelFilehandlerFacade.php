<?php

namespace LaravelGuru\LaravelFilehandler;

use Illuminate\Support\Facades\Facade;
use LaravelGuru\LaravelFilehandler\Services\FileService;

/**
 * @see \LaravelGuru\LaravelFilehandler\Skeleton\SkeletonClass
 */
class LaravelFilehandlerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FileService::class;
    }
}
