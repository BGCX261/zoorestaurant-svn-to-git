	function Component() {
		return true;
	}
	
	function Component(tableConfiguration, dialogConfiguration) {

		this.tableConfiguration = tableConfiguration;
		this.dialogConfiguration = dialogConfiguration;
		
		this.loadContent = function(url){
			this.clearMessage();
			var frame = document.getElementById(this.dialog.frameName);
			var frameWindow = window.frames[this.dialog.frameName]; 
			//TODO En vez de poner el body en blanco mostrar una gif animado que diga "cargando"
			frameWindow.document.body.innerHTML = "";
			frame.src = url;
			this.dialog.show();
		};
		
		this.showUrl = function(url) {
			compo = this;
			direccion = url;
			fun = this.loadContent;
			/*
			 * Antes de abrir el dialog, chequeo si la sesion esta activa
			 */
			var sessioncallback = {
					success: function(o) {
						try { 
					    	var resp = YAHOO.lang.JSON.parse(o.responseText);
					    	if (resp.sessionActive == true) {
					    		//alert("hay sesion, muestra la url");
					    		compo.loadContent(url);
					    	} else {
					    		//alert("se acabo la sesion");
					    		componentPage = true;
					    		loginDialog.show();
					    	}
						} catch (e) {
							alert("An error ocurred: " + e);
						}
					},
					
					failure: function(o) {
						alert("An error ocurred: " + o.responseText);
					} 
				
				}
			
			var conn = YAHOO.util.Connect.originalRequest("GET", "/login/asyncCheckSession", sessioncallback, this);		
		};
		
		this.close = function() {
			this.dialog.hide();
			this.myDataTable.requery();
		};		
		
		this.createDialog = function() { 
			this.dialog = new YAHOO.widget.Dialog(dialogConfiguration.tag,  
				            { width : this.dialogConfiguration.width,
				              height: this.dialogConfiguration.height,
				              fixedcenter : true, 
				              visible : false,  
				              constraintoviewport : true,
				              hideaftersubmit: false, 
				              modal : true,
				              close : false
				             } ); 
				       
			this.dialog.validate = function() { return true; };
		
			this.dialog.frameName = this.dialogConfiguration.tag + "_frame";

			this.dialog.render();

			var frame = document.getElementById(this.dialog.frameName);
			frame.style.width = "100%";
			frame.style.height = "100%";
			frame.style.border = "none";
		};

		this.createTable = function() { 
				
				var myConfigs = { 
		            		             
		            draggableColumns:false,
		            width: this.tableConfiguration.width,
		            height: this.tableConfiguration.height
		        } 
			    
			    var action = this.tableConfiguration.url 
			    	+ "?containerType=" + this.tableConfiguration.containerType
			    	+ "&containerId=" + this.tableConfiguration.containerId;
		 		
			    this.myDataSource = new YAHOO.util.XHRDataSource(action); 

		        this.myDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_XML; 
		        this.myDataSource.responseSchema = { 
		            resultNode: "item",
		            fields: this.tableConfiguration.fieldDef,
		            metaNode: "item_list"
		        }; 
		 
		        this.myDataTable = new YAHOO.widget.ScrollingDataTable(
		        		this.tableConfiguration.tag, 
		        		this.tableConfiguration.columnDef, 
		        		this.myDataSource,
		                myConfigs); 
		        
		        this.myDataTable.subscribe("rowMouseoverEvent", this.myDataTable.onEventHighlightRow); 
		        this.myDataTable.subscribe("rowMouseoutEvent", this.myDataTable.onEventUnhighlightRow); 
		        this.myDataTable.subscribe("rowClickEvent", this.myDataTable.onEventSelectRow); 
		        this.myDataTable.subscribe("cellDblclickEvent", this.myDataTable.onEventShowCellEditor); 
		        this.myDataTable.subscribe("editorBlurEvent", this.myDataTable.onEventSaveCellEditor);
		        
		        if (tableConfiguration.hasBatchActions) {
		        	this.myDataTable.subscribe("checkboxClickEvent", function(oArgs){ 
			            var elCheckbox = oArgs.target; 
			            var oRecord = this.getRecord(elCheckbox); 
			            oRecord.setData("checked", elCheckbox.checked); 
			        });
			    } 



		        this.myDataTable.requery = function() {
					var ds = this.getDataSource();
					if (ds instanceof YAHOO.util.LocalDataSource) {
						ds.liveData = newRequest;
						ds.sendRequest("",
							{
								success: this.onDataReturnInitializeTable,
								failure: this.onDataReturnInitializeTable,
								scope: this
							}
						);
					} else {
						ds.sendRequest(
							this.get('initialRequest') ,
							{
								success: this.onDataReturnInitializeTable,
								failure: this.onDataReturnInitializeTable,
								scope: this
							}
						);
					}
		        };
				
		        //Set the default value of each page to false 
		        /*var pages = this.myDataTable.getState().pagination.pages;
		        this.toggleAllValue = Array(pages);
				for (i = 1; i <= pages; i++) {
					this.toggleAllValue[i] = false;

				
				}*/	        

		};

		this.clearMessage = function() {
			this.showMessage("");
		};

		
		this.showMessage = function(msg) {
			var messages = document.getElementById(this.dialogConfiguration.messageTag);
			if (messages) {
				messages.innerHTML = msg;
			}
		};

		this.action = function(url) {
			ajaxAction(url,
					function(table) { table.requery() }, this.myDataTable);
		};
		
		this.actionToConfirm = function(url, message) {
			if (confirm(message)) {
				ajaxAction(url,
						function(table) { table.requery() }, this.myDataTable);
			}
		};
		
		
		this.createTable();
		this.createDialog();
		
		return true;
		
	}



