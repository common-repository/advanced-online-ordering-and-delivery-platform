function setupModel(target, modelName) {
    // when a 'read categories' button was clicked
    $(document).on('click', '.read-'+modelName+'-button', function(){
        // show list of categories record
        showModelFirstPage(target, modelName);
    });
}

function showModelFirstPage(target,modelName){
	var elem = $('.read-'+modelName+'-button');
	activateTab(elem);
    var json_url=bnd_rest_url+"bnd-rest-api/"+modelName+"/read_paging";
    showModel(target, modelName, json_url);
}

function searchModel(target,modelName){
	var elem = $('.search-'+modelName+'-button');
	var keywords = $('.'+modelName+"-search-keywords").val();
    var json_url=bnd_rest_url+"bnd-rest-api/"+modelName+"/search_paging?keywords="+keywords;
    showModel(target, modelName, json_url);
}

function showModel(target, modelName, json_url){
   showLoading(target);
   $.getJSON(json_url, function(response){
	    // inject to app
	    $(target).html(response);
	    hideLoading(target);
		if ($(".table-sortable").length > 0) {
		    $( ".table-sortable" ).sortable({
		        placeholder: "ui-state-highlight"
		      });
		    $( ".table-sortable" ).disableSelection();
		}
	
	    // when a 'page' button was clicked
	    $(document).on('click', '.pagination li', function(){
	        // get json url
	        var json_url=$(this).find('a').attr('data-page');
	        // show list of products
	        showModel(target, modelName, json_url);
	    });
	    
	    // when a 'create button is cliecked
	    $(document).off('click', '.create-'+modelName+'-button');
	    $(document).on('click', '.create-'+modelName+"-button", function(){
	    	loadTemplate(modelName+"/create",target, function(){
	    		setupCreateModel(target, modelName);
	    	});
	    });
	    
	 // when a 'create button is cliecked
	    $(document).off('click', '.update-'+modelName+'-button');
	    $(document).on('click', '.update-'+modelName+'-button', function(){
	    	var id = $(this).data("id");
	    	loadTemplate(modelName+"/edit",target, function(){
	    		setupEditModel(target, modelName);
	    	},"id="+id);
	    });
	    $(document).off('click', '.delete-'+modelName+'-button');
	    $(document).on('click', '.delete-'+modelName+'-button', function(){
	    	var id = $(this).data("id");
	    	bootbox.confirm("Are you sure you want to delete the record?", function(result){
	    	    if (result==true) {
	    	    	$.ajax({
	    	    		url: bnd_rest_url+"bnd-rest-api/"+modelName+"/delete",
	    	            type : "POST",
	    	            data:"id="+id,
	    	            success : function(result) {
	    	            	console.log(result);
	    	                // category was created, go back to categories list
	    	                showModelFirstPage(target,modelName);
	    	            },
	    	            error: function(xhr, resp, text) {
	    	                console.log(xhr, resp, text);
	    	            }
	    	        });
	    	    }
	    	})
	    });
	    $(document).off('click', '#save-model-sort-order');
	    $(document).on('click', '#save-model-sort-order', function(){
	    	tableName = $(this).data("model");
	    	sortModel(target,modelName, tableName, json_url);
	    });
	    
	    $(document).off('click', '#search-'+modelName+'-button');
	    $(document).on('click', '#search-'+modelName+'-button', function(){
	    	searchModel(target,modelName, json_url);
	    });

    });
}

function sortModel(target, modelName,tableName, json_url) {
	var formData = "model="+tableName;
	var count=1;
	var prefix=""
	$(".table-sortable tr").each(function() {
		itemId = $(this).data(tableName+"-id");
		formData+="&"+prefix+itemId+"="+count++;
	});
	$.ajax({
		url: bnd_rest_url+"bnd-rest-api/save_sort_order",
        type : "POST",
        data:formData,
        success : function(result) {
        	console.log(result);
            showAlert(result["message"]);
            showModel(target, modelName, json_url);
        },
        error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    });
}

function setupCreateModel(target, modelName) {
	 $(document).on('click', '.read-'+modelName+"-button", function(){
	    	showModelFirstPage(target,modelName);
	 });
}
function setupEditModel(target, modelName) {
	 $(document).on('click', '.read-'+modelName+"-button", function(){
		 showModelFirstPage(target,modelName);
	 });
}

