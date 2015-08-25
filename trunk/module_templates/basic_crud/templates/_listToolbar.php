<?php use_helper('Javascript') ?>
<?php echo javascript_tag('
	createPushButton("new");
	createMenuButton("batch_actions_form", "bacthsubmit", "bacthsubmitselect", "batch_actions_container");
	createPushButton("toggleAll");
')
?>
<div id="toolbar">
	<div id="general_actions">
		<div class="toolbar_button_container">
			<a id="new" href="<?php echo url_for($sf_context->getModuleName().'/new') ?>">
				<?php echo __(ucwords('New '.$modelSingular)) ?>
			</a>
		</div>
		<div id="batch_actions_container" class="toolbar_button_container">
			<form id="batch_actions_form" action="<?php echo url_for($sf_context->getModuleName().'/batchAction')?>" method="POST" 
			<?php echo 'onsubmit=\'return setSelectedIds("'.$modelSingular.'", "'.$modelPlural.'", "batch_actions_form")\'' ?> >
				<input type="submit" id="bacthsubmit" name="batchsubmit_button" value="<?php echo __('Action') ?>"> 
				<input type="hidden" id="ids" name="ids" value="">
				<select id="bacthsubmitselect" name="batchsubmitselect" multiple> 
				    <option value="0"><?php echo __('Delete') ?></option> 
				</select> 
			</form>
		</div>
		<div class="toolbar_button_container">
			<a id="toggleAll" href="javascript:toggleAll()">
				<?php echo __('Select/Unselect All') ?>
			</a>
		</div>


	</div>


</div>