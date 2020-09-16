<?php

namespace DigitalEquation\Teamwork;

use Illuminate\Support\Facades\Facade;

class TeamworkFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'teamwork';
    }
}
