<?php use_helper('I18N'); ?>
<?php if ($sf_user->getFlash('error')): ?>
<div id="error_message">
	<p><?php echo __($sf_user->getFlash('error')) . __($sf_user->getFlash('error_detail')) ?></p>
</div>
<?php endif; ?>
