<?php

namespace SebastiaanLuca\Router\Tests\Unit;

use Illuminate\Contracts\Routing\Registrar;
use SebastiaanLuca\Router\Routers\Router;
use SebastiaanLuca\Router\Tests\TestCase;

class RouterTest extends TestCase
{
    public function testItMapsRoutes()
    {
        $registrar = $this->mock(Registrar::class);

        $registrar->shouldReceive('get')->twice()->with('/', ['as' => 'test']);

        $router = $this->createRouter($registrar);

        $router->map();
    }

    public function testItMapsRoutesOnCreation()
    {
        $registrar = $this->mock(Registrar::class);

        $registrar->shouldReceive('get')->once()->with('/', ['as' => 'test']);

        $this->createRouter($registrar);
    }

    public function testItSetsTheNamespace()
    {
        $router = $this->createRouter();

        $this->assertEquals('App\\Http\\Controllers', $router->getNamespace());
        $this->assertEquals('App\\Http\\Controllers\\Users\\Auth', $router->getNamespace('Users\\Auth'));
    }

    /**
     * @param \Illuminate\Contracts\Routing\Registrar|null $registrar
     *
     * @return \SebastiaanLuca\Router\Routers\Router
     */
    protected function createRouter(?Registrar $registrar = null) : Router
    {
        if (! $registrar) {
            $registrar = app(Registrar::class);
        }

        return new class($registrar) extends Router
        {
            /**
             * The default controller namespace.
             *
             * @var string
             */
            protected $namespace = 'App\\Http\\Controllers';

            /**
             * Map the routes.
             */
            public function map()
            {
                $this->router->get('/', ['as' => 'test']);
            }
        };
    }
}