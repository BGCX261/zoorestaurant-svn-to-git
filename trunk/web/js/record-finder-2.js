function RecordData(id) {
	
	this.id = id;
	this.dataLoaded = false;
	this.quickSearchValues = new Array();
	this.arrayDescr = undefined;
	this.arrayTitle = undefined;
	this.arrayLabels = undefined;
	
	this.addQuickSearchValue = function(key, value) {
		this.quickSearchValues[key] = value;
	}

	this.exactMatchs = function(text) {
		for (valueKey in this.quickSearchValues) {
			if (text.toLowerCase() == this.quickSearchValues[valueKey].toLowerCase()) {
				return true;
			}
		}
		return false;
	}
	
	this.matchsWords = function(words) {
		var key;
		for (key in words) {
			for (valueKey in this.quickSearchValues) {
				if (words[key].toLowerCase() == this.quickSearchValues[valueKey].toLowerCase()) {
					return true;
				}
			}
		}
		return false;
	}
	
	return true;
	
}


function createSearchDialog() {
	// Instantiate the Dialog

	var handleCancel = function() {
		this.cancel();
	};

	var dialog = new YAHOO.widget.Dialog("recordFinder_container", 
							{ fixedcenter : true,
							  visible : false,
							  constraintoviewport : true,
							  buttons : [ { text: "Close", handler: handleCancel } ],
							  zindex: 100
							});

	return dialog;
}

