/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 *
 * This enables you to define handlers, for when the DOM is ready:
 *
 * $(function() {
 *
 * });
 *
 * When the window is loaded:
 *
 * $( window ).load(function() {
 *
 * });
 *
 * ...and/or other possibilities.
 *
 * Ideally, it is not considered best practise to attach more than a
 * single DOM-ready or window-load handler for a particular page.
 * Although scripts in the WordPress core, Plugins and Themes may be
 * practising this, we should strive to set a better example in our own work.
 * 
 *
 */
var $ = jQuery.noConflict();

$.fn.addCommas = function (nStr) {
	  nStr += "";
	  var x = nStr.split(".");
	  var x1 = x[0];
	  var x2 = x.length > 1 ? "." + x[1] : "";
	  var rgx = /(\d+)(\d{3})/;
	  while (rgx.test(x1)) {
	    x1 = x1.replace(rgx, "$1" + "," + "$2");
	  }
	  return x1 + x2;
};

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$.validator.addMethod('equals', function (value, element, param) {
    return this.optional(element) || value === $(param).val();
}, 'Value does not match');


function loadFunction(functionName, hideSubmenu) {
	$(".app-panel").hide();
	$(".app-panel-"+functionName).show();
	$(".main-menu li").removeClass("active");
	$("#"+functionName).parent().addClass("active");
	loadPanel(functionName);
}

function importModel(modelName, target) {
	$('.'+target+" .spinner-border").show();
	$('#'+target+"-sync-icon").hide();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "action=bnd_admin_import_"+modelName,
		  success: function(response){
			console.log(response);
			$('.'+target+" .spinner-border").hide();
			$('#'+target+"-sync-icon").show();
			$('#'+target).html("<i class='fas fa-check-circle text-success'></i>");
			$('.'+target+" #import-text").html(response["count"]+" "+response["message"]);
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function importAll(){
	importModel('merchant','#merchant_sync-icon');
	importModel('opening_hours','#opening_hours-sync-icon');
	importModel('order_types','#order_types-sync-icon');
	importModel('categories','#categories-sync-icon');
	importModel('items','#items-sync-icon');
	importModel('item_groups','#item_groups-sync-icon');
	importModel('attributes','#attributes-sync-icon');
	importModel('modifier_groups','#modifier_groups-sync-icon');
	importModel('item_tags','#item_tags-sync-icon');
	importModel('tax_rates','#tax_rates-sync-icon');
}

function resizeContainer() {
	/*var dataWidth= $('.menu').width();
    var containerWidth= $('#app-container').width()-(dataWidth+10);
    $('.container').attr("style","margin-left:"+(dataWidth+10)+"px;width:"+containerWidth+"px");*/
}

function saveKey(){
	var key = $('#bnd_license_key').val();
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "key="+key+"&action=bnd_admin_save_key",
		  success: function(response){
			if (response["error"]==false) {
				bootbox.alert(response["message"]);
				showCloverConnection();
			}
			else {
				bootbox.alert(response["message"]);
			}
		  },
		  failure: function(response) {	        			  
		  }
	});
}


function saveLicenseInfo(content){
	$.ajax({
		  type: "POST",
		  url: bnd_ajax_url,
		  data: "merchant_email="+content["merchant_email"]+"&action=bnd_admin_save_license",
		  success: function(response){
			return response;
		  },
		  failure: function(response) {
			  return response; 			  
		  }
	});
}

function removeKey(){
	bootbox.confirm({
	    title: "Disconnect from Clover",
	    message: "Are you sure you want to disconnect from Clover? This cannot be undone.",
	    buttons: {
	        cancel: {
	            label: '<i class="fa fa-times"></i> Cancel'
	        },
	        confirm: {
	            label: '<i class="fa fa-check"></i> Confirm'
	        }
	    },
	    callback: function (result) {
	    	console.log(result);
	       if (result) {
	    	   $.ajax({
	    			  type: "POST",
	    			  url: bnd_ajax_url,
	    			  data: "action=bnd_admin_remove_key",
	    			  success: function(response){
	    				if (response["error"]==false) {
	    					bootbox.alert(response["message"]);
	    					showCloverConnection();
	    				}
	    				else {
	    					bootbox.alert(response["message"]);
	    				}
	    			  },
	    			  failure: function(response) {	        			  
	    			  }
	    		});
	       }
	    }
	});
	
}

