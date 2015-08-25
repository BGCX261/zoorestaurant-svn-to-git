YAHOO.namespace("example.container");
function init() {
	// Define various event handlers for Dialog
	var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
		var response = o.responseText;
		response = response.split("<!")[0];
		document.getElementById("resp").innerHTML = response;
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};

	// Instantiate the Dialog
	YAHOO.example.container.dialog1 = new YAHOO.widget.Dialog("recordFinder_container", 
							{ fixedcenter : true,
							  visible : false,
							  constraintoviewport : true,
							  buttons : [ { text:"Close", handler:handleCancel } ]
							});

	// Wire up the success and failure handlers
	YAHOO.example.container.dialog1.callback = { 
							success: handleSuccess,
					        failure: handleFailure 
	};
	
	// Render the Dialog
	YAHOO.example.container.dialog1.render();
	YAHOO.util.Event.addListener("hideDialog", "click", YAHOO.example.container.dialog1.hide, YAHOO.example.container.dialog1, true);
}
YAHOO.util.Event.onDOMReady(init);

/**
 * Executes an asynchronous transaction, and brings a XML string
 * @param  string sUrl                     contains a url action
 * @param  string selectionCallback        contains a callback function
 * @return object o                        reference to the XML string
 */
function searchRegisters(sUrl, selectionCallback) {
	arraySearchField = new Array();
	description = new Array();
	var postData = "";
	callback = { 
		success: function(o) {
			generateSearchDialog(o, selectionCallback);
		},
		failure: function(o) {
	  		error(o);
	  	}
	};
	YAHOO.util.Connect.asyncRequest("GET", sUrl, callback, postData);
}

/**
 * Generates and displays an dialogue with a list of records
 * @param  object o               reference to the XML string
 * @param  string callback        contains a callback function
 */
