<?php use_helper('Javascript') ?>
<?php use_helper('I18N') ?>
<?php use_helper('Date') ?>
<?php use_helper('DateUtil') ?>
<?php echo javascript_tag('
	createPushButton("edit");
	createPushButton("delete");
	createPushButton("back");
') ?>

<div id="item" class="show_item">
<div class="show_item_body">

	<!-- agregar los campos a visualizar 
	-->
	
	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo __('Modification Date')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo format_date($model->getUpdatedAt()) ?></p>
	      </div>
	</div>
	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo __('Modified By')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo ($model->getUpdatedBy()!=' ')?$model->getUpdatedBy():'System' ?></p>
	      </div>
	</div>

</div>
</div>