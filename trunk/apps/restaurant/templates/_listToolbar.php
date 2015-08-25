<?php use_helper('Javascript') ?>
<?php echo javascript_tag('
	createPushButton("new");
	
')
?>
<div id="toolbar">
	<div id="general_actions">
		<div class="toolbar_button_container">
			<a id="new" href="<?php echo url_for($sf_context->getModuleName().'/new') ?>">
				<?php echo ('Crear '.$modelSingular) ?>
			</a>
		</div>
	</div>

</div>