<?php use_helper('Javascript')?>
<style>

.producto_field_label{
	float:left;
	font-size:13px;
	margin-left:5px;
	margin-top:3px;
}

.label_producto{
	padding-left:10px;
	float: left;
	font-size:14px;
}
#cantidad_producto {
	width: 30px;
}
.contenedor_input {
	padding-top:8px;
margin-left: 2px;
	float: left;
}
#buscarProducto {
	padding-top:8px;
}
#add_service {
	margin-left: 25px;
	font-weight: normal;
	font-size: 10px;
	padding: 0;
	margin-top:-1px;
}
.cargar_productos {
	background: #E0F8F7;
}

.contenedor{
	float:left;
}
.columna {
	margin-left: 30px;
}
#tabla_productos{
	padding-top: 20px;
	padding-left: 10px;
}
#agregar_toolbar {
	padding-top:5px;
}
#total {
	background:none repeat scroll 0 0 #58ACFA;
	float:right;
	font-size:15px;
	margin-right:70px;
	padding-top:3px;
}
#boton_agregar_producto .first-child {
	background: url(/images/plato.png) 0% 50% no-repeat;
	padding-left: 5px;
	
}
#boton_cerrar_mesa .first-child {
	background: url(/images/sign_remove.png) 0% 50% no-repeat;
	padding-left: 5px;
}
#boton_volver_lista .first-child {
	background: url(/images/left.png) 0% 50% no-repeat;
	padding-left: 5px;
	
}
#cerrar_toolbar {
	padding-left:10px;
	padding-top:15px;	
}
</style>


