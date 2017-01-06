<?php

/**
 * @copyright Frederic G. Østby
 * @license   http://www.makoframework.com/license
 */

namespace mako\http\request;

use Countable;
use IteratorAggregate;
use RuntimeException;

use mako\security\Signer;

/**
 * Cookies.
 *
 * @author Frederic G. Østby
 */
class Cookies implements Countable, IteratorAggregate
{
	/**
	 * Cookies.
	 *
	 * @var array
	 */
	protected $cookies;

	/**
	 * Signer.
	 *
	 * @var \mako\security\Signer
	 */
	protected $signer;

	/**
	 * Constructor.
	 *
	 * @param array                 $cookies Cookies
	 * @param \mako\security\Signer $signer  Signer
	 */
	public function __construct(array $cookies, Signer $signer = null)
	{
		$this->cookies = $cookies;

		$this->signer = $signer;
	}

	/**
	 * Returns the numner of cookies.
	 *
	 * @access public
	 * @return int
	 */
	public function count(): int
	{
		return count($this->cookies);
	}

	/**
	 * Retruns an array iterator object.
	 *
	 * @access public
	 * @return \ArrayIterator
	 */
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->cookies);
	}

	/**
	 * Adds a cookie.
	 *
	 * @access public
	 * @param string $name  Cookie name
	 * @param string $value Cookie value
	 */
	public function add(string $name, string $value)
	{
		$this->cookies[$name] = $value;
	}

	/**
	 * Adds a signed cookie.
	 *
	 * @access public
	 * @param string $name  Cookie name
	 * @param string $value Cookie value
	 */
	public function addSigned(string $name, string $value)
	{
		$this->cookies[$name] = $this->signer->sign($value);
	}

	/**
	 * Returns true if the cookie exists and false if not.
	 *
	 * @access public
	 * @param  string $name Cookie name
	 * @return bool
	 */
	public function has(string $name): bool
	{
		return isset($this->cookies[$name]);
	}

	/**
	 * Gets a cookie value.
	 *
	 * @access public
	 * @param  string     $name    Cookie name
	 * @param  null|mixed $default Default value
	 * @return null|mixed
	 */
	public function get(string $name, $default = null)
	{
		return $this->cookies[$name] ?? $default;
	}

	/**
	 * Gets a signed cookie value.
	 *
	 * @access public
	 * @param  string     $name    Cookie name
	 * @param  null|mixed $default Default value
	 * @return null|mixed
	 */
	public function getSigned(string $name, $default = null)
	{
		if(empty($this->signer))
		{
			throw new RuntimeException(vsprintf("%s(): A [ Signer ] instance is required to read signed cookies.", [__METHOD__]));
		}

		if(isset($this->cookies[$name]) && ($cookie = $this->signer->validate($this->cookies[$name])) !== false)
		{
			return $cookie;
		}

		return $default;
	}

	/**
	 * Removes a cookie.
	 *
	 * @access public
	 * @param string $name Cookie name
	 */
	public function remove(string $name)
	{
		unset($this->cookies[$name]);
	}

	/**
	 * Returns all the cookies.
	 *
	 * @access public
	 * @return array
	 */
	public function all(): array
	{
		return $this->cookies;
	}
}
