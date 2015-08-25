<?php use_helper('I18N') ?>
<?php use_helper('Date') ?>
<?php use_helper('Javascript') ?>


<?php include(sfConfig::get('sf_app_template_dir').'/error.php'); ?>
<?php include(sfConfig::get('sf_app_template_dir').'/message.php'); ?>

<?php echo javascript_tag('

	mesaId = '.$model->getId().';
	estadoMesa = "'.$model->getEstado()->getNombre().'";
	
	Restaurant = new Object();
	Restaurant.mesa = new Object();
	Restaurant.mesa.id = mesaId;
	Restaurant.mesa.estado = estadoMesa;

	YAHOO.util.Event.onDOMReady(function() {
		var dialog = createSearchDialog(); 	
	
		mozoFinder = new RecordFinder("/' . $sf_context->getModuleName () . '/asyncRecordFinderMozos", 
				function (id, description) {
					onMozoSelected(id, description);
				}
		, dialog);
	
		YAHOO.util.Event.addListener("buscarMozo", "click", buscarMozo, null, true);
		crearBotones();
		setearBotones(); 
	});
	
	function buscarMozo() {
		mozoFinder.search();
	}

	function onMozoSelected(id, description) {
		var descripcion = document.getElementById("mozo");
		descripcion.value = description;
		Restaurant.mesa.mozo = id;
		if(Restaurant.mesa.estado == "Abierta") {
			botonModificarMozo.set("disabled", false);
		}
	}
	
	function crearBotones() {
		botonAbrirMesa = new YAHOO.widget.Button({ 
	    	id: "boton_abrir_mesa",  
		    type: "push",  
		    label: "Abrir Mesa",  
	    	container: "mesa_toolbar"
		});
		 
		botonCerrarMesa = new YAHOO.widget.Button({ 
	    	id: "boton_cerrar_mesa",  
		    type: "push",  
		    label: "Cerrar Mesa",  
	    	container: "mesa_toolbar"
		});
		
		botonCargarProducto = new YAHOO.widget.Button({ 
	    	id: "boton_cargar_producto",  
		    type: "push",  
		    label: "Cargar Producto",  
	    	container: "mesa_toolbar"
		});
		
		botonVolver = new YAHOO.widget.Button({ 
	    	id: "boton_volver",  
		    type: "push",  
		    label: "Volver",  
	    	container: "mesa_toolbar"
		});
		
		botonModificarMozo = new YAHOO.widget.Button({ 
	    	id: "boton_modificar_mozo",  
		    type: "push",  
		    label: "Modificar Mozo",  
	    	container: "modificar_mozo_toolbar"
		});
		
		botonAbrirMesa.on("click", abrirMesa);
		botonVolver.on("click", volver);
		botonModificarMozo.on("click", modificarMozo);
		botonModificarMozo.set("disabled", true);
		botonCargarProducto.on("click", cargarProducto);
	}
	
	
	
	function abrirMesa() {
	
		var mesaData = YAHOO.lang.JSON.stringify(Restaurant.mesa);
		var postData = "mesa=" + mesaData;
		
		var callbackAbrirMesa = {
			success: function(o) {
				alert(o.responseText);
				botonCerrarMesa.set("disabled", false);
				botonAbrirMesa.set("disabled", true);
				botonCargarProducto.set("disabled", false);
				
			},
			failure: function(o) {
				alert("Ocurrio un error al abrir la mesa");
			}
		};
		
		var mozoInput = document.getElementById("mozo").value;
		
		if(Restaurant.mesa.mozo == undefined) {
			alert("Debe seleccionar un mozo");
		} else {
			sUrl = "/' . $sf_context->getModuleName () . '/asyncAbrirMesa";
			var request = YAHOO.util.Connect.asyncRequest("POST", sUrl, callbackAbrirMesa, postData);
		}
	}
	
	function setearBotones() {
		if(Restaurant.mesa.estado == "Abierta") {
			botonCerrarMesa.set("disabled", false);
			botonAbrirMesa.set("disabled", true);
			botonCargarProducto.set("disabled", false);
		} else {
			botonCerrarMesa.set("disabled", true);
			botonAbrirMesa.set("disabled", false);
			botonCargarProducto.set("disabled", true);
		}
	}
	
	function volver () {
		location.href = "/administracion_mesas";
	}
	
	function modificarMozo() {
		var mesaData = YAHOO.lang.JSON.stringify(Restaurant.mesa);
		var postData = "mesa=" + mesaData;
		
		var callbackModificarMozo = {
			success: function(o) {
				alert("El mozo asignado es: " + o.responseText);
				
			},
			failure: function(o) {
				alert("Ocurrio un error al cambiar el mozo");
			}
		};
		sUrl = "/' . $sf_context->getModuleName () . '/asyncModificarMozo";
		var request = YAHOO.util.Connect.asyncRequest("POST", sUrl, callbackModificarMozo, postData);
	}
	
	function cargarProducto() {
		window.location = "/administracion_mesas/cargarProducto?idMesa=" + Restaurant.mesa.id;
	}
	
')?>
		
<style>
#mozo{
	float: left;
}
#mesa_toolbar{
	padding-top: 10px;
}
#modificar_mozo_toolbar{
	margin-left:300px;
	margin-top:-26px;
}
#boton_cargar_producto .first-child {
	background: url(/images/add.png) 0% 50% no-repeat;
	padding-left: 5px;
	
}
#boton_abrir_mesa .first-child {
	background: url(/images/add.gif) 0% 50% no-repeat;
	padding-left: 5px;
	
}
#boton_cerrar_mesa .first-child {
	background: url(/images/sign_remove.png) 0% 50% no-repeat;
	padding-left: 5px;
}
#boton_modificar_mozo .first-child {
	background: url(/images/user.png) 0% 50% no-repeat;
	padding-left: 5px;
	
}

#boton_volver .first-child {
	background: url(/images/left.png) 0% 50% no-repeat;
	padding-left: 5px;
	
}

</style>

<div id="item" class="show_item">
<div class="show_item_body">

	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo ('Numero Mesa')?>:</p>
	      </div>
		  <div class="show_item_field_value">
	      	<p><?php echo $model->getNumero() ?></p>
	      </div>
	</div>
	<div class="show_item_field_entry">
	      <div class="show_item_field_label">
	      	<p><?php echo ('Mozo')?>:</p>
	      </div>
		  <div><input id="mozo" class="field" type="text" value="<?php echo $model->getMozo()->getNombreCompleto(); ?>" />
				<div><img id="buscarMozo" class="field_icon_button"
				src="/images/search.bmp" /></div>
			<div id="modificar_mozo_toolbar"></div>	
		</div>
	</div>
	<div id="mesa_toolbar"></div>
	

</div>
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
