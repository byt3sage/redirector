<?php
/**
 * Redirector plugin for Craft CMS 3.x
 *
 * A simple way to add 301/302 redirects within CraftCMS
 *
 * @link      https://github.com/jaetooledev
 * @copyright Copyright (c) 2021 Jae Toole
 */

namespace jaetooledev\redirectortests\unit;

use Codeception\Test\Unit;
use UnitTester;
use Craft;
use jaetooledev\redirector\Redirector;

/**
 * ExampleUnitTest
 *
 *
 * @author    Jae Toole
 * @package   Redirector
 * @since     1.0.0
 */
class ExampleUnitTest extends Unit
{
    // Properties
    // =========================================================================

    /**
     * @var UnitTester
     */
    protected $tester;

    // Public methods
    // =========================================================================

    // Tests
    // =========================================================================

    /**
     *
     */
    public function testPluginInstance()
    {
        $this->assertInstanceOf(
            Redirector::class,
            Redirector::$plugin
        );
    }

    /**
     *
     */
    public function testCraftEdition()
    {
        Craft::$app->setEdition(Craft::Pro);

        $this->assertSame(
            Craft::Pro,
            Craft::$app->getEdition()
        );
    }
}
