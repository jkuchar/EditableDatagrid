<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2009 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 */



require_once dirname(__FILE__) . '/IServiceLocator.php';

require_once dirname(__FILE__) . '/Object.php';



/**
 * Service locator pattern implementation.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
class ServiceLocator extends Object implements IServiceLocator
{
	/** @var IServiceLocator */
	private $parent;

	/** @var array  storage for shared objects */
	private $registry = array();

	/** @var array  storage for service factories */
	private $factories = array();



	/**
	 * @param  IServiceLocator
	 */
	public function __construct(IServiceLocator $parent = NULL)
	{
		$this->parent = $parent;
	}



	/**
	 * Adds the specified service to the service container.
	 * @param  string service name
	 * @param  mixed  object, class name or factory callback
	 * @param  bool   is singleton?
	 * @param  array  factory options
	 * @return void
	 */
	public function addService($name, $service, $singleton = TRUE, array $options = NULL)
	{
		if (!is_string($name) || $name === '') {
			throw new InvalidArgumentException("Service name must be a non-empty string, " . gettype($name) . " given.");
		}

		$lower = strtolower($name);
		if (isset($this->registry[$lower])) { // only for instantiated services?
			throw new AmbiguousServiceException("Service named '$name' has been already registered.");
		}

		if (is_object($service)) {
			if (!$singleton || $options) {
				throw new InvalidArgumentException("Service named '$name' is an instantiated object and must therefore be singleton without options.");
			}
			$this->registry[$lower] = $service;

		} else {
			if (!$service) {
				throw new InvalidArgumentException("Service named '$name' is empty.");
			}
			$this->factories[$lower] = array($service, $singleton, $options);
		}
	}



	/**
	 * Removes the specified service type from the service container.
	 * @return void
	 */
	public function removeService($name)
	{
		if (!is_string($name) || $name === '') {
			throw new InvalidArgumentException("Service name must be a non-empty string, " . gettype($name) . " given.");
		}

		$lower = strtolower($name);
		unset($this->registry[$lower], $this->factories[$lower]);
	}



	/**
	 * Gets the service object of the specified type.
	 * @param  string service name
	 * @param  array  options in case service is not singleton
	 * @return mixed
	 */
	public function getService($name, array $options = NULL)
	{
		if (!is_string($name) || $name === '') {
			throw new InvalidArgumentException("Service name must be a non-empty string, " . gettype($name) . " given.");
		}

		$lower = strtolower($name);

		if (isset($this->registry[$lower])) { // instantiated singleton
			if ($options) {
				throw new InvalidArgumentException("Service named '$name' is singleton and therefore can not have options.");
			}
			return $this->registry[$lower];

		} elseif (isset($this->factories[$lower])) {
			list($factory, $singleton, $defOptions) = $this->factories[$lower];

			if ($singleton && $options) {
				throw new InvalidArgumentException("Service named '$name' is singleton and therefore can not have options.");

			} elseif ($defOptions) {
				$options = $options ? $options + $defOptions : $defOptions;
			}

			if (is_string($factory) && strpos($factory, ':') === FALSE) { // class name
				fixNamespace($factory);
				if (!class_exists($factory)) {
					throw new AmbiguousServiceException("Cannot instantiate service '$name', class '$factory' not found.");
				}
				$service = new $factory;
				if ($options && method_exists($service, 'setOptions')) {
					$service->setOptions($options); // TODO: better!
				}

			} else { // factory callback
				fixCallback($factory);
				if (!is_callable($factory)) {
					$able = is_callable($factory, TRUE, $textual);
					throw new AmbiguousServiceException("Cannot instantiate service '$name', handler '$textual' is not " . ($able ? 'callable.' : 'valid PHP callback.'));
				}
				$service = call_user_func($factory, $options);
				if (!is_object($service)) {
					$able = is_callable($factory, TRUE, $textual);
					throw new AmbiguousServiceException("Cannot instantiate service '$name', value returned by '$textual' is not object.");
				}
			}

			if ($singleton) {
				$this->registry[$lower] = $service;
				unset($this->factories[$lower]);
			}
			return $service;
		}

		if ($this->parent !== NULL) {
			return $this->parent->getService($name, $options);

		} else {
			throw new InvalidStateException("Service '$name' not found.");
		}
	}



	/**
	 * Exists the service?
	 * @param  string service name
	 * @param  bool   must be created yet?
	 * @return bool
	 */
	public function hasService($name, $created = FALSE)
	{
		if (!is_string($name) || $name === '') {
			throw new InvalidArgumentException("Service name must be a non-empty string, " . gettype($name) . " given.");
		}

		$lower = strtolower($name);
		return isset($this->registry[$lower]) || (!$created && isset($this->factories[$lower])) || ($this->parent !== NULL && $this->parent->hasService($name, $created));
	}



	/**
	 * Returns the parent container if any.
	 * @return IServiceLocator|NULL
	 */
	public function getParent()
	{
		return $this->parent;
	}

}



/**
 * Ambiguous service resolution exception.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette
 */
class AmbiguousServiceException extends Exception
{
}
