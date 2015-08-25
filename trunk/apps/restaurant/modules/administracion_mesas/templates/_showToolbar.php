<?php use_helper('Javascript') ?>

<?php echo javascript_tag('
	createPushButton("edit");
	createPushButton("delete");
	createPushButton("back");
') ?>

<div id="toolbar">
	<div id="general_actions">
		<div class="toolbar_button_container">
			<a id="edit" href="<?php echo url_for($sf_context->getModuleName().'/edit?id='.$model->getId()) ?>">
				<?php echo __('Edit') ?>
			</a>
		</div>
		<div class="toolbar_button_container">
			<a id="delete" href="<?php echo url_for($sf_context->getModuleName().'/delete?id='.$model->getId()) ?>"
				onclick="confirmSingleDelete('<?php echo $modelName ?>>')">
				<?php echo __('Delete') ?>	
			</a>
		</div>
		<div class="toolbar_button_container">
			<a id="back" href="<?php echo url_for($sf_context->getModuleName().'/index') ?>">
				<?php echo __('Back to the list') ?>	
			</a>
		</div>
			

	</div>

</div>