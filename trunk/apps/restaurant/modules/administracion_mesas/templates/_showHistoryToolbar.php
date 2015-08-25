<?php use_helper('Javascript') ?>

<?php echo javascript_tag('
	createPushButton("backEdit");
	createPushButton("back");
') ?>

<div id="toolbar">
	<div id="general_actions">
		<div class="toolbar_button_container">
			<a id="backEdit" href="<?php echo url_for($sf_context->getModuleName().'/show?id='.$model->getId()) ?>">
				<?php echo __('Back') ?>	
			</a>
		</div>
		<div class="toolbar_button_container">
			<a id="back" href="<?php echo url_for($sf_context->getModuleName().'/index') ?>">
				<?php echo __('Back to the list') ?>	
			</a>
		</div>
	</div>
</div>