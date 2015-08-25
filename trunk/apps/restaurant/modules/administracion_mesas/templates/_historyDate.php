<?php use_helper('I18N') ?>
<?php use_helper('Javascript') ?>

<?php echo javascript_tag('
<!-- Dynamic content -->
 
var myColumnDefs = [ 
	            {key:"updated at", resizeable:false, sortable:true, label: translate("Version")},
	        ]; 
	        
var myFieldDefs = [
                {key:"updated at"},
	        ];
	configureTable("the_table", "items", myColumnDefs, myFieldDefs, true);
') ?>

<div id="main_container">
 
<?php include(sfConfig::get('sf_app_template_dir').'/error.php'); ?>
<?php include(sfConfig::get('sf_app_template_dir').'/message.php'); ?>


<div id="list"> 

	<div id="the_table" class="yui-dt">
		<table id="items">
		  <thead>
		    <tr class="yui-dt-first yui-dt-last">
		      <th><input type="checkbox"></th>
		      <th>Id</th>
		      <th>Nombre de usuario</th>
		      <th>Nombre</th>
		      <th>Apellido</th>
		    </tr>
		  </thead>
		  <tbody>
		    <?php $first = true;
		    	 foreach ($list as $model): ?>
		    <tr>
		      <td>
		       <?php  
		       	    echo link_to_remote(($first)?'Current':format_date($model->getUpdatedAt(),'g',sfContext::getInstance()->getUser()->getCulture()),
		       		array('update' => 'item_data',
		       			  'url' => $sf_context->getModuleName().'/asyncHistoryRecord?id='.$model->getId()));
		       		$first = false ?>		    
		      </td>
		    </tr>
		    <?php endforeach; ?>
		
		  </tbody>
		</table>
	</div>
	<div id="paginator"></div>

</div>

</div>