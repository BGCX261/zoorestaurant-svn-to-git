<?php if ($sf_user->getFlash('message')): ?>
<div id="notice_message">
	<p><?php echo __($sf_user->getFlash('message')) ?></p>
</div>
<?php endif; ?>
