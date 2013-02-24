<?php //netteCache[01]000241a:2:{s:4:"time";s:21:"0.33937700 1263847305";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:5:"Cache";i:1;s:9:"checkFile";}i:1;s:86:"D:\www\00_Vyvoj\EditableDataGrid\document_root/../app/templates/Homepage/default.phtml";i:2;i:1263847301;}}}?><?php
// file …/templates/Homepage/default.phtml
//

$_cb = LatteMacros::initRuntime($template, NULL, 'aefccdbef2'); unset($_extends);


//
// block content
//
if (!function_exists($_cb->blocks['content'][] = '_cbbbd525edd06_content')) { function _cbbbd525edd06_content() { extract(func_get_arg(0))
;if (SnippetHelper::$outputAllowed) { ?>
<h1>Editable datagrid (pre-alfa/draft)</h1>

<h2>Co to umí?</h2>
<ul>
	<li>Editovat všechno možné přímo v datagridu</li>
	<li>KISS - keep it simple, nic nepíšete 2x</li>
	<li>Propojení s formuláři v Nette</li>
	<li>Má ošetřeno když více uživatelů vyedituje stejnou buňku. (hashe, které by jste si měli v callbacku pro ukládání zkontrolovat, než data uložíte)</li>
</ul>

<h2>Optimistická poznámka</h2>
<p>
	Tento program je zatím ve fázi vývoje! Na jakékoli dotazy ohledně použití/nasazení nebude brán zřetel.
	Pokud posunete tento produkt dál, budu rád pokud mi o své modifikaci napíšete.
	Ideálně pokud se bude jednat o nástupce. Nahradím stávající verzi tou Vaší.<br>
	Já osobně jsem tento projekt zamrazil (alespoň dočasně), tzn. jestli se bude vyvýjet či nikoli, je čistě a jen na Vás.
</p>

<h2>Kodéři! Vy to rači vůbec nestahujte!</h2>
<p>
	Kód je okomentován dost sporadicky a je zatím ve stádiu: "Jé ono to funguje!".
	Rozhodně se z něho nesnažte něco naučit, ale hlavně ho neberte jako vzor pro svoje aplikace.
</p>

<h2>Závislosti</h2>
<p>
	Tento projekt je závislý na hromadě dalších (zejména na straně klienta).
	Na straně serveru vyžaduje Nette 0.9.2 (na té je to tostováno, možná to pojede i na něčem jiném).
</p>

<h2>Ukázka</h2>
<p>
	V této živé ukázce můžete editovat o 106, nic se nikde nesmaže ani nepokazí. :)
</p>

<h2>Já to chci!</h2>
<h3>No tak moment, nejdřív LICENCE</h3>
<p>
	<a href="http://www.gnu.org/licenses/lgpl.txt">LGPL</a>
</p>
<h3>Mno jo, už to bude</h3>
<p>
	Teď si to 2x rozmyslete, jestli to fakt chcete stáhnout. Za nic neručím.<br>
	<a href="<?php echo TemplateHelpers::escapeHtml($control->link("sosej!")) ?>">Sosejte zde!</a><br>
	Stahuje se to nějak pomalu? Mno, tak nečekejte nečině a přečtěte si 
	<a href="http://addons.nettephp.com/cs/file-downloader" target="_BLANK">o FileDownloaderu</a>, <a target="_BLANK" href="http://addons.nettephp.com/cs/TabControl">o TabControlu</a> nebo o něčem <a href="http://addons.nettephp.com/cs/" target="_BLANK">dalším</a>.
</p>

<?php } $control->getWidget("dataGrid")->renderEditable() ;if (SnippetHelper::$outputAllowed) { ?>

<?php
}}

//
// end of blocks
//

if ($_cb->extends) { ob_start(); }

if (SnippetHelper::$outputAllowed) {
} if (!$_cb->extends) { call_user_func(reset($_cb->blocks['content']), get_defined_vars()); }  
}

if ($_cb->extends) { ob_end_clean(); LatteMacros::includeTemplate($_cb->extends, get_defined_vars(), $template)->render(); }