function createIframeDialog(dialog_tag, width, height, modal) {
	var dialog = new YAHOO.widget.Dialog(dialog_tag,  
		            { width : width + "px",
		              height: height + "px",
		              fixedcenter : true, 
		              visible : false,  
		              constraintoviewport : false,
		              hideaftersubmit: false, 
		              modal : modal
		             } ); 
	dialog.validate = function() { return true; };
	dialog.render();
	dialog.tag = dialog_tag; 
	dialog.showUrl = function(url) {
		//clearMessage();
		var frame = document.getElementById(this.tag  + "_frame");
		var frameWindow = window.frames[this.tag + "_frame"]; 
		//TODO En vez de poner el body en blanco mostrar una gif animado que diga "cargando"
		frameWindow.document.body.innerHTML = "";
		frame.src = url;
		this.show();
	};
	
	return dialog;
}

	

function idToActionsParser(editUrl, deleteUrl) {
	var parser = function(s) {
		return "<a href='javascript:showInDialog(\"" + editUrl + "?id=" + s + "\")'>Edit</a>"
			+ "&nbsp;<a href='javascript:ajaxTableAction(\"" + deleteUrl + "?id=" + s + "\")'>Delete</a>";
	};
	return parser;
}


function configureTable(containerTag, tableTag, columnDefs, fieldDefs, useCheckSelection) {

	YAHOO.util.Event.onContentReady(containerTag, function() { 

		  var myConfigs = { 
	             
	            paginator: new YAHOO.widget.Paginator({ 
	                rowsPerPage:10, 
	                template: YAHOO.widget.Paginator.TEMPLATE_ROWS_PER_PAGE, 
	                pageLinks: 3,
	                containers: 'paginator'
	            }), 
	            draggableColumns:false
	        } 
 
	        this.myDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get(tableTag)); 
	        this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
	        this.myDataSource.responseSchema = { 
	            fields: fieldDefs
	        }; 
	 
	        myDataTable = new YAHOO.widget.DataTable(containerTag, columnDefs, this.myDataSource, 
	                myConfigs); 
	        myDataTable.subscribe("rowMouseoverEvent", myDataTable.onEventHighlightRow); 
	        myDataTable.subscribe("rowMouseoutEvent", myDataTable.onEventUnhighlightRow); 
			myDataTable.subscribe("rowClickEvent", myDataTable.onEventSelectRow); 
	        myDataTable.subscribe("cellDblclickEvent", myDataTable.onEventShowCellEditor); 
	        myDataTable.subscribe("editorBlurEvent", myDataTable.onEventSaveCellEditor);
	        
	        if (useCheckSelection) {
	 			myDataTable.subscribe("checkboxClickEvent", function(oArgs){ 
		            var elCheckbox = oArgs.target; 
		            var oRecord = this.getRecord(elCheckbox); 
		            oRecord.setData("checked", elCheckbox.checked); 
		        });
		    } 

	        //Set the default value of each page to false 
	        var pages = myDataTable.getState().pagination.pages;
	        toggleAllValue = Array(pages);
			for (i = 1; i <= pages; i++) {
				toggleAllValue[i] = false;
			}	        
	         
	});
	
}

