<?php

/**
 * @copyright  Frederic G. Østby
 * @license    http://www.makoframework.com/license
 */

namespace mako\core\services;

use \mako\cache\CacheManager;

/**
 * Cache service.
 *
 * @author  Frederic G. Østby
 */

class CacheService extends \mako\core\services\Service
{
	//---------------------------------------------
	// Class properties
	//---------------------------------------------

	// Nothing here

	//---------------------------------------------
	// Class constructor, destructor etc ...
	//---------------------------------------------

	// Nothing here

	//---------------------------------------------
	// Class methods
	//---------------------------------------------
	
	/**
	 * Registers the service.
	 * 
	 * @access  public
	 */

	public function register()
	{
		$this->application->registerSingleton(['mako\cache\CacheManager', 'cache'], function($app)
		{
			$config = $app->getConfig()->get('cache');

			return new CacheManager($config['default'], $config['configurations'], $app);
		});
	}
}