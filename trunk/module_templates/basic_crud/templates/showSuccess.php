<?php use_helper('I18N') ?>
<?php use_helper('Date') ?>
<?php use_helper('DateUtil') ?>


<?php include_partial('showToolbar', array('model' => $model, 'modelName' => $entityName)) ?>
<?php include(sfConfig::get('sf_app_template_dir').'/error.php'); ?>
<?php include(sfConfig::get('sf_app_template_dir').'/message.php'); ?>


<div id="item" class="show_item">
<div class="show_item_body">

	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo __('ID')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo $model->getid() ?></p>
	      </div>
	</div>
	

</div>
</div>
