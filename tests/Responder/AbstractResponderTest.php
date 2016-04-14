<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder;

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Aura\Payload\Payload;

abstract class AbstractResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = ServerRequestFactory::fromGlobals();
        $this->response = new Response;
        $this->payload = new Payload;
    }
}