/*
 * Default panel loading functionality
 */
function loadPanel(functionName) {
	if ("dashboard" == functionName) {
		
	}
	else if ("inventory"==functionName) {
		setupModel('#inventory-content',"category");
		setupModel('#inventory-content',"item");
		setupModel('#inventory-content',"modifier-group");
		setupModel('#inventory-content',"option");
		showModelFirstPage('#inventory-content',"category");
	}
	else if ("connection"==functionName) {
		setupConnection();
		showCloverConnection();
	}
	else if ("merchant"==functionName) {
		setupMerchant();
		showMerchant();
	}
	else if ("delivery"==functionName) {
		setupDelivery();
		showDeliverySetup();
	}
	else if ("discount"==functionName) {
		setupModel('#discount-content',"discount-coupon");
		showModelFirstPage('#discount-content',"discount-coupon");
	}
	else if ("orders"==functionName) {
		setupModel('#order-content',"order");
		setupModel('#order-content',"order-payment");
		showModelFirstPage('#order-content',"order");
		$(document).on('click', '.read-refund-request-button', function(){
			var elem = $('.read-refund-request-button');
			activateTab(elem);
			loadTemplate('refund-request',"#order-content", function(){
				
			});
	    });
	}
	else if ("configuration"==functionName) {
		$(document).on('click', '.read-configuration-button', function(){
			var elem = $('.read-configuration-button');
			activateTab(elem);
			loadTemplate('configuration',"#configuration-content", function() {
			});
	    });
		$(document).on('click', '.read-template-button', function(){
			var elem = $('.read-template-button');
			activateTab(elem);
			loadTemplate('template',"#configuration-content", function() {
			});
	    });
		setupModel('#configuration-content',"message-template");
		var elem = $('.read-configuration-button');
		activateTab(elem);
		loadTemplate('configuration',"#configuration-content", function() {
		});
	}
	else if ("helpsupport"==functionName) {
		$(document).on('click', '.read-faq-button', function(){
			var elem = $('.read-faq-button');
			activateTab(elem);
			loadTemplate('faq',"#helpsupport-content", function() {
			});
	    });
		$(document).on('click', '.read-training-button', function(){
			var elem = $('.read-training-button');
			activateTab(elem);
			loadTemplate('training',"#helpsupport-content", function() {
			});
	    });
		var elem = $('.read-faq-button');
		activateTab(elem);
		loadTemplate('faq',"#helpsupport-content", function() {
		});
	}
}

function loadTemplate(templateName, target, callback, params) {
	showLoading(target);
	var paramString = (params!=null && params !=undefined)?"&"+params:"";
	$.ajax({
		  type: "GET",
		  url: bnd_rest_url+"bnd-rest-api/load_template?tn="+templateName+paramString,
		  success: function(response){
			$(target).html(response);
			hideLoading(target);
			if (callback) {
				callback(response);
			}
		  },
		  failure: function(response) {	        			  
		  }
	});
}

function showLoading(target) {
	$(target).addClass("show-spinner");
}

function hideLoading(target) {
	$(target).removeClass("show-spinner");
}

function showAlert(message) {
	$('#global-alert-text').html(message);
	$('#global-alert').show(1000);
	setTimeout(function() { $('#global-alert').hide(1000)},3000);
}

function changeSwitch(source,dest, reverse) {
	if ($('#'+source).is(":checked")) {
		$('#'+dest).val((reverse)?0:1);
	}
	else {
		$('#'+dest).val((reverse)?1:0);
	}
}

function changeImage(e, callback) {
	  e.preventDefault();
	  var $button = $(this);
	  // Create the media frame.
	  var file_frame = wp.media.frames.file_frame = wp.media({
	     title: 'Select or upload image',
	     library: { // remove these to show all
	        type: 'image' // specific mime
	     },
	     button: {
	        text: 'Select'
	     },
	     multiple: false  // Set to true to allow multiple files to be selected
	  });
	
	  // When an image is selected, run a callback.
	  file_frame.on('select', function () {
	     // We set multiple to false so only get one image from the uploader
	     var attachment = file_frame.state().get('selection').first().toJSON();
	     callback(attachment);
	  });
	
	  // Finally, open the modal
	  file_frame.open();
}


