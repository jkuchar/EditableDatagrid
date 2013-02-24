<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2009 John Doe
 * @package    MyApplication
 */



/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class HomepagePresenter extends BasePresenter
{

	function createComponentDataGrid($name) {
		$model = new DatagridModel('customers');
		$grid = new EditableDatagrid();

		// DO NOT REWRITE DATAGRID RENDERER!

		//$translator = new Translator(Environment::expand('%templatesDir%/customersGrid.cs.mo'));
		//$grid->setTranslator($translator);

		$grid->itemsPerPage = 10; // display 10 rows per page
		$grid->displayedItems = array('all', 10, 20, 50); // items per page selectbox items
		//$grid->rememberState = TRUE; // Nepoužívat s TabControlem - když je více DG tak potom se ty stavy nějak popletou mezi s sebou. Pokud je FALSE funguje vše OK. (stavy se pamatují - persistentní pamerty)
		//$grid->timeout = '+ 7 days'; // change session expiration after 7 days
		$grid->bindDataTable($model->getCustomerAndOrderInfo());

		$operations = array('delete' => 'delete', 'deal' => 'deal', 'print' => 'print', 'forward' => 'forward'); // define operations
		// in czech for example: $operations = array('delete' => 'smazat', 'deal' => 'vyřídit', 'print' => 'tisk', 'forward' => 'předat');
		// or you can left translate values by translator adapter
		$callback = array($this, 'handleDemo');
		$grid->allowOperations($operations, $callback, 'customerNumber'); // allows checkboxes to do operations with more rows
		$grid->keyName = "customerNumber";

		/**** add some columns ****/

		$grid->addColumn('customerName', 'Name');
		$grid->addColumn('contactLastName', 'Surname');
		$grid->addColumn('addressLine1', 'Address')->getHeaderPrototype()->addStyle('width: 180px');
		$grid->addColumn('city', 'City');
		$grid->addColumn('country', 'Country');
		$grid->addColumn('postalCode', 'Postal code');
		$caption = Html::el('span')->setText('O')->title('Has orders?')->class('link');
		$grid->addCheckboxColumn('orders', $caption)->getHeaderPrototype()->addStyle('text-align: center');
		$grid->addDateColumn('orderDate', 'Date', '%m/%d/%Y'); // czech format: '%d.%m.%Y'
		$grid->addColumn('status', 'Status');
		$grid->addNumericColumn('creditLimit', 'Credit', 0);


		/**** add some filters ****/

		$grid['customerName']->addFilter();
		$grid['contactLastName']->addFilter();
		$grid['addressLine1']->addFilter();
		$grid['city']->addSelectboxFilter()->translateItems(FALSE);
		$grid['country']->addSelectboxFilter()->translateItems(FALSE);
		$grid['postalCode']->addFilter();
		$grid['orders']->addSelectboxFilter(array('0' => "Don't have", '1' => "Have"), TRUE);
		$grid['orderDate']->addDateFilter();
		$grid['status']->addSelectboxFilter(array('Cancelled' => 'Cancelled', 'Resolved' => 'Resolved', 'Shipped' => 'Shipped', 'NULL' => "Without orders"));
		$grid['creditLimit']->addFilter();


		/**** default sorting and filtering ****/

		$grid['city']->addDefaultSorting('asc');
		$grid['contactLastName']->addDefaultSorting('asc');
		$grid['orders']->addDefaultFiltering(TRUE);
		$grid['country']->addDefaultFiltering('USA');

		/**** column content affecting ****/

		// by css styling
		$grid['orderDate']->getCellPrototype()->addStyle('text-align: center');
		$grid['status']->getHeaderPrototype()->addStyle('width: 60px');
		$grid['addressLine1']->getHeaderPrototype()->addStyle('width: 150px');
		$grid['city']->getHeaderPrototype()->addStyle('width: 90px');

		$grid->setEditForm($this["form"]);
		$grid->addEditableField("customerName");
		$grid->addEditableField("contactLastName");
		$grid->addEditableField("addressLine1");
		$grid->addEditableField("postalCode");
		$grid->addEditableField("status");
		$grid->onDataReceived[] = array($this,"onDataRecieved");
		$grid->onInvalidDataReceived[] = array($this,"onDataRecieved");
		$grid->onInvalidDataReceived[] = array($this,"onInvalidDataRecieved");
		return $grid;
	}

	function createComponentForm($name) {
		$form = new AppForm($this,$name);
		$form->getElementPrototype()->addClass("ajax"); // Zajaxovatění formulářů v jquery.nette.js

		$form->addText("customerName", "Name")
			->addRule(Form::FILLED, "Toto je validační pravidlo v Nette formulářích: políčko je prázdné!");

		$form->addTextArea("contactLastName", "Surname")
			->addRule(Form::FILLED,"Musí být vyplněno!")
			->addRule(Form::REGEXP, "Musí začínat na m!","/^m(.*)$/i");

		$form->addTextArea("addressLine1", "Address")
			->addRule(Form::FILLED, "Toto je validační pravidlo v Nette formulářích: políčko je prázdné!");

		$form->addTextArea("postalCode", "Postal code")
			->addRule(Form::INTEGER, "Toto je validační pravidlo v Nette formulářích: políčko musí být číslo!");

		$form->addSelect("status", "Status",array('Cancelled' => 'Cancelled', 'Resolved' => 'Resolved', 'Shipped' => 'Shipped', 'NULL' => "Without orders"))
			->addRule(Form::FILLED, "Toto je validační pravidlo v Nette formulářích: políčko je prázdné!");

		$form->addSubmit("odeslat", "Odeslat");
		return $form;
	}

	function onDataRecieved($cisloRadku,FormControl $policko,$origSha1) {
		$this->flashMessage("Data přijata na řádku ".$cisloRadku.", data: ".$policko->value." a původní sha1 zadaných dat (kvůli současným úpravám více uživatelů, složí k porovnání s aktuální hodnotou v DB):".$origSha1,"info");
	}

	function onInvalidDataRecieved($cisloRadku,FormControl $policko,$origSha1) {
		$this->flashMessage("Přijatá data jsou neplatná, protože neprošla validací!","error");
	}

	function handleSosej() {
		FileDownload::getInstance()
		    ->setSourceFile(APP_DIR."/EditableDatagrid.zip")
		    ->setSpeedLimit(5*FDTools::KILOBYTE)
		    ->download();
	}

}
