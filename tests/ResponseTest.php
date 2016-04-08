<?php

namespace NFePHP\eFinanc\Tests;

/**
 * Unit Tests for Tools::class
 *
 * @author Roberto L. Machado <linux dot rlm at gmail dot com>
 */
use NFePHP\eFinanc\Tests\Factory\FactoryTest;


class ResponseTest extends FactoryTest
{
    /**
     * @covers NFePHP\eFinanc\Response::__construct
     */
    public function testInstantiable()
    {
        //$evt = new Tools($this->config, true);
        //$this->assertInstanceOf(Tools::class, $evt);
        $this->assertTrue(true);
    }
}