function saveConfiguration() {
	var form_data = $('#configuration-form').serialize();
	var configData = $('#configuration-form').serializeObject();
	var configs = [];
	$.each(configData, function(key, value) {
		configs.push({"key":key, "value":value});
	});
	showLoading('#configuration-content');
	saveConfig(configs).then(function(response){
		if (response["status"]=="success") {
			$.ajax({
				  type: "POST",
				  url: bnd_ajax_url,
				  data: form_data+"&action=bnd_admin_save_configuration",
				  success: function(response){
					  hideLoading('#configuration-content');
					  showAlert(response["message"]);
				  },
				  failure: function(response) {	   
					  hideLoading('#configuration-content');
					  showAlert(response["message"]);
				  }
			});
		}
	});
	
}

async function saveConfigs(configs){
	var updated = 0;
	var configSettings =[];
	for (const index in configs) {
	  	var config = configs[index];
		configSettings.push({
			"merchant_clid":BndSettings["merchant_id"],
	    	"config_key":config["key"],
	    	"config_value":config["value"]
	    });
	}
	var bndClient = new BNDClient();
	var response = await bndClient.saveMerchantSettings({"settings":configSettings});
    return response;
}

function displaySalesChart(data) {
	if (document.getElementById("salesChart")) {
	    var salesChart = document.getElementById("salesChart").getContext("2d");
	    var myChart = new Chart(salesChart, {
	      type: "line",
	      options: {
	        plugins: {
	          datalabels: {
	            display: false
	          },
	          title: {
	                display: true,
	                text: 'Sales this week'
	            }
	        },
	        responsive: true,
	        maintainAspectRatio: false,
	        scales: {
        	  y: {
                min: 0,
                suggestedMax:500
              }
	         
	        },
	        legend: {
	          display: false
	        },
	        tooltips: chartTooltip
	      },
	      data: {
	        labels: data['labels'],
	        datasets: [
	          {
	            label: "",
	            data: data['values'],
	            borderColor: themeColor1,
	            pointBackgroundColor: foregroundColor,
	            pointBorderColor: themeColor1,
	            pointHoverBackgroundColor: themeColor1,
	            pointHoverBorderColor: foregroundColor,
	            pointRadius: 6,
	            pointBorderWidth: 2,
	            pointHoverRadius: 8,
	            fill: false
	          }
	        ]
	      }
	    });
	}
}

function displayRevenueChart(data) {
	if (document.getElementById("revenueChart")) {
	    var salesChart = document.getElementById("revenueChart").getContext("2d");
	    var myChart = new Chart(salesChart, {
	      type: "line",
	      options: {
	        plugins: {
	          datalabels: {
	            display: false
	          },
			  title: {
		            display: true,
		            text: 'Revenue this week'
		      }
	        },
	        responsive: true,
	        maintainAspectRatio: false,
	        scales: {
        	  y: {
                min: 0,
                suggestedMax:5000,
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return '$' + value;
                    }
                }
              }
	         
	        },
	        legend: {
	          display: false
	        },
	        tooltips: chartTooltip
	      },
	      data: {
	        labels: data['labels'],
	        datasets: [
	          {
	            label: "",
	            data: data['values'],
	            borderColor: themeColor1,
	            pointBackgroundColor: foregroundColor,
	            pointBorderColor: themeColor1,
	            pointHoverBackgroundColor: themeColor1,
	            pointHoverBorderColor: foregroundColor,
	            pointRadius: 6,
	            pointBorderWidth: 2,
	            pointHoverRadius: 8,
	            fill: false
	          }
	        ]
	      }
	    });
	}
}

var chartTooltip = {
    backgroundColor: '#ffffff',
    titleFontColor: '#008ecc',
    borderColor: '#c8c8c8',
    borderWidth: 0.5,
    bodyFontColor: '#008ecc',
    bodySpacing: 10,
    xPadding: 15,
    yPadding: 15,
    cornerRadius: 0.15,
    displayColors: false
};