<?php echo javascript_tag('

	numeroPedido = '.$numeroPedido.';
	Pedido = new Object();
	Pedido.numeroPedido = numeroPedido;
	Pedido.total = 0;
	
	Pedido.Detalle = new Object();
	Pedido.Detalle.Producto = new Object();
	Pedido.Detalle.Producto.id = "";
	Pedido.Detalle.Producto.cant = 1;


	function cambiaCantidad(editor) {
		if (editor.oldData != editor.newData) {
			var rows = myDataTable.getSelectedRows();
			var rec = myDataTable.getRecord(rows[0]);
			if (editor.newData <= 0) {
				rec.setData("cantidad", editor.oldData);
				
				alert("Cantidad inválida!");
			} else {
				var idProducto = rec.getData("id_producto");
				cambiarCantidad(idProducto, editor.newData);
			} 
			
		}
    }
        
    
    var validadorCantidad = function(value) {
		value = YAHOO.widget.DataTable.validateNumber(value);
		if (value <= 0) {
			value = 1;
		}
		return value;    
    }
	
	function cambiarCantidad(idProducto, nuevaCantidad) {
		
		var callbackCantidad = {
				success: function(o) {
					var pedido = YAHOO.lang.JSON.parse(o.responseText);
					limpiarTabla();
					completarPedido(pedido);
					setearTotal();
				},
				failure: function(o) {
					alert("Ocurrio un error al cambiar la cantidad");
				}
			};
		sUrl = "/' . $sf_context->getModuleName () . '/asyncCambiarCantidadDetalle?idProducto=" + idProducto + "&cantidad=" + nuevaCantidad + "&numeroPedido=" + numeroPedido;
		var request = YAHOO.util.Connect.asyncRequest("GET", sUrl, callbackCantidad, null);
	}
	
	
	function crearTablaProductos() {
	
		var editorCantidad = new YAHOO.widget.TextboxCellEditor({validator:validadorCantidad});
	
	    
	    editorCantidad.subscribe("saveEvent", cambiaCantidad);	
	
		var columnDefs = [
                           {key:"codigo", label: "Codigo", resizeable: false, sortable: false, width:100},
                           {key:"nombre", label: "Nombre", resizeable: false, sortable: false, width:150},
                           {key:"descripcion", label: "Descripcion", resizeable: false, sortable: false, width:300},
                           {key:"precio", label: "Precio", resizeable: false, sortable: false, width:100},
                           {key:"cantidad", label: "Cantidad", resizeable: false, sortable: false, width:100, editor: editorCantidad},
                           {key:"total", label: "Total", resizeable: false, sortable: false, width:100},
                           
                       ];

       var fieldDefs = [
       					{ key: "id_producto" },
       					{ key: "codigo" },
                       { key: "nombre" },
                       { key: "descripcion" },
                       { key: "precio"},
                       { key: "cantidad"},
                       { key: "total"},
                       ];

       myDataSource = new YAHOO.util.LocalDataSource([]);
       myDataSource.responseSchema = fieldDefs;

       myDataTable = new YAHOO.widget.ScrollingDataTable("tabla_productos", columnDefs, myDataSource, {height:"15em"});
       myDataTable.subscribe("rowMouseoverEvent", myDataTable.onEventHighlightRow);
       myDataTable.subscribe("rowMouseoutEvent", myDataTable.onEventUnhighlightRow);
       myDataTable.subscribe("rowClickEvent", myDataTable.onEventSelectRow);
       myDataTable.subscribe("cellClickEvent", myDataTable.onEventShowCellEditor);
       myDataTable.subscribe("editorBlurEvent", myDataTable.onEventSaveCellEditor);
       
       var productoMenu = new YAHOO.widget.ContextMenu("producto_context_menu",{trigger:  myDataTable.getTbodyEl()}); 
		   productoMenu.addItem("Eliminar");
		   productoMenu.render("tabla_productos");
		   productoMenu.clickEvent.subscribe(onProductoMenuClick, myDataTable);
       
       completarTablaPedido();
	}
	
	function onProductoMenuClick(p_sType, p_aArgs, p_productoDataTable) { 
	     var task = p_aArgs[1]; 
	     if(task) { 
	         var elRow = this.contextEventTarget; 
	         elRow = p_productoDataTable.getTrEl(elRow); 

	         if(elRow) { 
	             switch(task.index) { 
	                 case 0:     // Incidente
	                     var oRecord = p_productoDataTable.getRecord(elRow);
	             		 var idProducto = oRecord.getData("id_producto");
	             		 var nombre = oRecord.getData("nombre");
						 if(confirm("Está seguro que desea eliminar " + nombre + "?")) {
						 	 p_productoDataTable.deleteRow(elRow);
						 	eliminarDetalle(idProducto);
						 	
						 }
	                     break;
	             } 
	         } 
	     }
	}	

	function botones() {
		botonAgregarProducto = new YAHOO.widget.Button({ 
	    	id: "boton_agregar_producto",  
		    type: "push",  
		    label: "Agregar",  
	    	container: "agregar_toolbar"
		});
		
		botonCerrarMesa = new YAHOO.widget.Button({ 
	    	id: "boton_cerrar_mesa",  
		    type: "push",  
		    label: "Cerrar Mesa",  
	    	container: "cerrar_toolbar"
		});
		
		botonVolverLista = new YAHOO.widget.Button({ 
	    	id: "boton_volver_lista",  
		    type: "push",  
		    label: "Volver",  
	    	container: "cerrar_toolbar"
		});
	
		botonVolverLista.on("click", volverLista);
		botonAgregarProducto.on("click", agregarDetalle);
	}
	
	function volverLista() {
		location.href = "/administracion_mesas";
	}
		
	function onProductoSelected(id, description) {
		Pedido.Detalle.Producto.id = id;
		var descripcion = document.getElementById("producto");
		descripcion.value = description;
		setFocus("cantidad_producto");
	}
	
	function buscarProducto() {
		productoFinder.search();
	}
	
	function setFocus(inputName) {
		var input = document.getElementById(inputName);
		input.focus();
	}
	
	function agregarDetalle(){
		if(Pedido.Detalle.Producto.id == "") {
			alert("Debe seleccionar un producto");
		} else {
			var cantidad = document.getElementById("cantidad_producto").value;
			if(cantidad != "") {
				Pedido.Detalle.Producto.cant = cantidad;
			} else {
				Pedido.Detalle.Producto.cant = 1;
			}
			
			var callbackDetalle = {
				success: function(o) {
					var pedido = YAHOO.lang.JSON.parse(o.responseText);
					limpiarTabla();
					completarPedido(pedido);
					limpiarCampos();
					setearTotal();
				},
				failure: function(o) {
					alert("Ocurrio un error al cargar el producto");
				}
			};
		sUrl = "/' . $sf_context->getModuleName () . '/asyncCargarDetalle?idProducto=" + Pedido.Detalle.Producto.id + "&cantidad=" + Pedido.Detalle.Producto.cant + "&numeroPedido=" + numeroPedido;
		var request = YAHOO.util.Connect.asyncRequest("GET", sUrl, callbackDetalle, null);
			
			
		}
	}
	
	function agregarDetalleTabla(detalle) {
		var totalDetalle = detalle.producto.precio * detalle.cantidad;
		Pedido.total = Pedido.total + totalDetalle;
		var linea = {
       				id_producto: detalle.producto.id,
       				codigo: detalle.producto.codigo,
       				nombre: detalle.producto.nombre,
       				descripcion: detalle.producto.descripcion,
       				precio: "$" + detalle.producto.precio,
       				cantidad: detalle.cantidad,
       				total: "$" + totalDetalle
       			}
       			myDataTable.addRow(linea);
       			
	}
	
	function limpiarCampos() {
		var cantidad = document.getElementById("cantidad_producto");
		var producto = document.getElementById("producto");
		producto.value = "";
		cantidad.value = "";
		Pedido.Detalle.Producto.id = "";
	}
	
		
	function completarTablaPedido() {
		
		var callbackPedido = {
				success: function(o) {
					var pedido = YAHOO.lang.JSON.parse(o.responseText);
					completarPedido(pedido);
					setearTotal();
				},
				failure: function(o) {
					alert("Ocurrio un error al cargar el pedido");
				}
			};
	
		sUrl = "/' . $sf_context->getModuleName () . '/asyncPedido?numeroPedido=" + numeroPedido;
		var request = YAHOO.util.Connect.asyncRequest("GET", sUrl, callbackPedido, null);
	}
	
	function completarPedido(pedido) {
			var i;
       		for(i = 0; i < pedido.detalle.length; i++) {
       			agregarDetalleTabla(pedido.detalle[i]);
       		}
	}
	
	function setearTotal() {
		var divTotal = document.getElementById("total");
		divTotal.innerHTML = "Total: $";
		divTotal.innerHTML += Pedido.total;
	}
	
	function limpiarTabla() {
		Pedido.total = 0;
		myDataTable.deleteRows(0, myDataTable.getRecordSet().getLength());
	}
	
	function setearListeners() {
		
		YAHOO.util.Event.addListener("buscarProducto", "click", buscarProducto, null, true);
			
			YAHOO.util.Event.addListener("producto", "keyup", function(e) {
				if (e.keyCode == 13) {
					productoFinder.quickSearch(this.value);
				}
			});
			
			YAHOO.util.Event.addListener("cantidad_producto", "keyup", function(e) {
				
				if (!IsNumeric(this.value)) {
					this.value = "";
				}
				if (e.keyCode == 13) {
					agregarDetalle();
					setFocus("producto");
				}
			});
	
	}
	
	function IsNumeric(input)
	{
	   return (input - 0) == input && input.length > 0;
	}
	
	function eliminarDetalle(idProducto) {
		
		var callbackEliminarDetalle = {
				success: function(o) {
					var total = YAHOO.lang.JSON.parse(o.responseText);
					Pedido.total = total;
					setearTotal();
				},
				failure: function(o) {
					alert("Ocurrio un error al cargar el pedido");
				}
			};
	
		
		sUrl = "/' . $sf_context->getModuleName () . '/asyncEliminarDetalle?numeroPedido=" + numeroPedido + "&idProducto=" + idProducto;
		var request = YAHOO.util.Connect.asyncRequest("GET", sUrl, callbackEliminarDetalle, null);
	
	}
	
	YAHOO.util.Event.onDOMReady(function() {
			
		var dialogProductos = createSearchDialog(); 	
	
			productoFinder = new RecordFinder("/' . $sf_context->getModuleName () . '/asyncRecordFinderProductos", 
				function (id, description) {
					onProductoSelected(id, description);
				}
			, dialogProductos);
	
			
			setearListeners();
			crearTablaProductos();
			botones();
			setFocus("producto");
	
	});

')?>


<div id="pagetitle"><h1>Cargar Productos Mesa <?php echo $numeroMesa?></h1></div>

<div id="item" class="show_item">
<div class="show_item_body">

	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo ('Numero Mesa')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo $numeroMesa ?></p>
	      </div>
	</div>
	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo ('Mozo')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo $nombreMozo ?></p>
	      </div>
	</div>
	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo ('Fecha Abierta')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo $fechaAbierta ?></p>
	      </div>
	</div>
	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo ('Numero Pedido')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo $numeroPedido ?></p>
	      </div>
	</div>
</div>
</div>

<div class="cargar_productos">
	
		 <div>
			 <div> 
			      <div class="label_producto">
			      	<p><?php echo "Producto"?>:</p>
			      </div>
			      <div class="contenedor">
				      <div class="contenedor_input">
		           	  	<input id="producto" class="field" type="text"/>
		           	  </div>
					  <div class="contenedor">
	  				  	<img id="buscarProducto" class="field_icon_button" src="/images/search.bmp"/>
			      	  </div>
		      	  </div>
			  
			      <div class="label_producto columna">
			      	<p><?php echo "Cantidad"?>:</p>
			      </div>
			      <div class="contenedor_input">
	           	  	<input id="cantidad_producto" class="field" type="text"/>
	           	  </div>
	           	  <div id="agregar_toolbar"></div>
	           	  <!--  <button id="agregar_producto"><?php echo "Agregar"?></button>-->
			 </div>

		 </div>
	
		<div id="tabla_productos"></div>
</div>
<div class="cargar_productos">
	<div id="total"></div>
	<div id="cerrar_toolbar"></div>
	<div style="height: 15px"></div>
</div>

<div id="record_finder">
<div id="recordFinder_container">
<div id="recordFinder_title"></div>


<div id="recordFinder_search">
<form id="formSearch">
<table id="finderSearchTable"></table>
</form>
</div>

<div id="recordResults_container"></div>

</div>
</div>