<?php use_helper('I18N') ?>
<?php use_helper('Javascript') ?>

<?php echo javascript_tag('
<!-- Dynamic content -->
 
	 var myColumnDefs = [ 
		            {key:"checked", label:"", width: 10, formatter:"checkbox"}, 
		            {key:"id", width: 20, resizeable:false, sortable:true, formatter:"number"}, 
		            {key:"name", resizeable:false, sortable:true},
		            {key:"abbreviation", resizeable:false, sortable:true},
		            {key:"actions", width: 100, resizeable:false, sortable:false}
	
		        ]; 
		        
	var myFieldDefs = [
	            	{key:"checked", parser:YAHOO.util.DataSource.parseBoolean},
	            	{key:"id", parser:"number"}, 
					{key:"name"},
	                {key:"abbreviation"},
	                {key:"actions"}
					                     
		        ];
	
	configureTable("the_table", "items", myColumnDefs, myFieldDefs, true);
') ?>

<div id="main_container">
 
<?php include(sfConfig::get('sf_app_template_dir').'/listToolbar.php'); ?>
<?php include(sfConfig::get('sf_app_template_dir').'/error.php'); ?>
<?php include(sfConfig::get('sf_app_template_dir').'/message.php'); ?>


<div id="list"> 

	<div id="the_table" class="yui-dt">
		<table id="items">
		  <thead>
		    <tr class="yui-dt-first yui-dt-last">
		      <th><input type="checkbox"></th>
		      <th><?php echo __('Id');?></th>
		      <th><?php echo __('Nombre');?></th>
		      <th><?php echo __('Abreviatura');?></th>
		    </tr>
		  </thead>
		  <tbody>
		    <?php foreach ($list as $item): ?>
		    <tr>
		      <td></td>
		      <td><?php echo $item->getId() ?></td>
		      <td>
				<a href="<?php echo url_for($sf_context->getModuleName().'/show?id='.$item->getId()) ?>">      
		    	<?php echo $item->getName() ?>
				</a>
		    </td>
			<td><?php echo $item->getAbbreviation() ?></td>
		    <td>
				<p>
		    		<a id="edit_item_action" href="<?php echo url_for($sf_context->getModuleName().'/edit?id='.$item->getId()) ?>">
		    			<?php echo __('Edit') ?>
		    		</a>
		    		&nbsp;
		    		<a id="delete_item_action" 
		    			href="<?php echo url_for($sf_context->getModuleName().'/delete?id='.$item->getId()) ?>"
		    			onclick='return confirmSingleDelete("<?php echo $entityName ?>")'
		    			>
		    			<?php echo __('Delete') ?>
		    		</a>
		    	</p>
		    </td>
		    </tr>
		    <?php endforeach; ?>
		
		  </tbody>
		</table>
	</div>

	<div id="paginator"></div>

</div>

<?php if ($showSearch): ?>
	<?php if ($hasFilter): ?>
		<?php include_partial('search', array('filter' => $filter, 'form' => $search_form)) ?>
	<?php else: ?>
		<?php include_partial('search', array('form' => $search_form)) ?>
	<?php endif;?>
<?php endif; ?> 
</div>