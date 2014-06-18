<?php

namespace TwigBridge\Tests\ServiceProvider\Bindings;

use TwigBridge\Tests\Base;
use Mockery as m;
use TwigBridge\ServiceProvider;

class BridgeTest extends Base
{
    public function testInstance()
    {
        $app      = $this->getApplication();
        $provider = new ServiceProvider($app);
        $provider->boot();

        $this->assertInstanceOf('TwigBridge\Bridge', $app['twig.bridge']);
    }

    public function testSetLexer()
    {
        $app = $this->getApplication();
        $app['twig.lexer'] = m::mock('Twig_LexerInterface');
        $app['twig.lexer']->shouldReceive('fooBar')->andReturn('baz');

        $provider = new ServiceProvider($app);
        $provider->boot();

        $bridge = $app['twig.bridge'];

        $this->assertEquals($bridge->getLexer()->fooBar(), 'baz');
    }

    public function testAddExtensions()
    {
        $app      = $this->getApplication();
        $provider = new ServiceProvider($app);
        $provider->boot();

        $called = false;
        $app->resolving('twig.extensions', function () use (&$called) {
            $called = true;
        });

        $app['twig.bridge'];
        $this->assertTrue($called);
    }
}
