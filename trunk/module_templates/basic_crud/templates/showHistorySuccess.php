<?php use_helper('I18N') ?>
<?php use_helper('Date') ?>
<?php use_helper('DateUtil') ?>

<style>

#item_data {
	float: left;
	width: 60%;
	overflow: hidden;
}

#versions {
	width: 30%;
	float: right;
	overflow: hidden;
}
</style>

<?php include_partial('showHistoryToolbar', array('model' => $model, 'modelName' => $entityName)) ?>
<div id="historic_container">
<div id="item_data">
</div>
<div id="versions">
<?php include_partial('historyDate', array('model' => $model, 'list' => $previousList, 'modelName' => $entityName)) ?>
</div>
</div>
<?php include(sfConfig::get('sf_app_template_dir').'/error.php'); ?>
<?php include(sfConfig::get('sf_app_template_dir').'/message.php'); ?>