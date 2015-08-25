<?php use_helper('Javascript') ?>
<?php use_helper('I18N') ?>
<?php echo javascript_tag('
	
	var linkNumeroFormatter = function (cell, rec, col, data) {
			var id = rec.getData("id_mesa");
			var numero = rec.getData("numero");
	        cell.innerHTML = "<a href=\"administracion_mesas/show?id="+ id + "\"> " + numero + "</a>";
	    };
	 
	var linkPedidoFormatter = function (cell, rec, col, data) {
			var id = rec.getData("id_mesa");
			var pedido = rec.getData("pedido");
	        cell.innerHTML = "<a href=\"administracion_mesas/cargarProducto?idMesa="+ id + "\"> " + pedido + "</a>";
	    };


	var columnDefs = [
                           {key:"numero", label: "Numero", resizeable: false, sortable: false, width:50, formatter:linkNumeroFormatter},
                           {key:"fechaAbierta", label: "Fecha/Hora Abierta", resizeable: false, sortable: false, width:180},
                           {key:"estado", label: "Estado", resizeable: false, sortable: false, width:100},
                           {key:"pedido", label: "Pedido", resizeable: false, sortable: false, width:150, formatter:linkPedidoFormatter},
                           {key:"total", label: "Total", resizeable: false, sortable: false, width:80},
                           {key:"mozo", label: "Mozo", resizeable: false, sortable: false, width:150},
                       ];

       var fieldDefs = [
       					{ key: "id_mesa" },
                       { key: "numero" },
                       { key: "fechaAbierta" },
                       { key: "mozo"},
                       { key: "estado"},
                       { key: "pedido"},
                       ];

       YAHOO.util.Event.onContentReady("tabla_mesas", function() {
       myDataSource = new YAHOO.util.LocalDataSource([]);
       myDataSource.responseSchema = fieldDefs;

       myDataTable = new YAHOO.widget.ScrollingDataTable("tabla_mesas", columnDefs, myDataSource, {height:"15em"});
       myDataTable.subscribe("rowMouseoverEvent", myDataTable.onEventHighlightRow);
       myDataTable.subscribe("rowMouseoutEvent", myDataTable.onEventUnhighlightRow);
       myDataTable.subscribe("rowClickEvent", myDataTable.onEventSelectRow);
       myDataTable.subscribe("cellDblclickEvent", myDataTable.onEventShowCellEditor);
       myDataTable.subscribe("editorBlurEvent", myDataTable.onEventSaveCellEditor);

       completarTablaMesas();
       
       });
       
       function completarTablaMesas() {
       		var callbackMesas = { 
		  			 success : function (o) {
        				var mesas = YAHOO.lang.JSON.parse(o.responseText);
			        	completar(mesas);
			    },
		  			failure: function(o) {
		  				alert(o.responseText);
		  		},
			};
			var transaction = YAHOO.util.Connect.asyncRequest("GET", "/administracion_mesas/asyncMesas", callbackMesas, null);
       
       
       }
       
       function completar(mesas) {
       		var i;
       		for(i = 0; i < mesas.length; i++) {
       			var fecha = "";
       			if(mesas[i].fechaAbierta != "") {
       				fecha = parseTimestamp(mesas[i].fechaAbierta).format("shortDateTime");
       			}
       			var linea = {
       				id_mesa: mesas[i].id,
       				numero: mesas[i].numero,
       				fechaAbierta: fecha,
       				mozo: mesas[i].mozo,
       				estado: mesas[i].estado,
       				pedido: mesas[i].pedido.numero,
       				total: "$" + mesas[i].pedido.total
       			}
       			myDataTable.addRow(linea);
       		}
       }
 
')

?>

<style>
#tabla_mesas {
	padding-top: 10px;	
	padding-left: 7px;
}
</style>

<div id="main_container">
<?php include(sfConfig::get('sf_app_template_dir').'/_listToolbar.php'); ?>
<div>
	<div id="tabla_mesas"></div>
</div> 
</div>