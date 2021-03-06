<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\application\services;

use mako\syringe\Container;

/**
 * Abstract service.
 *
 * @author Frederic G. Østby
 */
abstract class Service
{
	/**
	 * Container.
	 *
	 * @var \mako\syringe\Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param \mako\syringe\Container $container Container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Registers the service.
	 */
	abstract public function register();
}