function generateSearchDialog(o, callback) {
	selectionCallback = callback;
	
	//Response tiene el xml
	var response = o.responseText;
	
	//Parsea el xml
	var parser = new DOMParser();
  	xmlDoc = parser.parseFromString(response,"text/xml");

  	//Busco el t�tulo del di�logo
  	var titleNode = xmlDoc.getElementsByTagName("title")[0];
	var rowField = "", 
		rowResult, 
		oFinderResults = "", 
		nodFinderSearchTable = document.getElementById("finderSearchTable");
	
	//Tag HTML donde van los resultados
	oFinderResults = document.getElementById("recordResults_container");
	
	var i, 
		oRecordDescr; 

	//Busco los campos de t�tulo de los registros
	var oRecordTitle = xmlDoc.getElementsByTagName("record_title"); 

	//Busco los campos de descripci�n de los registros
	var showResults= "", showDescr, showTitle = oRecordTitle[0].childNodes;
	var oRecordDescr = xmlDoc.getElementsByTagName("record_description");

	//Busco los campos de b�squeda
	var oSearchField = xmlDoc.getElementsByTagName("search_field");
	var showDescr = oRecordDescr[0].childNodes;
	
	oFinderResults.innerHTML = "";
	arrayTitle = new Array();
    arrayDescr = new Array();
	
    nodFinderSearchTable.innerHTML ="";
	var divRecordFinderSearch = document.getElementById("recordFinder_search");
	divRecordFinderSearch.innerHTML ="";
	
	for (var i = 0; i < oSearchField.length; i++) {
		var newText1 = document.createTextNode(oSearchField[i].getAttribute("label"));
		var tr1 = document.createElement("tr");
		var td1 = document.createElement("td");
		tr1.setAttribute("class","search_field");
		td1.setAttribute("class","search_field");
		td1.appendChild(newText1);
		var td2 = document.createElement("td");
		if (oSearchField[i].getAttribute("type")=="free") {
			var newInput1 = document.createElement("input");
			newInput1.setAttribute("type","text");
			newInput1.setAttribute("id","input"+(i+1));
			newInput1.setAttribute("class","elementWidth");
			td2.appendChild(newInput1);
			YAHOO.util.Event.addListener("input"+(i+1), "keyup", applyFilter);
			
		} else if (oSearchField[i].getAttribute("type")=="option_list") {
			var newSelect1 = document.createElement("select");
			newSelect1.setAttribute("class", "elementWidth");
			var option = oSearchField[i].childNodes[0].childNodes;
			newSelect1.options[0] = new Option("Elija", "", false, false);
			for (j=0; j<option.length; j++) {
				newSelect1.options[j+1] = 
					new Option(option[j].childNodes[1].childNodes[0].nodeValue,
						option[j].childNodes[0].childNodes[0].nodeValue,
						false,
						false
					);
			}
			td2.appendChild(newSelect1);
			newSelect1.setAttribute("id", "select"+(i+1));
			YAHOO.util.Event.addListener("select"+(i+1), "change", applyFilter);
		}
		tr1.appendChild(td1);
		tr1.appendChild(td2);
		nodFinderSearchTable.appendChild(tr1);
		
		if(!in_array_tradicional(arrayDescr, oSearchField[i].getAttribute("name")))
			arrayDescr.push(oSearchField[i].getAttribute("name"));
		arraySearchField.push(oSearchField[i].getAttribute("name"));
	}
	
	var form1 = document.createElement("form");
	form1.setAttribute("id", "formSearch");
	form1.appendChild(nodFinderSearchTable);
	divRecordFinderSearch.appendChild(form1);
	
	for (i = 0; i < showDescr.length; i++) {
		arrayDescr.push(showDescr[i].getAttribute("name"));
		if(!in_array_tradicional(arrayDescr, oSearchField[i].getAttribute("name")))
			arrayDescr.push(oSearchField[i].getAttribute("name"));
	}
	for (i = 0; i < showTitle.length; i++) {
		arrayTitle.push(showTitle[i].getAttribute("name"));
		if(in_array_tradicional(arrayDescr, arrayTitle[i]))
			removeByElement(arrayDescr, arrayTitle[i]);
	}
	
	var oRecordValues = xmlDoc.getElementsByTagName("record");
	
	for (i = 0; i < oRecordValues.length; i++) {
		var divA = document.createElement("div");
		divA.setAttribute("class", "recordResult_container");
		var recordId = oRecordValues[i].getAttribute("id");
		divA.setAttribute("id", "record" + recordId);
		
		var divB = document.createElement("div");
		divB.setAttribute("class","recordResult_title");
		var table1 = document.createElement("table");
		var tr1 = document.createElement("tr");
		tr1.setAttribute("class", "bold");
		
		//remueve el valor "undefined" en la posici�n
		description[recordId]="";
		
		//Generar el t�tulo de un registro
		for (var j = 0; j < arrayTitle.length; j++) {
			var recordChild = oRecordValues[i].getElementsByTagName(arrayTitle[j]);
			var td1 = document.createElement("td");
			var newText1 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
			td1.appendChild(newText1);
			tr1.appendChild(td1);
			table1.appendChild(tr1);
			//Capturo los campos de titulos de un registro
			description[recordId] += recordChild[0].childNodes[0].nodeValue + " - ";
		}
		
		//asigno un evento el cual llamar� a la funci�n callback
		//pasandole como parametro loss campos de titulos del registro
		divA.addEventListener("click", function(event){
			//this hace referencia a divA
		  	var id = this.id.substring(6);
		  	var descriptionText = description[recordId];
		  	YAHOO.example.container.dialog1.hide();
			selectionCallback(id, description[id].substring(0, description[id].length - 3));
		}, false);
		
		//Genero los campos de descripci�n del registro
		for (var j = 0; j < arrayDescr.length; j++) {
			var recordChild = oRecordValues[i].getElementsByTagName(arrayDescr[j]);
			var tr2 = document.createElement("tr");
			var td1 = document.createElement("td");
			var newText1 = document.createTextNode(ucfirst(recordChild[0].nodeName) + ":");
			td1.appendChild(newText1);
			var td2 = document.createElement("td");
			var newText2 = "";
			if (recordChild[0].childNodes.length > 0) {
				newText2 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
				td2.appendChild(newText2);
			}
			tr2.appendChild(td1);
			tr2.appendChild(td2);
			table1.appendChild(tr2);
		}
		divB.appendChild(table1);
		divA.appendChild(divB);
		oFinderResults.appendChild(divA);
	}
	
	document.getElementById("recordFinder_title").innerHTML = titleNode.childNodes[0].nodeValue;
	YAHOO.util.Dom.setStyle("recordFinder_container", "display", "block");
	YAHOO.example.container.dialog1.show();
}

