<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2009 John Doe
 * @package    MyApplication
 */



/**
 * Base class for all application presenters.
 *
 * @author     John Doe
 * @package    MyApplication
 */
abstract class BasePresenter extends Presenter
{
	public $oldLayoutMode = FALSE;

	protected function startup() {
		$session = Environment::getSession();
		if (!$session->isStarted()) {
			$session->start();
		}
		parent::startup();
	}

	/**
	 * Saves the message to template, that can be displayed after redirect.
	 * @param  string
	 * @param  string
	 * @return stdClass
	 */
	public function flashMessage($message, $type = 'info') {
		$this->invalidateControl("flashes");
		parent::flashMessage($message, $type);
	}
}
