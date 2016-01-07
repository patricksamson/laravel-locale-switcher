<?php
namespace Lykegenes\LocaleSwitcher\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @codeCoverageIgnore
 */
class LocaleSwitcher extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Lykegenes\LocaleSwitcher\LocaleSwitcher::class;
    }
}
