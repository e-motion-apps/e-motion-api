<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Blumilk\BLT\Features\Toolbox;


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends Toolbox implements Context
{
    use \Blumilk\BLT\Features\Traits\Optional\SpatiePermission;
    use \Blumilk\BLT\Features\Hooks\RefreshDatabaseBeforeScenario;
    use \Blumilk\BLT\Features\Traits\CodeBlocs;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $bootstrapper = new \Blumilk\BLT\Bootstrapping\LaravelBootstrapper();
        $bootstrapper->boot();
    }
}