var themeColor1 ='#008ecc';
var themeColor2 ='#01decc';
var themeColor3 ='#01de88';
var themeColor4 ='#54de88';
var themeColor5 ='#308ecc';
var themeColor6 ='#01d1cc';
var themeColor7 ='#31de88';
var themeColor8 ='#54deff';
var themeColor9 ='#998ecc';
var themeColor10 ='#de01cc';
var themeColor11 ='#013388';
var themeColor12 ='#54cd32';
var themeColor1_10 ='#008ecc';
var themeColor2_10 ='#01decc';
var themeColor3_10 ='#01de88';
var themeColor4_10 ='#54de88';
var themeColor5_10 ='#308ecc';
var themeColor6_10 ='#01d1cc';
var themeColor7_10 ='#31de88';
var themeColor8_10 ='#54deff';
var themeColor9_10 ='#998ecc';
var themeColor10_10 ='#de01cc';
var themeColor11_10 ='#013388';
var themeColor12_10 ='#54cd32';

var foregroundColor ='white';

var colorsArray = [themeColor1,themeColor2,themeColor3,themeColor4,themeColor5,themeColor6,themeColor7,themeColor8,themeColor9,themeColor10,themeColor11,themeColor12]
var borderColorsArray = [themeColor1_10,themeColor2_10,themeColor3_10,themeColor4_10,themeColor5_10,themeColor6_10,themeColor7_10,themeColor8_10,themeColor9_10,themeColor10_10,themeColor11_10,themeColor12_10]

function displayPolarChart(categoryData) {
	if (document.getElementById("polarChart")) {
        var polarChart = document.getElementById("polarChart").getContext("2d");
        var myChart = new Chart(polarChart, {
          type: "polarArea",
          options: {
            plugins: {
              datalabels: {
                display: false
              }
            },
            responsive: true,
            maintainAspectRatio: false,
            scale: {
              ticks: {
                display: false
              }
            },
            legend: {
              position: "bottom",
              labels: {
                padding: 30,
                usePointStyle: true,
                fontSize: 12
              }
            },
            tooltips: chartTooltip
          },
          data: {
            datasets: [
              {
                label: "Categories Chart",
                borderWidth: 2,
                pointBackgroundColor: themeColor1,
                borderColor: borderColorsArray,
                backgroundColor: colorsArray,
                data: categoryData["values"]
              }
            ],
            labels: categoryData["labels"]
          }
        });
      }
}

