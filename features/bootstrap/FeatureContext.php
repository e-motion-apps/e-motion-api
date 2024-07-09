<?php

use Behat\Behat\Context\Context;
use Blumilk\BLT\Bootstrapping\LaravelBootstrapper;
use Blumilk\BLT\Features\Hooks\RefreshDatabaseBeforeScenario;
use Blumilk\BLT\Features\Toolbox;
use Blumilk\BLT\Features\Traits\Eloquent;
use Blumilk\BLT\Features\Traits\Http;
use Blumilk\BLT\Features\Traits\Optional\SpatiePermission;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends Toolbox implements Context
{
    use SpatiePermission;
    use RefreshDatabaseBeforeScenario;

use Http;
use Eloquent;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $bootstrapper = new LaravelBootstrapper();
        $bootstrapper->boot();
    }
}
