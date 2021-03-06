<?php

require_once dirname(__FILE__) . '/TextColumn.php';



/**
 * Representation of date data grid column.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @license    New BSD License
 * @example    http://nettephp.com/extras/datagrid
 * @package    Nette\Extras\DataGrid
 * @version    $Id: DateColumn.php 34 2009-07-14 16:32:06Z mail@romansklenar.cz $
 */
class DateColumn extends TextColumn
{
	/** @var string */
	public $format;


	/**
	 * Date column constructor.
	 * @param  string  column's textual caption
	 * @param  string  date format supported by PHP strftime()
	 * @return void
	 */
	public function __construct($caption = NULL, $format = '%x')
	{
		parent::__construct($caption);
		$this->format = $format;
		$this->getHeaderPrototype()->style('width: 80px');
	}


	/**
	 * Formats cell's content.
	 * @param  mixed
	 * @param  DibiRow|array
	 * @return string
	 */
	public function formatContent($value, $data = NULL)
	{
		if ($value == NULL || empty($value)) return 'N/A';
		$value = parent::formatContent($value, $data);

		$value = is_numeric($value) ? (int) $value : ($value instanceof DateTime ? $value->format('U') : strtotime($value));
		return strftime($this->format, $value);
	}


	/**
	 * Applies filtering on dataset.
	 * @param  mixed
	 * @return void
	 */
	public function applyFilter($value)
	{
		if (!$this->hasFilter()) return;

		$datagrid = $this->getDataGrid(TRUE);
		$column = $this->getName();
		$cond = array();
		$cond[] = array("[$column] = %t", $value);
		$datagrid->dataSource->where('%and', $cond);
	}
}