function loadSmallCharts() {
var smallChartOptions = {
        layout: {
          padding: {
            left: 5,
            right: 5,
            top: 10,
            bottom: 10
          }
        },
        plugins: {
          datalabels: {
            display: false
          }
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: false
        },
        tooltips: {
          intersect: false,
          enabled: false,
          custom: function (tooltipModel) {
            if (tooltipModel && tooltipModel.dataPoints) {
              var $textContainer = $(this._chart.canvas.offsetParent);
              var yLabel = tooltipModel.dataPoints[0].yLabel;
              var xLabel = tooltipModel.dataPoints[0].xLabel;
              var label = tooltipModel.body[0].lines[0].split(":")[0];
              $textContainer.find(".value").html("$" + $.fn.addCommas(yLabel));
              $textContainer.find(".label").html(label + "-" + xLabel);
            }
          }
        },
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true
              },
              display: false
            }
          ],
          xAxes: [
            {
              display: false
            }
          ]
        }
      };

      var smallChartInit = {
        afterInit: function (chart, options) {
          var $textContainer = $(chart.canvas.offsetParent);
          var yLabel = chart.data.datasets[0].data[0];
          var xLabel = chart.data.labels[0];
          var label = chart.data.datasets[0].label;
          $textContainer.find(".value").html("$" + $.fn.addCommas(yLabel));
          $textContainer.find(".label").html(label + "-" + xLabel);
        }
      };

      if (document.getElementById("smallChart1")) {
        var smallChart1 = document
          .getElementById("smallChart1")
          .getContext("2d");
        var myChart = new Chart(smallChart1, {
          type: "line",
          plugins: [smallChartInit],
          data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
              {
                label: "Total Orders",
                borderColor: themeColor1,
                pointBorderColor: themeColor1,
                pointHoverBackgroundColor: themeColor1,
                pointHoverBorderColor: themeColor1,
                pointRadius: 2,
                pointBorderWidth: 3,
                pointHoverRadius: 2,
                fill: false,
                borderWidth: 2,
                data: [1250, 1300, 1550, 921, 1810, 1106, 1610],
                datalabels: {
                  align: "end",
                  anchor: "end"
                }
              }
            ]
          },
          options: smallChartOptions
        });
      }

      if (document.getElementById("smallChart2")) {
        var smallChart2 = document
          .getElementById("smallChart2")
          .getContext("2d");
        var myChart = new Chart(smallChart2, {
          type: "line",
          plugins: [smallChartInit],
          data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
              {
                label: "Pending Orders",
                borderColor: themeColor1,
                pointBorderColor: themeColor1,
                pointHoverBackgroundColor: themeColor1,
                pointHoverBorderColor: themeColor1,
                pointRadius: 2,
                pointBorderWidth: 3,
                pointHoverRadius: 2,
                fill: false,
                borderWidth: 2,
                data: [115, 120, 300, 222, 105, 85, 36],
                datalabels: {
                  align: "end",
                  anchor: "end"
                }
              }
            ]
          },
          options: smallChartOptions
        });
      }

      if (document.getElementById("smallChart3")) {
        var smallChart3 = document
          .getElementById("smallChart3")
          .getContext("2d");
        var myChart = new Chart(smallChart3, {
          type: "line",
          plugins: [smallChartInit],
          data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
              {
                label: "Active Orders",
                borderColor: themeColor1,
                pointBorderColor: themeColor1,
                pointHoverBackgroundColor: themeColor1,
                pointHoverBorderColor: themeColor1,
                pointRadius: 2,
                pointBorderWidth: 3,
                pointHoverRadius: 2,
                fill: false,
                borderWidth: 2,
                data: [350, 452, 762, 952, 630, 85, 158],
                datalabels: {
                  align: "end",
                  anchor: "end"
                }
              }
            ]
          },
          options: smallChartOptions
        });
      }

      if (document.getElementById("smallChart4")) {
        var smallChart4 = document
          .getElementById("smallChart4")
          .getContext("2d");
        var myChart = new Chart(smallChart4, {
          type: "line",
          plugins: [smallChartInit],
          data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [
              {
                label: "Shipped Orders",
                borderColor: themeColor1,
                pointBorderColor: themeColor1,
                pointHoverBackgroundColor: themeColor1,
                pointHoverBorderColor: themeColor1,
                pointRadius: 2,
                pointBorderWidth: 3,
                pointHoverRadius: 2,
                fill: false,
                borderWidth: 2,
                data: [200, 452, 250, 630, 125, 85, 20],
                datalabels: {
                  align: "end",
                  anchor: "end"
                }
              }
            ]
          },
          options: smallChartOptions
        });
      }
}



function activateTab(elem) {
	$('.tabs li a').removeClass("active");
	elem.addClass("active");
}


function showInfo(message, subtitle, callback) {
    if (subtitle==null || subtitle==undefined) {
	  subtitle="Info";		
    }
    showToast(message, subtitle, "info", callback);
}

function showSuccess(message, subtitle, callback) {
    if (subtitle==null || subtitle==undefined) {
	  subtitle="Success";		
    }
    showToast(message, subtitle, "success", callback);
}

function showWarning(message, subtitle, callback) {
    if (subtitle==null || subtitle==undefined) {
	  subtitle="Warning";		
    }
    showToast(message, subtitle, "warning", callback);
}

function showError(message, subtitle, callback) {
    if (subtitle==null || subtitle==undefined) {
	  subtitle="Error";		
    }
    showToast(message, subtitle, "error", callback);
}

function showToast(message, subtitle, type, callback) {
    bootbox.alert({
	  message:message,
	  title:subtitle,
	  className:'bg-outline-'+type,
	  callback:callback
    });
}