function RecordFinder(url, selectionCallback, dialog, type) {
		
		this.selectionHooks = new Array();
	
		this.dialog = dialog;
	
		this.data = new Array();
		
		this.url = url;
		this.urlAux = url;
		
		this.selectionCallback = selectionCallback;
		
		this.createCallback = function(finder) {
			return function(id, desc) {
				finder.selectionCallback(id, desc);
				var hooks = finder.getSelectionHooks();
				for (key in hooks) {
					var hook = hooks[key]; 
					hook(id, desc);
				}
			}
		}
		
		this.parameters = new Array();
		
		this.type = type;
		
		
		this.init = function() {
			// Define various event handlers for Dialog
			
			
			var handleSubmit = function() {
				this.submit();
			};
			
			var handleSuccess = function(o) {
				var response = o.responseText;
				response = response.split("<!")[0];
				document.getElementById("resp").innerHTML = response;
			};
			
			var handleFailure = function(o) {
				alert("Submission failed: " + o.status);
			};

			// Wire up the success and failure handlers
			this.dialog.callback = { success: handleSuccess,
								     failure: handleFailure };
			
			// Render the Dialog
			this.dialog.render();
			YAHOO.util.Event.addListener("hideDialog", "click", this.dialog.hide, this.dialog, true);
		}

		
		this.handleDataRequestSuccess = function(o) {
			var onLoaded = o.argument;

			var callback = this.createCallback(this);
			this.generateSearchDialog(o, callback);
			this.dataLoaded = true;
			onLoaded(this);
		};		
		

		this.loadData = function(onLoaded) {
			this.dataLoaded = false;
			this.arraySearchField = new Array();
			this.description = new Array();
			var postData = "";
			this.clearDialog();
			callback = { 
				success: this.handleDataRequestSuccess,
				failure: function(o) {
			  		error(o);
			  		this.dataLoaded = true;
			  	},
				scope: this,
				argument: onLoaded
			};
			
			if(this.type == undefined) {
				this.setParameter("type", "all");
			} else {
				this.setParameter("type", "onDemand");
			}
						
			var conn = YAHOO.util.Connect.asyncRequest("GET", this.url, callback, this);
			var inProgress = YAHOO.util.Connect.isCallInProgress(conn);
			/*while (inProgress == true) {
				inProgress = YAHOO.util.Connect.isCallInProgress(conn);
			}*/
		}
		

		this.setParameter = function(name, value) {
			this.parameters[name] = value;
			this.setCompleteUrl();
		}
		
		this.clearParameters = function() {
			this.parameters = new Array();
		}
		
		this.setCompleteUrl = function() {
				this.url = this.urlAux;
				for(key in this.parameters){
					if(this.searchCaracterInString('?')){
						this.url +=	"&" + key + "=" + this.parameters[key];
						} else {
							this.url +="?" + key + "=" + this.parameters[key];
						}
				}
		}
		
		this.searchCaracterInString = function (caracter) {
			var i;
			for(i=0;i < this.url.length; i++)
			{
			 if(this.url.charAt(i) == caracter) 
				 return true;
			 }
			 return false;
		}
		
		
		this.search = function() {
			//try {
				var onLoaded = function(finder) {
					finder.dialog.show();	
					focusFirst();
				}
				this.loadData(onLoaded);
				
			//} catch (e) {
			//	alert("An error ocurred launching the search dialog: " + e.message);
			//}
		}
		
		function focusFirst(){
			var oformSearch = document.getElementById("formSearch");
			var focusFirst = oformSearch.elements[0];
			focusFirst.focus();
		}
		
		this.quickSearch = function(str) {
			try {
				var onLoaded = function(finder) {
					if(finder.type == undefined) { //Data alredy loaded 
						finder.performQuickSearch(str);
					} else { // On demand
						finder.filterQuickSearch(str);
					}
				}
				this.loadData(onLoaded);
			} catch (e) {
				alert("An error ocurred launching the search dialog: " + e.message);
			}
		}

		this.performQuickSearch = function(str) {
			var result = new Array();
			var i;
			var key;
			for (key in this.data) {
					if (this.data[key].exactMatchs(str)) {
						result.push(this.data[key]);
					}
			}
			if (result.length == 1) {
				this.selectionCallback(result[0].id, result[0].title);
			} else {
				this.dialog.show();
			}
		}
		
		this.filterQuickSearch = function(str) {
			
			var callbackQS = {
					success: function(o) {
						var resp = YAHOO.lang.JSON.parse(o.responseText);
						if(resp.message) {
							this.dialog.show();
						} else {
							this.selectionCallback(resp.id, resp.code + " - " + resp.name);
						}
					},
					failure: function(o) {
						alert("An error ocurred: " + o.responseText);
					},
					scope:this,
					//argument: this
				};
			var postData = "value=" + str;
			var sUrl = this.urlAux + "QuickSearch";
			var tran = YAHOO.util.Connect.asyncRequest("POST", sUrl, callbackQS, postData);
			
		}
		
		this.clearDialog = function () {
			oFinderResults = document.getElementById("recordResults_container");
			oFinderResults.innerHTML = "";
		}
		
		this.generateSearchDialog = function(o, callback) {
			selectionCallback = callback;
			
			//Response tiene el xml
			var response = o.responseText;
			
			//Parsea el xml
			//Â¿QuÃ© pasa si el XML no es vÃ¡lido?
			var parser = new DOMParser();
		  	xmlDoc = parser.parseFromString(response,"text/xml");

		  	//Busco el tï¿½tulo del diï¿½logo
		  	var titleNode = xmlDoc.getElementsByTagName("title")[0];
			var rowField = "", 
				rowResult, 
				oFinderResults = "", 
				nodFinderSearchTable = document.getElementById("finderSearchTable");
			
			//Tag HTML donde van los resultados
			oFinderResults = document.getElementById("recordResults_container");
			oFinderResults.innerHTML = "";
			
			
				var i, 
					oRecordDescr; 
	
				//Busco los campos de tï¿½tulo de los registros
				var oRecordTitle = xmlDoc.getElementsByTagName("record_title"); 
	
				//Busco los campos de descripciï¿½n de los registros
				var showResults= "", showDescr, showTitle = oRecordTitle[0].childNodes;
				var oRecordDescr = xmlDoc.getElementsByTagName("record_description");
	
				//Busco los campos de bï¿½squeda
				var oSearchField = xmlDoc.getElementsByTagName("search_field");
				var showDescr = oRecordDescr[0].childNodes;
				
				this.arrayTitle = new Array();
			    this.arrayDescr = new Array();
				this.arrayLabel = new Array();
				
			    nodFinderSearchTable.innerHTML ="";
				var divRecordFinderSearch = document.getElementById("recordFinder_search");
				divRecordFinderSearch.innerHTML ="";
				//AcÃ¡ borra el contenido
				
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
						
						YAHOO.util.Event.addListener("input"+(i+1), "keyup", this.applyFilter, this, true);
						if(this.type == "onDemand"){
							newText1 = document.createTextNode(" Min " + oSearchField[i].getAttribute("quantity"));
							td2.appendChild(newText1);
						}
						
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
						YAHOO.util.Event.addListener("select"+(i+1), "change", this.applyFilter, this, true);
					}
					tr1.appendChild(td1);
					tr1.appendChild(td2);
					nodFinderSearchTable.appendChild(tr1);
					
					if(!in_array_tradicional(this.arrayDescr, oSearchField[i].getAttribute("name"))) {
						this.arrayDescr.push(oSearchField[i].getAttribute("name"));
						this.arrayLabel[oSearchField[i].getAttribute("name")] = oSearchField[i].getAttribute("label")
					}
					this.arraySearchField.push(oSearchField[i].getAttribute("name"));
				}
				
				var form1 = document.createElement("form");
				form1.setAttribute("id", "formSearch");
				form1.appendChild(nodFinderSearchTable);
				divRecordFinderSearch.appendChild(form1);
				
				for (i = 0; i < showDescr.length; i++) {
					this.arrayDescr.push(showDescr[i].getAttribute("name"));
					this.arrayLabel[showDescr[i].getAttribute("name")] = showDescr[i].getAttribute("label")
					if(!in_array_tradicional(this.arrayDescr, showDescr[i].getAttribute("name"))) {
						this.arrayDescr.push(showDescr[i].getAttribute("name"));
						this.arrayLabel[showDescr[i].getAttribute("name")] = showDescr[i].getAttribute("label")
					}
				}
				for (i = 0; i < showTitle.length; i++) {
					this.arrayTitle.push(showTitle[i].getAttribute("name"));
					if(in_array_tradicional(this.arrayDescr, this.arrayTitle[i]))
						removeByElement(this.arrayDescr, this.arrayTitle[i]);
				}

				if(this.type == undefined) {
				
				var oRecordValues = xmlDoc.getElementsByTagName("record");
				
				for (i = 0; i < oRecordValues.length; i++) {
					
					var divA = document.createElement("div");
					divA.setAttribute("class", "recordResult_container");
					var recordId = oRecordValues[i].getAttribute("id");
					divA.setAttribute("id", "record" + recordId);
					
					var recordData = new RecordData(recordId);
						
					
					var divB = document.createElement("div");
					divB.setAttribute("class","recordResult_title");
					var table1 = document.createElement("table");
					var tr1 = document.createElement("tr");
					tr1.setAttribute("class", "bold");
	
					
					//remueve el valor "undefined" en la posiciï¿½n
					this.description[recordId]="";
					
					//Generar el tï¿½tulo de un registro
					for (var j = 0; j < this.arrayTitle.length; j++) {
						var recordChild = oRecordValues[i].getElementsByTagName(this.arrayTitle[j]);
						var td1 = document.createElement("td");
						if (recordChild[0].childNodes[0] != undefined) {
							var newText1 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
							recordData.addQuickSearchValue(this.arrayTitle[j], recordChild[0].childNodes[0].nodeValue);
							td1.appendChild(newText1);
							tr1.appendChild(td1);
							table1.appendChild(tr1);
							//Capturo los campos de titulos de un registro
							this.description[recordId] += recordChild[0].childNodes[0].nodeValue + " - ";
						}
					}
					
					recordData.title = this.description[recordId];
					
					this.data[i] = recordData;
					
					
					//asigno un evento el cual llamarï¿½ a la funciï¿½n callback
					//pasandole como parametro loss campos de titulos del registro
					YAHOO.util.Event.addListener(divA, "click", function(event, finder) {
						//this hace referencia a divA
						var id = this.id.substring(6);
					  	var descriptionText = finder.description[recordId];
					  	finder.dialog.hide();
					  	try {
					  	selectionCallback(id, finder.description[id].substring(0, finder.description[id].length - 3));
					  	} catch (e) {
					  		alert(e);
					  	}
					}, this);
					
	
					//Genero los campos de descripciï¿½n del registro
					for (var j = 0; j < this.arrayDescr.length; j++) {
						var recordChild = oRecordValues[i].getElementsByTagName(this.arrayDescr[j]);
						var tr2 = document.createElement("tr");
						var td1 = document.createElement("td");
						var newText1 = document.createTextNode(this.arrayLabel[this.arrayDescr[j]] + ":");
						td1.appendChild(newText1);
						var td2 = document.createElement("td");
						if (recordChild[0].childNodes.length > 0) {
							var newText2 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
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
				
				//document.getElementById("recordFinder_title").innerHTML = titleNode.childNodes[0].nodeValue;
				} else { //onDemand
					
					var divA = document.createElement("div");
					divA.setAttribute("class", "recordResult_container");
					
					var divB = document.createElement("div");
					divB.setAttribute("class","recordResult_title");
					var table1 = document.createElement("table");
					var tr1 = document.createElement("tr");
					tr1.setAttribute("class", "bold");
	
					var td1 = document.createElement("td");
					var newText1 = document.createTextNode("Ingresar datos para filtrar");
					td1.appendChild(newText1);
					tr1.appendChild(td1);
					table1.appendChild(tr1);
					divB.appendChild(table1);
					divA.appendChild(divB);
					oFinderResults.appendChild(divA);
				}
				document.getElementById("recordFinder_title").innerHTML = titleNode.childNodes[0].nodeValue;
				YAHOO.util.Dom.setStyle("recordFinder_container", "display", "block");
		}

		this.applyFilter = function() {
			//ver esto, prefunto por el tipo
			if(this.type == undefined) {
				this.asyncRecordFinderFilter();
			} else {
				this.asyncOnDemandRecordFinderFilter();
			}
		}

		function error(o) {
		  	alert("Error:" + o.responseText);
		}
		
		in_array_tradicional=function(array, elem) {
		    for(var j in array) {
		        if(array[j]==elem) {
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

		function ucfirst (str) {
		    // *     example 1: ucfirst('kevin van zonneveld');
		    // *     returns 1: 'Kevin van zonneveld' 
		    str += '';
		    var f = str.charAt(0).toUpperCase();
		    return f + str.substr(1);
		}
		
		this.asyncRecordFinderFilter = function() {
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
						var recordChild = oRecordValues[i].getElementsByTagName(this.arraySearchField[j]);
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
					
					//Remueve el valor "undefined"(que viene por defecto) en la posiciï¿½n recordId
					this.description[recordId]="";
					
					//El titulo del registro en bold
					for (var j = 0; j < this.arrayTitle.length; j++) {
						var recordChild = oRecordValues[i].getElementsByTagName(this.arrayTitle[j]);
						var td1 = document.createElement("td");
						if (recordChild[0].childNodes[0] != undefined) {
							var newText1 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
							td1.appendChild(newText1);
							tr1.appendChild(td1);
							table1.appendChild(tr1);
							this.description[recordId] += recordChild[0].childNodes[0].nodeValue + " - ";
						}
					}
					
					YAHOO.util.Event.addListener(divA, "click", function(event, finder){
						//this hace referencia a divA
					  	var id = this.id.substring(6);
					  	var descriptionText = finder.description[recordId];
						finder.dialog.hide();
					  	selectionCallback(id, finder.description[id].substring(0, finder.description[id].length - 3));
					}, this);
					
					//Genero los campos de descripciï¿½n del registro
					for (var j = 0; j < this.arrayDescr.length; j++) {
				 		var recordChild = oRecordValues[i].getElementsByTagName(this.arrayDescr[j]);
						var tr2 = document.createElement("tr");
						var td1 = document.createElement("td");
						var newText1 = document.createTextNode(this.arrayLabel[this.arrayDescr[j]] + ":");
						td1.appendChild(newText1);
						var td2 = document.createElement("td");
						if (recordChild[0].childNodes.length > 0) {
							var newText2 = document.createTextNode(recordChild[0].childNodes[0].nodeValue);
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
			}
			
		}
		
		this.asyncOnDemandRecordFinderFilter = function() {
						
			var oSearchField = xmlDoc.getElementsByTagName("search_field");
			
			var oformSearch = document.getElementById("formSearch");
			
			DynamicFinder = new Object();
			DynamicFinder.values = new Array();
			
			
			for (var i=0; i < oformSearch.elements.length; i++){
				
				var elemValue = oformSearch.elements[i].value;
				if(elemValue != "") {
					var request = false;
					if(elemValue.length >= oSearchField[i].getAttribute("quantity")){
						request = true;
					} else {
						oFinderResults.innerHTML = "";
					}
					var field = new Object();
					field.fieldName = oSearchField[i].getAttribute("name");
					field.values = elemValue; 
					DynamicFinder.values.push(field); 
					//alert(values[oSearchField[i].getAttribute("name")]);
					fieldFocus = oformSearch.elements[i];
					}
			}//fin for
			
			if(request) {
				var dynamicData = YAHOO.lang.JSON.stringify(DynamicFinder);
				//alert(dynamicData);
				var postData = "values=" + dynamicData;
				
				
				var callbackDynamic = {
						success: function(o) {
							var resp = YAHOO.lang.JSON.parse(o.responseText);
							//alert(resp.record_finder);
							this.completeFinderTable(resp, this);
							fieldFocus.focus();
						},
						failure: function(o) {
							alert("An error ocurred: " + o.responseText);
						},
						scope:this,
						argument: this
					};
				
				 
				sUrl = this.url;
				var request = YAHOO.util.Connect.asyncRequest("POST", sUrl, callbackDynamic, postData);
			}
		}
		
		this.completeFinderTable = function(response, finder) {
			
			//Busco el tï¿½tulo del diï¿½logo
		  	var titleNode = response.title;
			var rowField = "", 
				rowResult, 
				oFinderResults = "", 
				nodFinderSearchTable = document.getElementById("finderSearchTable");
			
			//Tag HTML donde van los resultados
			oFinderResults = document.getElementById("recordResults_container");
			oFinderResults.innerHTML = "";
			
			var i, 
				oRecordDescr; 

			//Busco los campos de tï¿½tulo de los registros
			
			//var oRecordTitle = xmlDoc.getElementsByTagName("record_title"); 
			var oRecordTitle = response.record_title;
			
			//Busco los campos de descripciï¿½n de los registros
			//var showResults= "", showDescr, showTitle = oRecordTitle[0].childNodes;
			var oRecordDescr = response.record_description;
			
			//Busco los campos de bï¿½squeda
			//var oSearchField = xmlDoc.getElementsByTagName("search_field");
			var oSearchField = response.search_fields;
			//var showDescr = oRecordDescr[0].childNodes;
			
			this.arrayTitle = new Array();
		    this.arrayDescr = new Array();
			this.arrayLabel = new Array();
			
//		    nodFinderSearchTable.innerHTML ="";
			var divRecordFinderSearch = document.getElementById("recordFinder_search");
			divRecordFinderSearch.innerHTML ="";
			
			var form1 = document.createElement("form");
			form1.setAttribute("id", "formSearch");
			form1.appendChild(nodFinderSearchTable);
			divRecordFinderSearch.appendChild(form1);
			
			for (i = 0; i < oRecordDescr.length; i++) {
				this.arrayDescr.push(oRecordDescr[i].name);
				this.arrayLabel[oRecordDescr[i].name] = oRecordDescr[i].label;
				if(!in_array_tradicional(this.arrayDescr, oRecordDescr[i].name)) {
					this.arrayDescr.push(oRecordDescr[i].name);
					this.arrayLabel[oRecordDescr[i].name] = oRecordDescr[i].label;
				}
			}
			for (i = 0; i < oRecordTitle.length; i++) {
				this.arrayTitle.push(oRecordTitle[i].name);
				if(in_array_tradicional(this.arrayDescr, this.arrayTitle[i]))
					removeByElement(this.arrayDescr, this.arrayTitle[i]);
			}
			
			//var oRecordValues = xmlDoc.getElementsByTagName("record");
			var oRecordValues = response.records;
			for (i = 0; i < oRecordValues.length; i++) {
				
				var divA = document.createElement("div");
				divA.setAttribute("class", "recordResult_container");
				var recordId = oRecordValues[i].id;
				divA.setAttribute("id", "record" + recordId);
				
				var recordData = new RecordData(recordId);
					
				
				var divB = document.createElement("div");
				divB.setAttribute("class","recordResult_title");
				var table1 = document.createElement("table");
				var tr1 = document.createElement("tr");
				tr1.setAttribute("class", "bold");

				
				//remueve el valor "undefined" en la posiciï¿½n
				this.description[recordId]="";
				
				//Generar el tï¿½tulo de un registro
				for (var j = 0; j < this.arrayTitle.length; j++) {
					 
					var recordChild = oRecordValues[i][this.arrayTitle[j]];
					
					var td1 = document.createElement("td");
					if (recordChild != undefined) {
						
						var newText1 = document.createTextNode(recordChild);
						recordData.addQuickSearchValue(this.arrayTitle[j], recordChild);
						td1.appendChild(newText1);
						tr1.appendChild(td1);
						table1.appendChild(tr1);
						//Capturo los campos de titulos de un registro
						this.description[recordId] += recordChild + " - ";
						
					}
				}
				
				recordData.title = this.description[recordId];
				
				this.data[i] = recordData;
				
				
				//asigno un evento el cual llamarï¿½ a la funciï¿½n callback
				//pasandole como parametro loss campos de titulos del registro
				YAHOO.util.Event.addListener(divA, "click", function(event, finder){
					//this hace referencia a divA
					
				  	var id = this.id.substring(6);
				  	var descriptionText = finder.description[recordId];
				  	finder.dialog.hide();
				  	selectionCallback(id, finder.description[id].substring(0, finder.description[id].length - 3));
				}, this);
				

				//Genero los campos de descripciï¿½n del registro
				for (var j = 0; j < this.arrayDescr.length; j++) {
					var recordChild = oRecordValues[i][this.arrayDescr[j]];
					var tr2 = document.createElement("tr");
					var td1 = document.createElement("td");
					var newText1 = document.createTextNode(this.arrayLabel[this.arrayDescr[j]] + ":");
					td1.appendChild(newText1);
					var td2 = document.createElement("td");
					if (recordChild.length > 0) {
						var newText2 = document.createTextNode(recordChild);
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
			
			document.getElementById("recordFinder_title").innerHTML = titleNode;
			YAHOO.util.Dom.setStyle("recordFinder_container", "display", "block");

		}
		
		this.addSelectionHook = function(callback) {
			this.selectionHooks.push(callback);
		}
		
		this.getSelectionHooks = function() {
			return this.selectionHooks;
		}
		
		this.init();
		
		return true;
		
		
		
}

	function isAlfaNumeric(code) {
		return 65 <= code <= 90 || 97 <= code <= 122 || 48 <= code <= 57; 
	}
