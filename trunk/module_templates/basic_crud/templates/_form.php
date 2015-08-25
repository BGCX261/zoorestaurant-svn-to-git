<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<?php use_helper('I18N') ?>

<?php include_partial("formToolbar") ?>

<?php include(sfConfig::get('sf_app_template_dir').'/error.php'); ?>
<?php include(sfConfig::get('sf_app_template_dir').'/message.php'); ?>

<div class="sf_admin_form">
	<div class="form_body">
	<form id="item_form" action="<?php echo url_for($sf_context->getModuleName().'/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php echo $form->renderHiddenFields() ?>

	<div class="form_global_errors">
	    <?php if ($form->hasGlobalErrors()): ?>
	      <?php echo $form->renderGlobalErrors() ?>
	    <?php endif; ?>
	</div>
	
    <?php foreach ($form->getFormFieldSchema() as $field): ?>

    <?php if (!$field->isHidden()): ?>
	
	  <div class="form_field">
		<div class="form_field_errors">
	    	<?php echo $field->renderError() ?>
	    </div>
	    <div class = "form_field_entry">
	      <div class="form_field_label">
	      <?php echo $field->renderLabel() ?>
		  </div>
		  <div class="form_field_input">
	     	<?php echo $field->render() ?>
	      </div>
	    </div>
		<div class="form_field_help">
	
	      <?php if ($help = $field->renderHelp()): ?>
	        <div class="help"><?php echo __($help, array(), 'messages') ?></div>
	      <?php endif; ?>

	    </div>

	  </div>
	<?php endif; ?>
    <?php endforeach; ?>
	</form>
	</div>
  
</div>