function configureAjaxTable(containerTag, url, columnDefs, fieldDefs, useCheckSelection, resultNode, metaNode) {

	YAHOO.util.Event.onContentReady(containerTag, function() { 
	  var myConfigs = { 
             
            paginator: new YAHOO.widget.Paginator({ 
                rowsPerPage:10, 
                template: YAHOO.widget.Paginator.TEMPLATE_ROWS_PER_PAGE, 
                pageLinks: 3,
                containers: 'paginator'
            }), 
            draggableColumns:false 
        } 

 		this.myDataSource = new YAHOO.util.XHRDataSource(url); 

        //this.myDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get(tableTag)); 
        this.myDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_XML; 
        this.myDataSource.responseSchema = { 
            resultNode: resultNode,
            fields: fieldDefs,
            metaNode: metaNode
        }; 
 
        myDataTable = new YAHOO.widget.DataTable(containerTag, columnDefs, this.myDataSource, 
                myConfigs); 
        myDataTable.subscribe("rowMouseoverEvent", myDataTable.onEventHighlightRow); 
        myDataTable.subscribe("rowMouseoutEvent", myDataTable.onEventUnhighlightRow); 
		myDataTable.subscribe("rowClickEvent", myDataTable.onEventSelectRow); 
        myDataTable.subscribe("cellDblclickEvent", myDataTable.onEventShowCellEditor); 
        myDataTable.subscribe("editorBlurEvent", myDataTable.onEventSaveCellEditor);
        
        if (useCheckSelection) {
 			myDataTable.subscribe("checkboxClickEvent", function(oArgs){ 
	            var elCheckbox = oArgs.target; 
	            var oRecord = this.getRecord(elCheckbox); 
	            oRecord.setData("checked", elCheckbox.checked); 
	        });
	    } 



	myDataTable.requery = function() {
		var ds = this.getDataSource();
		if (ds instanceof YAHOO.util.LocalDataSource) {
			ds.liveData = newRequest;
			ds.sendRequest("",
				{
					success: this.onDataReturnInitializeTable,
					failure: this.onDataReturnInitializeTable,
					scope: this
				}
			);
		} else {
			ds.sendRequest(
				this.get('initialRequest') ,
				{
					success: this.onDataReturnInitializeTable,
					failure: this.onDataReturnInitializeTable,
					scope: this
				}
			);
		}
	};

        //Set the default value of each page to false 
        var pages = myDataTable.getState().pagination.pages;
        toggleAllValue = Array(pages);
		for (i = 1; i <= pages; i++) {
			toggleAllValue[i] = false;
		}	        
	         
	});
	
}

