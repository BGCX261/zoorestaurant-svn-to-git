<?php use_helper('Javascript') ?>
<?php use_helper('I18N') ?>
<?php echo javascript_tag('
	
	var linkNombreFormatter = function (cell, rec, col, data) {
			var id = rec.getData("id_mozo");
			var nombre = rec.getData("nombre");
	        cell.innerHTML = "<a href=\"mozos/show?id="+ id + "\"> " + nombre + "</a>";
	    };
	 
	
	var mozosColumnDefs = [
                           {key:"nombre", label: "Nombre", resizeable: false, sortable: false, width:150, formatter:linkNombreFormatter},
                           {key:"apellido", label: "Apellido", resizeable: false, sortable: false, width:150},
                       ];

       var mozosFieldDefs = [
       				   { key: "id_mozo" },
                       { key: "nombre" },
                       { key: "apellido" },
                       ];

       YAHOO.util.Event.onContentReady("tabla_mozos", function() {
       mozosSource = new YAHOO.util.LocalDataSource([]);
       mozosSource.responseSchema = mozosFieldDefs;

       mozoTable = new YAHOO.widget.ScrollingDataTable("tabla_mozos", mozosColumnDefs, mozosSource, {height:"15em"});
       mozoTable.subscribe("rowMouseoverEvent", mozoTable.onEventHighlightRow);
       mozoTable.subscribe("rowMouseoutEvent", mozoTable.onEventUnhighlightRow);
       mozoTable.subscribe("rowClickEvent", mozoTable.onEventSelectRow);
       mozoTable.subscribe("cellDblclickEvent", mozoTable.onEventShowCellEditor);
       mozoTable.subscribe("editorBlurEvent", mozoTable.onEventSaveCellEditor);

       completarTablaMozos();
       
       });
       
       function completarTablaMozos() {
       		var callbackMozos = { 
		  			 success : function (o) {
        				var mozos = YAHOO.lang.JSON.parse(o.responseText);
			        	completar(mozos);
			    },
		  			failure: function(o) {
		  				alert(o.responseText);
		  		},
			};
			var transaction = YAHOO.util.Connect.asyncRequest("GET", "/mozos/asyncMozos", callbackMozos, null);
       
       
       }
       
       function completar(mozos) {
       		var i;
       		for(i = 0; i < mozos.length; i++) {
       			
       			var linea = {
       				id_mozo: mozos[i].id,
       				nombre: mozos[i].nombre,
       				apellido: mozos[i].apellido
       			}
       			mozoTable.addRow(linea);
       		}
       }
 
')

?>

<style>
#tabla_mozos {
	padding-top: 10px;	
	padding-left: 7px;
}
</style>

<div id="main_container">
<?php include(sfConfig::get('sf_app_template_dir').'/_listToolbar.php'); ?>
<div>
	<div id="tabla_mozos"></div>
</div> 
</div>