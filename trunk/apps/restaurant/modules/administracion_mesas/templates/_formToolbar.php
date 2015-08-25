<?php use_helper('Javascript') ?>
<?php echo javascript_tag('
	
	createPushButton("accept");
	createPushButton("cancel");
	
	function submitForm() {
		document.getElementById("item_form").submit();
		return true;
	}
') ?>

<div id="toolbar">

	<div id="general_actions">

		<div class="toolbar_button_container">
			<a id="accept" href="javascript:submitForm()"><?php echo __('Accept') ?></a>
		</div>			

		<div class="toolbar_button_container">
         <?php echo link_to(__('Cancel'), $sf_context->getModuleName().'/index', array('id' => 'cancel')) ?>		</div>


	</div>

</div>