/**
 * Filters the results based on events at SearchFields
 * @param  object o               reference to the XML string
 * @param  string callback        contains a callback function
 */
function applyFilter() {
   	var oFinderResults = document.getElementById("recordResults_container");
   	oFinderResults.innerHTML = "";
   	var oRecordValues = xmlDoc.getElementsByTagName("record");
   	//Recorre todos los registros obtenidos del xml
	for (var i = 0; i < oRecordValues.length; i++) {
		var displayRecord = new Boolean(true);
		var oformSearch = document.getElementById("formSearch");
		//Recorro los elementos del formulario de busqueda para obtener su contenido 
		for (var j=0; j<(oformSearch.elements.length); j++ ) {
			if ( oformSearch.elements[j].value !="" ){
				var recordChild = oRecordValues[i].getElementsByTagName(arraySearchField[j]);
				var textNode = recordChild[0].childNodes[0].nodeValue;
				var elemValue;
				if ( oformSearch.elements[j].type == "select-one"){
					var x = oformSearch.elements[j];
					elemValue = x.options[x.selectedIndex].text;
				}else{
					elemValue = oformSearch.elements[j].value;
				}
				//El metodo match() compara el texto del nodo con el valor dado por el usuario 
				if ((textNode.toLowerCase()).match(elemValue.toLowerCase()) == null){
					displayRecord = false;
					break;
				}
			}
		}
		//muestro el registro solo si los valores son semejantes
		if (displayRecord) {
			var divA = document.createElement("div");
			divA.setAttribute("class","recordResult_container");
			
			var recordId = oRecordValues[i].getAttribute("id");
			divA.setAttribute("id", "record" + recordId);
			
			
			var divB = document.createElement("div");
			divB.setAttribute("class","recordResult_title");
			var table1 = document.createElement("table");
			var tr1 = document.createElement("tr");
			tr1.setAttribute("class","bold");
			
			//Remueve el valor "undefined"(que viene por defecto) en la posici�n recordId
			description[recordId]="";
			
			//El titulo del registro en bold
			for (var j = 0; j < arrayTitle.length; j++) {
				var recordChild = oRecordValues[i].getElementsByTagName(arrayTitle[j]);
				var td1 = document.createElement("td");
				var newText1 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
				td1.appendChild(newText1);
				tr1.appendChild(td1);
				table1.appendChild(tr1);
				description[recordId] += recordChild[0].childNodes[0].nodeValue + " - ";
			}
			
			divA.addEventListener("click", function(event){
				//this hace referencia a divA
			  	var id = this.id.substring(6);
			  	var descriptionText = description[recordId];
				selectionCallback(id, description[id].substring(0, description[id].length - 3));
			}, false);
			
			//Genero los campos de descripci�n del registro
			for (var j = 0; j < arrayDescr.length; j++) {
				var recordChild = oRecordValues[i].getElementsByTagName(arrayDescr[j]);
				var tr2 = document.createElement("tr");
				var td1 = document.createElement("td");
				var newText1 = document.createTextNode(ucfirst(recordChild[0].nodeName)+":");
				td1.appendChild(newText1);
				var td2 = document.createElement("td");
				var newText2 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
				td2.appendChild(newText2);
				tr2.appendChild(td1);
				tr2.appendChild(td2);
				table1.appendChild(tr2);
			}
			divB.appendChild(table1);
			divA.appendChild(divB);
			oFinderResults.appendChild(divA);
		}
	}
}

function error(o) {
  	alert("Error:" + o.responseText);
}
in_array_tradicional=function(elem) {
    for(var j in this) {
        if(this[j]==arguments[0]) {
            return true;
        }
    }
    return false;
}
function removeByElement(arrayName,arrayElement) {
    for(var i=0; i<arrayName.length;i++ ) { 
        if(arrayName[i]==arrayElement)
            arrayName.splice(i,1); 
      } 
}

/**
 * Filters the results based on events at SearchFields
 * @param  string str        string to perform uppercase function
 */
function ucfirst (str) {
    // *     example 1: ucfirst('kevin van zonneveld');
    // *     returns 1: 'Kevin van zonneveld' 
    str += '';
    var f = str.charAt(0).toUpperCase();
    return f + str.substr(1);
}