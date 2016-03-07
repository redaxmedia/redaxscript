<?php
namespace Redaxscript\Tests;

use Redaxscript\Auth;

/**
 * AuthTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class AuthTest extends TestCase
{
    /**
     * instance of the request class
     *
     * @var object
     */

    protected $_request;

    /**
     * setUp
     *
     * @since 3.0.0
     */

    protected function setUp()
    {
        $this->_request = Request::getInstance();
    }
}