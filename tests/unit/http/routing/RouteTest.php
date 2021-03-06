<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\tests\unit\http\routing;

use mako\http\routing\Route;
use mako\tests\TestCase;

/**
 * @group unit
 */
class RouteTest extends TestCase
{
	/**
	 *
	 */
	public function testBasicMatch()
	{
		$route = new Route(['GET'], '/', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/'));

		//

		$route = new Route(['GET'], '/foo', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/'));

		//

		$route = new Route(['GET'], '/foo/', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo'));

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/'));
	}

	/**
	 *
	 */
	public function testMatchWithParameter()
	{
		$route = new Route(['GET'], '/foo/{id}', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/123/'));

		//

		$route = new Route(['GET'], '/foo/{id}/', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/'));

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/'));
	}

	/**
	 *
	 */
	public function testMatchWithParameters()
	{
		$route = new Route(['GET'], '/foo/{id}/{slug}', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/foo-bar'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/123'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/123/foo-bar/'));

		//

		$route = new Route(['GET'], '/foo/{id}/{slug}/', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/foo-bar/'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/123/'));

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/foo-bar'));
	}

	/**
	 *
	 */
	public function testMatchWithOptionalParameters()
	{
		$route = new Route(['GET'], '/foo/{id}/{slug}?', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/foo-bar'));

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/123/'));

		//

		$route = new Route(['GET'], '/foo/{id}/{slug}?/', 'FooController::fooAction');

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/foo-bar/'));

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/'));

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123'));
	}

	/**
	 *
	 */
	public function testMatchWithParameterConstraints()
	{
		$route = (new Route(['GET'], '/foo/{id}', 'FooController::fooAction'))->patterns(['id' => '[0-9]+']);

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/123/'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/abc'));

		//

		$route = (new Route(['GET'], '/foo/{id}/', 'FooController::fooAction'))->patterns(['id' => '[0-9]+']);

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123'));

		$this->assertSame(1, preg_match($route->getRegex(), '/foo/123/'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/abc'));

		$this->assertSame(0, preg_match($route->getRegex(), '/foo/abc/'));
	}

	/**
	 *
	 */
	public function testHasTrailingSlash()
	{
		$route = new Route(['GET'], '/foo', 'FooController::fooAction');

		$this->assertFalse($route->hasTrailingSlash());

		//

		$route = new Route(['GET'], '/foo/', 'FooController::fooAction');

		$this->assertTrue($route->hasTrailingSlash());
	}

	/**
	 *
	 */
	public function testAllows()
	{
		$route = new Route(['GET'], '/foo', 'FooController::fooAction');

		$this->assertTrue($route->allowsMethod('GET'));

		$this->assertFalse($route->allowsMethod('POST'));

		//

		$route = new Route(['GET', 'POST'], '/foo', 'FooController::fooAction');

		$this->assertTrue($route->allowsMethod('GET'));

		$this->assertTrue($route->allowsMethod('POST'));
	}

	/**
	 *
	 */
	public function testGetMethods()
	{
		$route = new Route(['GET'], '/foo', 'FooController::fooAction');

		$this->assertEquals(['GET'], $route->getMethods());

		//

		$route = new Route(['GET', 'POST'], '/foo', 'FooController::fooAction');

		$this->assertEquals(['GET', 'POST'], $route->getMethods());
	}

	/**
	 *
	 */
	public function testGetRoute()
	{
		$route = new Route(['GET'], '/foo', 'FooController::fooAction');

		$this->assertEquals('/foo', $route->getRoute());
	}

	/**
	 *
	 */
	public function testGetAction()
	{
		$route = new Route(['GET'], '/foo', 'FooController::fooAction');

		$this->assertEquals('FooController::fooAction', $route->getAction());
	}

	/**
	 *
	 */
	public function testGetName()
	{
		$route = new Route(['GET'], '/foo', 'FooController::fooAction', 'foo');

		$this->assertEquals('foo', $route->getName());
	}

	/**
	 *
	 */
	public function testPrefix()
	{
		$route = (new Route(['GET'], '/foo', 'FooController::fooAction'))->prefix('bar');

		$this->assertEquals('/bar/foo', $route->getRoute());

		//

		$route = (new Route(['GET'], '/foo', 'FooController::fooAction'))->prefix('/bar');

		$this->assertEquals('/bar/foo', $route->getRoute());

		//

		$route = (new Route(['GET'], '/foo', 'FooController::fooAction'))->prefix('bar')->prefix('baz');

		$this->assertEquals('/bar/baz/foo', $route->getRoute());
	}

	/**
	 *
	 */
	public function testGetRegex()
	{
		$route = new Route(['GET'], '/', 'FooController::fooAction');

		$this->assertSame('#^/?$#su', $route->getRegex());

		//

		$route = new Route(['GET'], '/foo', 'FooController::fooAction');

		$this->assertSame('#^/foo$#su', $route->getRegex());

		//

		$route = new Route(['GET'], '/foo/', 'FooController::fooAction');

		$this->assertSame('#^/foo/?$#su', $route->getRegex());

		//

		$route = new Route(['GET'], '/foo/bar', 'FooController::fooAction');

		$this->assertSame('#^/foo/bar$#su', $route->getRegex());

		//

		$route = new Route(['GET'], '/{id}', 'FooController::fooAction');

		$this->assertSame('#^/(?P<id>[^/]++)$#su', $route->getRegex());

		//

		$route = new Route(['GET'], '/foo/{id}', 'FooController::fooAction');

		$this->assertSame('#^/foo/(?P<id>[^/]++)$#su', $route->getRegex());

		//

		$route = new Route(['GET'], '/foo/{id}/bar', 'FooController::fooAction');

		$this->assertSame('#^/foo/(?P<id>[^/]++)/bar$#su', $route->getRegex());

		//

		$route = new Route(['GET'], '/foo/{id}/', 'FooController::fooAction');

		$this->assertSame('#^/foo/(?P<id>[^/]++)/?$#su', $route->getRegex());

		//

		$route = (new Route(['GET'], '/foo/{id}', 'FooController::fooAction'))->patterns(['id' => '[0-9]+']);

		$this->assertSame('#^/foo/(?P<id>[0-9]+)$#su', $route->getRegex());
	}

	/**
	 *
	 */
	public function testNamespace()
	{
		$route = (new Route(['GET'], '/', 'FooController::fooAction'))->namespace('app\controllers');

		$this->assertSame('app\controllers\FooController::fooAction', $route->getAction());
	}

	/**
	 *
	 */
	public function testNestedNamespace()
	{
		$route = (new Route(['GET'], '/', 'FooController::fooAction'))->namespace('app')->namespace('controllers');

		$this->assertSame('app\controllers\FooController::fooAction', $route->getAction());
	}

	/**
	 *
	 */
	public function testClosureNamespace()
	{
		$route = (new Route(['GET'], '/', function() {}))->namespace('app\controllers');

		$this->assertInstanceOf('Closure', $route->getAction());
	}

	/**
	 *
	 */
	public function testMiddleware()
	{
		$route = (new Route(['GET'], '/', 'FooController::fooAction'))->middleware('foo');

		$this->assertSame(['foo'], $route->getMiddleware());

		//

		$route = (new Route(['GET'], '/', 'FooController::fooAction'))->middleware(['foo', 'bar']);

		$this->assertSame(['foo', 'bar'], $route->getMiddleware());

		//

		$route = (new Route(['GET'], '/', 'FooController::fooAction'))->middleware('foo')->middleware('bar');

		$this->assertSame(['foo', 'bar'], $route->getMiddleware());
	}

	/**
	 *
	 */
	public function testSetAndGetParameters()
	{
		$route = new Route(['GET'], '/', 'FooController::fooAction');

		$parameters = ['foo' => 'bar', 'baz' => 'bax'];

		$route->setParameters($parameters);

		$this->assertSame($route->getParameter('foo'), 'bar');

		$this->assertSame($route->getParameter('baz'), 'bax');

		$this->assertNull($route->getParameter('nope'));

		$this->assertTrue($route->getParameter('nope', true));

		$this->assertSame($parameters, $route->getParameters());
	}
}
