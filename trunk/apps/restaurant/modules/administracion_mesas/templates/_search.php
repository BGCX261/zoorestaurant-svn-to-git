<div id="search_container">
	
	<div id="search_title" class="frame_title">
		<p> <?php echo __('Search') ?> </p>
	</div>
	
	
	<div id="search_form">
		<form action="<?php echo url_for($sf_context->getModuleName().'/search/') ?>" method="POST">
			<table>
		    <?php echo $form ?>
		   <tr>
		   <td colspan="2">&nbsp; </td>
		   </tr>
		    <tr>
		      <td colspan="2" >
		        <input type="submit" value="<?php echo __('Find') ?>"/>
		      </td>
		    </tr>
		  
		  
		  </table>
		
		</form>

	<div id ="search_rester">

		<?php if (isset($filter)): ?>
			<a href="<?php echo url_for($sf_context->getModuleName().'/removeFilter/') ?>">
				<?php echo __('Reset filter')?>
			</a>
		<?php endif; ?>
	</div>


	</div>
	
	
</div>