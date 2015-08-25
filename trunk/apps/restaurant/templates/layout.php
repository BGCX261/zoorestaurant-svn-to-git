<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
   
    
	<?php echo stylesheet_tag('/yui/build/reset-fonts-grids/reset-fonts-grids.css')?>
	<?php echo stylesheet_tag('/yui/build/reset-fonts-grids/reset-fonts-grids.css')?>
	<?php echo stylesheet_tag('/yui/build/menu/assets/skins/sam/menu.css')?>
	<?php echo stylesheet_tag('/yui/build/paginator/assets/skins/sam/paginator.css')?>
	<?php echo stylesheet_tag('/yui/build/datatable/assets/skins/sam/datatable.css')?>
	<?php echo stylesheet_tag('/yui/build/datatable/assets/skins/sam/datatable-skin.css')?>
	<?php echo stylesheet_tag('/yui/build/button/assets/skins/sam/button.css')?>
	<?php echo stylesheet_tag('/yui/build/button/assets/skins/sam/button-skin.css')?>
	<?php echo stylesheet_tag('/yui/build/container/assets/skins/sam/container.css')?>
	<?php echo stylesheet_tag('/yui/build/tabview/assets/skins/sam/tabview.css')?>
	<?php echo stylesheet_tag('/yui/build/treeview/assets/skins/sam/treeview.css')?>
	<?php echo stylesheet_tag('/yui/build/calendar/assets/skins/sam/calendar.css')?>
	<?php echo stylesheet_tag('/css/yui-gb.css')?>
	<?php echo stylesheet_tag('/css/main.css')?>
	<?php echo stylesheet_tag('/css/component.css')?>
	
	<?php echo javascript_include_tag('/yui/build/yahoo/yahoo-min.js')?>
	<?php echo javascript_include_tag('/yui/build/yuiloader-dom-event/yuiloader-dom-event.js')?>
	<?php echo javascript_include_tag('/yui/build/yahoo-dom-event/yahoo-dom-event.js')?>
	<?php echo javascript_include_tag('/yui/build/container/container_core-min.js')?>
	<?php echo javascript_include_tag('/yui/build/container/container_core.js')?>
	<?php echo javascript_include_tag('/yui/build/container/container-min.js')?>
	<?php echo javascript_include_tag('/yui/build/menu/menu-min.js')?>
	<?php echo javascript_include_tag('/yui/build/menu/menu.js')?>
	<?php echo javascript_include_tag('/yui/build/element/element-min.js')?>
	<?php echo javascript_include_tag('/yui/build/button/button-min.js')?>
	<?php echo javascript_include_tag('/yui/build/animation/animation-min.js')?>
	<?php echo javascript_include_tag('/yui/build/animation/animation.js')?>
	<?php echo javascript_include_tag('/yui/build/dragdrop/dragdrop-min.js')?>
	<?php echo javascript_include_tag('/yui/build/paginator/paginator-min.js')?>
	<?php echo javascript_include_tag('/yui/build/datasource/datasource-min.js')?>
	<?php echo javascript_include_tag('/yui/build/datatable/datatable.js')?>
	<?php echo javascript_include_tag('/yui/build/connection/connection-min.js')?>
	<?php echo javascript_include_tag('/yui/build/menu/menu.js')?>
	<?php echo javascript_include_tag('/yui/build/tabview/tabview-min.js')?>
	<?php echo javascript_include_tag('/yui/build/treeview/treeview-min.js')?>
	<?php echo javascript_include_tag('/yui/build/json/json-min.js')?>
	<?php echo javascript_include_tag('/yui/build/calendar/calendar-min.js')?>
	<?php echo javascript_include_tag('/js/crud.js')?>
	<?php echo javascript_include_tag('/js/Datejs-all/build/date-es-AR.js'); ?>

	 <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php echo ('Restaurant Zoo Lujan');  ?></title>
  </head>
  <body class=" yui-skin-sam">
<div id="doc3" class="yui-t2">
<div id="global" align="center">
<div id="main_container" align="left">

<div id="bd" >
	<div>
		
	</div>

	<div id="pagetitle">
		<?php if ($sf_context->has("page_title")): ?>
			<?php echo "<h1>";
				if ($sf_context->has("subsystem_title") && $sf_context->get("subsystem_title")) {
					echo __($sf_context->get("subsystem_title")) . ' > ' . 
						__($sf_context->get("page_title"));
				} else {
					echo __($sf_context->get("page_title"));					
				}
						 
				echo "</h1>" ?>
		<?php endif; ?>	
	</div>

	<table id="layout_table" cellspacing="0" cellpadding="0" border="0">
	<tbody>
	<tr>
	<td id="main_cell">
		<div id="container-main">
			<div>
					<?php echo $sf_content ?>
			</div>
		</div>
	</td>
	</tr>
	</tbody>
	</table>

	</div><!--closes bd-->

		<div id="ft">
	</div>
</div>
</div>
</div>
</body>
</html>