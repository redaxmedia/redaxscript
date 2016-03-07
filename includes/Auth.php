<?php
namespace Redaxscript;

/**
 * parent class to authenticate the user
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Auth
 * @author Henry Ruhs
 */

class Auth
{
    /**
     * instance of the request class
     *
     * @var object
     */

    protected $_request;

    /**
     * constructor of the class
     *
     * @since 3.0.0
     *
     * @param Request $request instance of the request class
     */

    public function __construct(Request $request)
    {
        $this->_request = $request;
    }
}