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
 * @package    Nette\Forms
 */



require_once dirname(__FILE__) . '/../../Forms/FormContainer.php';

require_once dirname(__FILE__) . '/../../Forms/IFormControl.php';



/**
 * A control that repeats a specified prototype for each item in the list.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2009 David Grudl
 * @package    Nette\Forms
 */
class RepeaterControl extends FormContainer /*implements IFormControl*/
{
	/** @var int */
	public $repeatCount = 3;

	/** @var int */
	public $repeatMin = 1;

	/** @var int */
	public $repeatMax = 0;

	/** @var array */
	protected $value;


	/**
	 */
	public function __construct()
	{
		throw new NotImplementedException;
	}



	/**
	 * Set value.
	 * @param  mixed
	 * @return RepeaterControl  provides a fluent interface
	 */
	public function setValue($value)
	{
		if (is_array($value)) {
			$this->value = $value;
		} else {
			$this->value = array();
		}
		return $this;
	}



	/**
	 * Get value.
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}



	/**
	 * Load HTTP data.
	 * @return void
	 */
	public function loadHttpData()
	{
		$name = $this->getName();
		$this->setValue(isset($data[$name]) ? $data[$name] : array());
	}

}