function saveModel(target, modelName){
	if (!$('#create-'+modelName+"-form").valid()) {
		return false;
	}
    // get form data
    var form_data=$('#create-'+modelName+"-form").serialize();
    // submit form data to api
    $.ajax({
        url: bnd_rest_url+"bnd-rest-api/"+modelName+"/create",
        type : "POST",
        data : form_data,
        success : function(result) {
            // category was created, go back to categories list
            showModelFirstPage(target,modelName);
        },
        error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    });
    return true;
}

function getModelByParentId(modelName, parent, parentId) {
	
	$.ajax({
        url: bnd_rest_url+"bnd-rest-api/"+modelName+"/read_paging",
        type : "GET",
        success : function(result) {
            // category was created, go back to categories list
            showModelFirstPage(target,modelName);
        },
        error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    });
}

function updateModel(target, modelName){
	if (!$('#update-'+modelName+"-form").valid()) {
		return false;
	}
    // get form data
    var form_data=$('#update-'+modelName+"-form").serialize();
    // submit form data to api
    $.ajax({
        url: bnd_rest_url+"bnd-rest-api/"+modelName+"/update",
        type : "POST",
        data : form_data,
        success : function(result) {
            // category was created, go back to categories list
            showModelFirstPage(target,modelName);
        },
        error: function(xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    });
    return true;
}


function updateModelFields(data, target, model, callback) {
	var url = bnd_rest_url+'bnd-rest-api/'+model+'/update';
	showLoading(target);
	var form_data = "";
	for(var key in data) {
		form_data+="&"+key+"="+data[key];
	}
	form_data = form_data.substring(1);
	$.ajax({
		  type: "POST",
		  url: url,
		  data:form_data,
		  success: function(response){
			console.log(response);
			hideLoading(target);
			showAlert(response["message"]);
			callback(response);
		  },
		  failure: function(response) {	
			  hideLoading(target);
		  }
	});
}


function getItemsByCategory(catid) {
	var json_url=bnd_rest_url+"bnd-rest-api/run_query?action=itemsForCategories&cat="+catid+"&response=item/read-category";
	/*
	showLoading("#inventory-content");
    $.getJSON(json_url, function(response){
	    // inject to app
	    $("#inventory-content").html(response);
	    
		if ($(".table-sortable").length > 0) {
			Sortable.create($(".table-sortable")[0]);
		}
	    hideLoading("#inventory-content");
    });*/
	showModel("#inventory-content","item", json_url);
}

function updateCategoryDisplay(id) {
	var source = "temp-display-"+id;
	var dest = "display-"+id;
	changeSwitch(source,dest, false);
	var data=[];
	data["id"]=id;
	data["display"]=$('#'+dest).val();
	updateModelFields(data,"#inventory-content","category", function(response){
		
	});
}

function updateDataSync(id) {
	var source = "temp-sync_enabled-"+id;
	var dest = "sync_enabled-"+id;
	changeSwitch(source,dest, false);
	var data=[];
	data["id"]=id;
	data["sync_enabled"]=$('#'+dest).val();
	updateModelFields(data,"#connection-content","data-sync", function(response){
		
	});
}

function updateModifierGroupDefault(id) {
	var source = "temp-default-"+id;
	var dest = "default-"+id;
	changeSwitch(source,dest, false);
	var data=[];
	data["id"]=id;
	data["show_by_default"]=$('#'+dest).val();
	updateModelFields(data,"#inventory-content","modifier-group", function(response){
		
	});
}

function updateItemDisplay(id) {
	var source = "temp-display-"+id;
	var dest = "display-"+id;
	changeSwitch(source,dest, true);
	var data=[];
	data["id"]=id;
	data["is_hidden"]=$('#'+dest).val();
	updateModelFields(data,"#inventory-content","item", function(response){
		
	});
}

function getModifiersByGroup(groupId) {
	var json_url=bnd_rest_url+"bnd-rest-api/run_query?action=modifiersForGroup&id="+groupId+"&response=modifier/read";
	showModel("#inventory-content","modifier", json_url);
}

function showSubModel(id, action, model, heading) {
	showModal("subModelModal");
	var json_url=bnd_rest_url+"bnd-rest-api/run_query?action="+action+"&id="+id+"&response="+model+"/read-modal";
	showLoading("#subModelModallContent");
    $.getJSON(json_url, function(response){
	    // inject to app
    	$("#subModelModalLabel").html(heading);
	    $("#subModelModallContent").html(response);
	    hideLoading("#subModelModallContent");
    });
    event.stopPropagation();
}


function showModal(id) {
	$('#'+id).modal("show");
	var left = $('.container').offset().left;
	var width = $('.container').width();
	console.log(left, width);
	$('#'+id).css("width",width*0.9);
	$('#'+id).css("left",left);
}