function createPushButton(tag, hidden) {
	var button;
	YAHOO.util.Event.onContentReady(tag, function() {	
	
	 	button = new YAHOO.widget.Button( 
       		tag, { // Source element id 
	         	type: "button" 
	         } 
    	 );
	 	button.setVisible = function(flag) {
	 		element = document.getElementById(tag);
	 		element.style.visibility = flag ? "" : "hidden";
	 	}
	 	
	 	button.setVisible(!hidden);
	}); 	
}

function createMenuButton(formTag, submitTag, selectTag, containerTag) {
	YAHOO.util.Event.onContentReady(formTag, function() {	
		var actionsButton = new YAHOO.widget.Button(submitTag, { 
			type: "split", 
            menu: selectTag,
            containers: containerTag
			});
	}); 
}
	
function confirmSingleDelete(objectName) {
	try {
		if (window.translation == undefined) {
			window.translation = new Translation();
		}
		inputBox = confirm(translation.getMessage("Do you really want to delete the " + objectName), translation.getMessage("Delete " + objectName));
		return inputBox;
	} catch (e) {
		alert("Error confirming the deletion: " + e);
		return false;
	}
}
	
function setSelectedIds(objectNameSingular, objectNamePlural, formTag) {
	var form = document.getElementById(formTag);
	var ids = getSelectedRecordsIds();
	if(ids == "") {
		alert(translate("You must select at least one " + objectNameSingular), translate("Delete " + objectNamePlural));
		return false;
	}
	inputBox = confirm(translate("Do you really want to delete the selected " + objectNamePlural), translate("Delete " + objectNamePlural));
	if (inputBox == false) {
		return false;
	} 
	form["ids"].value = ids;
	return true;
}
	
function getSelectedRecordsIds() {
       var records = myDataTable.getRecordSet().getRecords();
	var ids = "";
    for (i = 0; i < records.length; i++) {
   	    if (records[i].getData("checked")) {
   	    	if ( i > 0) {
   	    		ids += ",";
   	    	}
   	    	ids += records[i].getData("id");
   	    }
   	}
	return ids;
}
	
function toggleAll() {
 
     var paginator = myDataTable.getState().pagination;
             
     if (!toggleAllValue[paginator.page]) {
       	toggleAllValue[paginator.page] = true;
     } else {
     	toggleAllValue[paginator.page] = false;
     }
       
 
     var records = myDataTable.getRecordSet().getRecords();
    for (i=paginator.rowsPerPage * (paginator.page - 1); 
    		(i < paginator.rowsPerPage * paginator.page) && ( i < records.length); 
    		i++) {
   	    myDataTable.getRecordSet().updateKey(records[i], "checked", toggleAllValue[paginator.page]);
   	}
 	myDataTable.refreshView();
}


function validateEmailAddress(str) {

		var at="@";
		var dot=".";
		var lat=str.indexOf(at);
		var lstr=str.length;
		var ldot=str.indexOf(dot);

		if (str.indexOf(at)==-1) {
		   return false;
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false;
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return false;
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return false;
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false;
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false;
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return false;
		 }

 		 return true	;				
	}

function ajaxTableAction(url) {
	ajaxAction(url,
		function() { myDataTable.requery() });
}

function ajaxAction(url, onSuccess, arg) {
	clearMessage();
	var callback = { 
	  success: function(o) {
	  		showMessage(o.responseText);
	  		onSuccess(o.argument);
	  	}, 
	  failure: function(o) {
	  		//displayInDialog(o);
	  		alert(translate("An error ocurred") + ": " + o.responseText);
	  		
	  },
	  argument: arg
	}; 	
	var transaction = YAHOO.util.Connect.asyncRequest("GET", url, callback, null); 	
}

function clearMessage() {
	showMessage("");
}

function showMessage(msg) {
	var messages = document.getElementById("messages");
	messages.innerHTML = msg; 
}

function executeActionWithConfirmation(url, objectName){
	
	if( confirmSingleDelete(objectName))
	{
		ajaxTableAction(url);
	}

}


