<?php //netteCache[01]000233a:2:{s:4:"time";s:21:"0.72325400 1263846229";s:9:"callbacks";a:1:{i:0;a:3:{i:0;a:2:{i:0;s:5:"Cache";i:1;s:9:"checkFile";}i:1;s:78:"D:\www\00_Vyvoj\EditableDataGrid\document_root/../app/templates//@layout.phtml";i:2;i:1263845300;}}}?><?php
// file …/templates//@layout.phtml
//

$_cb = LatteMacros::initRuntime($template, NULL, '6099721270'); unset($_extends);

if (SnippetHelper::$outputAllowed) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<meta name="description" content="Nette Framework web application skeleton"><?php if (isset($robots)): ?>
	<meta name="robots" content="<?php echo TemplateHelpers::escapeHtml($robots) ?>">
<?php endif ?>

	<title>Nette Application Skeleton</title>

	<link rel="stylesheet" media="screen,projection,tv" href="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/css/screen.css" type="text/css">
	<link rel="stylesheet"  href="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/css/icons.css" type="text/css">
	<link rel="stylesheet"  href="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/css/datagrid.css" type="text/css">
	<link rel="stylesheet"  href="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/css/smoothness-upraveno/ui.all.css" type="text/css">
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/jquery.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/jquery.livequery.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/jquery.editinplace.0.4.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/jquery.nette.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/jquery-ui.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/datagrid.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/PHP.js/array_search.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/PHP.js/rawurlencode.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/PHP.js/sha1.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/PHP.js/str_replace.js" type="text/javascript"></script>
	<script src="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/js/PHP.js/utf8_encode.js" type="text/javascript"></script>
	
	<link rel="stylesheet" media="print" href="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/css/print.css" type="text/css">
	<link rel="shortcut icon" href="<?php echo TemplateHelpers::escapeHtml($basePath) ?>/favicon.ico" type="image/x-icon">
</head>

<body>
	<?php } LatteMacros::callBlock($_cb->blocks, 'content', get_defined_vars()) ;if (SnippetHelper::$outputAllowed) { ?>

	Flash messages:
<?php } if ($_cb->foo = SnippetHelper::create($control, "flashes")) { $_cb->snippets[] = $_cb->foo ?>
		<?php foreach ($iterator = $_cb->its[] = new SmartCachingIterator($flashes) as $flash): ?><div class="flash <?php echo TemplateHelpers::escapeHtml($flash->type) ?>"><?php echo TemplateHelpers::escapeHtml($flash->message) ?></div><?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>

<?php array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) { ?>
</body>
</html>
<?php
}