function configureEditAjaxTable(containerTag, url, columnDefs, fieldDefs, useCheckSelection, resultNode, metaNode) {

	YAHOO.util.Event.onContentReady(containerTag, function() { 
	  var myConfigs = { 
             
//            paginator: new YAHOO.widget.Paginator({ 
//                rowsPerPage:10, 
//                template: YAHOO.widget.Paginator.TEMPLATE_ROWS_PER_PAGE, 
//                pageLinks: 3,
//                containers: 'paginator'
//            }), 
			  //pasarlo como param
			  height: "160px",
			  width: "900px",
            draggableColumns:false 
        } 

 		this.myDataSource = new YAHOO.util.XHRDataSource(url); 

        this.myDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_XML; 
        this.myDataSource.responseSchema = { 
            resultNode: resultNode,
            fields: fieldDefs,
            metaNode: metaNode
        }; 
 
        
        // Set up editing flow
        var highlightEditableCell = function(oArgs) {
            var elCell = oArgs.target;
            if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
                this.highlightCell(elCell);
            }
        };
        
         myDataTable = new YAHOO.widget.ScrollingDataTable(containerTag, columnDefs, this.myDataSource, 
                myConfigs); 
        myDataTable.subscribe("rowMouseoverEvent", myDataTable.onEventHighlightRow); 
        myDataTable.subscribe("rowMouseoutEvent", myDataTable.onEventUnhighlightRow); 
		myDataTable.subscribe("rowClickEvent", myDataTable.onEventSelectRow); 
        myDataTable.subscribe("cellDblclickEvent", myDataTable.onEventShowCellEditor); 
        //myDataTable.subscribe("editorBlurEvent", myDataTable.saveCellEditor);
        myDataTable.subscribe("cellMouseoverEvent", highlightEditableCell);
        myDataTable.subscribe("cellMouseoutEvent", myDataTable.onEventUnhighlightCell);
        myDataTable.subscribe("cellClickEvent", myDataTable.onEventShowCellEditor);
        
        if (useCheckSelection) {
 			myDataTable.subscribe("checkboxClickEvent", function(oArgs){ 
	            var elCheckbox = oArgs.target; 
	            var oRecord = this.getRecord(elCheckbox); 
	            oRecord.setData("checked", elCheckbox.checked); 
	        });
	    } 



	myDataTable.requery = function() {
		var ds = this.getDataSource();
		if (ds instanceof YAHOO.util.LocalDataSource) {
			ds.liveData = newRequest;
			ds.sendRequest("",
				{
					success: this.onDataReturnInitializeTable,
					failure: this.onDataReturnInitializeTable,
					scope: this
				}
			);
		} else {
			ds.sendRequest(
				this.get('initialRequest') ,
				{
					success: this.onDataReturnInitializeTable,
					failure: this.onDataReturnInitializeTable,
					scope: this
				}
			);
		}
	};

        //Set the default value of each page to false 
//        var pages = myDataTable.getState().pagination.pages;
//        toggleAllValue = Array(pages);
//		for (i = 1; i <= pages; i++) {
//			toggleAllValue[i] = false;
//		}
		
		
	});
	
		
}

function checkSessionAndExecuteFunction(){
	var sessioncallback = {
			success: function(o) {
				try { 
			    	var resp = YAHOO.lang.JSON.parse(o.responseText);
			    	if (resp.sessionActive == true) {
			    		//alert("hay sesion, muestra la url");
			    		compo.loadContent(direccion);
			    	} else {
			    		//alert("se acabo la sesion");
			    		componentPage = true;
			    		loginDialog.show();
			    	}
				} catch (e) {
					alert("An error ocurred: " + e);
				}
			},
			
			failure: function(o) {
				alert("An error ocurred: " + o.responseText);
			} 
		
		}
	
	var conn = YAHOO.util.Connect.originalRequest("GET", "/login/asyncCheckSession", sessioncallback, this);			
}







