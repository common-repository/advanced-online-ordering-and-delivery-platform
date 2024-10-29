class BNDClient {
	urlService = {
        "us": {
          "production": "https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/api/v1",
          "sandbox": "https://buynowdepot.com/is3M5mBEKLKHqWhq/api/v1",
        },
        "eu": {
        	"production": "https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/api/v1",
            "sandbox": "https://buynowdepot.com/5yBHLOtOqVUqeGPiPtip/api/v1"
        }
	};
	apiUrls = {
        'addDeliveryZone': '/merchant-delivery-zone.json',
        'listDeliveryZones': '/merchant-delivery-zone/list-delivery-zones/{mid}.json',
        'saveEmployeeDelivery':'/device-register.json',
        'getDriverDetails':'/order-delivery/available-drivers/{mid}/{eid}.json',
        'saveOrderDriver':'/order-delivery.json',
        'listAllDeliveries': '/order-delivery/all-orders/{mid}.json',
        'getDeliveryDetail':'/order-delivery/delivery-detail/{mid}/{oid}.json',
        'getLocationData':'/delivery-tracker/get-location/{mid}/{oid}/{ctime}.json',
        'registerAdmin':'/merchant-user.json',
        'merchantSettings':'/merchant-settings.json',
        'getMerchantSettings':'/merchant-settings.json?mid={mid}',
        'registerDevice':'/device-license.json',
        'validateAdminLogin':'/merchant-user/validate-login.json',
        'cloverUpdates':'/webhook-data.json?merchant={mid}&lastSync={syncTime}',        
	}
	
	
	async callAPI( apiName,  method, params,  jsonData)  {
        var apiUrl = this.apiUrls[apiName];
        $.each(params, function(key, value) {
            var regExp = new RegExp("\\{" + key + "\\}", 'g');
            apiUrl = apiUrl.replace(regExp, value);
        });
        console.log(apiUrl);
        var endPoint = this.getBndUrl() + apiUrl;
        console.log(endPoint);
        if (method == "GET") {
          return await this.sendGetRequest(endPoint);
        } 
        else if (method == "PUT") {
          return await this.sendPutRequest(endPoint, jsonData);
        } 
        else {
          return await this.sendPostRequest(endPoint, jsonData);
        }
      }
    
      async sendPostRequest(url,  jsonData)  {
        var headers = {};
        //headers["Authorization"] = "Basic "+btoa(BndSettings["cardpointe_username"]+":"+BndSettings["cardpointe_password"]);
        //headers["Access-Control-Allow-Origin"]="*";
        try {
        	var data = await $.ajax({
                url:url,
                cache:false,
    			crossDomain:true,
                type:'post',
                headers:headers,
                dataType:'json',
                data:jsonData,
            });
        	console.log(JSON.stringify(data));
            return {"error": false, "content": data};
        }
        catch(error) {
        	console.log('Error' + JSON.stringify(error));
        	return {"error": true, "content": error};
        }
      }
      
      async sendPutRequest(url,  jsonData)  {
          /*var headers = {
             "Authorization":"Basic "+btoa(BndSettings["cardpointe_username"]+":"+BndSettings["cardpointe_password"]),
             "Content-Type":"application/json",
          }
          console.log(headers);
          console.log(jsonData);*/
          try {
          	var result = await $.ajax({
                  url:url,
                  cache:false,
      			  crossDomain:true,
                  type:'PUT',
                  headers:headers,
                  contentType:'application/json',
                  data:JSON.stringify(jsonData),
              });
          	  console.log(JSON.stringify(result));
              return {"error": false, "content": JSON.parse(result)};
          }
          catch(error) {
          	console.log('Error' + JSON.stringify(error));
          	return {"error": true, "content": {"status":"error", "content":error}};
          }
        }

      async sendGetRequest( url)  {
    	var headers = {};
        //headers["Authorization"] = "Basic "+btoa(BndSettings["cardpointe_username"]+":"+BndSettings["cardpointe_password"]);
        //headers["Access-Control-Allow-Origin"]="*";
        try {
	        var data = await $.ajax({
	            url:url,
	            cache:false,
				crossDomain:true,
	            headers:headers,
	        });
	        return {"error": false, "content": data};
        }
        catch(error) {
        	console.log('Error' + error);
        	return {"error": true, "content": {"status":"error", "content":error}};
        }
      }
      
      async saveDeliveryZone(zoneData) {
    	  var response = await this.callAPI("addDeliveryZone","POST",{},zoneData);
    	  return response;
      }
      
      async saveOrderDriver(orderDriverData) {
    	  var response = await this.callAPI("saveOrderDriver","POST",{},orderDriverData);
    	  return response;
      }
      
      async saveMerchantSettings(settingsData) {
    	  var response = await this.callAPI("merchantSettings","POST",{},settingsData);
    	  return response["content"];
      }
      async getMerchantSettings() {
    	  var params = {"mid": BndSettings["merchant_id"]};
    	  return await this.callAPI("getMerchantSettings","GET",params,{});
      }
      
      async saveEmployeeDelivery(employeeDeliveryData) {
    	  var response = await this.callAPI("saveEmployeeDelivery","POST",{},employeeDeliveryData);
    	  return response;
      }
      
      async registerDevice(licenseData) {
    	  var response = await this.callAPI("registerDevice","POST",{},licenseData);
    	  return response["content"];
      }
      
      async registerAdmin(adminData) {
    	  var response = await this.callAPI("registerAdmin","POST",{},adminData);
    	  return response["content"];
      }
      
      async validateAdminLogin(adminData) {
    	  var response = await this.callAPI("validateAdminLogin","POST",{},adminData);
    	  return response["content"];
      }
      
      async saveDeliverySetup(deliverySetupData) {
    	  var response = await this.callAPI("merchantSettings","POST",{},deliverySetupData);
    	  if (response["error"]==false) {
    		  return response["content"];
    	  }
    	  else {
    		  return {"status":"error", content:"Setup can't be saved"};
    	  }
      }
      
      async getDriverDetails(employeeId)  {
         var params = {"mid": BndSettings["merchant_id"],"eid":employeeId};
         var response = await this.callAPI("getDriverDetails", "GET", params, {}); 	 
      	 if (response["error"] == false) {
            var driverDetails = response["content"];
            return {
                "status": "success",
                "content": driverDetails,
              };
          } else {
            return {"status": "error", "message": "Error during delivery zone import"};
          }
      }
      
      async getDeliveryDetail(orderId)  {
          var params = {"mid": BndSettings["merchant_id"],"oid":orderId};
          var response = await this.callAPI("getDeliveryDetail", "GET", params, {}); 	 
       	 if (response["error"] == false) {
             var deliveryDetail = response["content"];
             return {
                 "status": "success",
                 "content": deliveryDetail,
               };
           } else {
             return {"status": "error", "message": "Error during delivery zone import"};
           }
       }
      
      async getLocationData(orderId, captureTime)  {
          var params = {"mid": BndSettings["merchant_id"],"oid":orderId, "ctime":captureTime};
          var response = await this.callAPI("getLocationData", "GET", params, {}); 	 
       	 if (response["error"] == false) {
             var locationDetail = response["content"];
             return {
                 "status": "success",
                 "content": locationDetail,
               };
           } else {
             return {"status": "error", "message": "Error during delivery zone import"};
           }
       }
      
      async getCloverUpdates(lastSyncTime)  {
          var params = {"mid": BndSettings["merchant_id"],"syncTime":lastSyncTime};
          var response = await this.callAPI("cloverUpdates", "GET", params, {}); 	 
       	 if (response["error"] == false) {
             var cloverUpdates = response["content"];
             return {
                 "status": "success",
                 "content": cloverUpdates,
               };
           } else {
             return {"status": "error", "message": "Error during getting clover updates"};
           }
       }
      
      async getAllDeliveries()  {
         var params = {"mid": BndSettings["merchant_id"]};
         return await this.callAPI("listAllDeliveries", "GET", params, {});
      }
      
      async importDeliveryZones()  {
          var params = {"mid": BndSettings["merchant_id"]};
          var response = await this.callAPI("listDeliveryZones", "GET", params, {}); 	 
      	 if (response["error"] == false) {
            var deliveryZones = response["content"];
            var saved = await saveDeliveryZones(deliveryZones);
          	if (saved["status"]=="success") {
          		 return {
                       "status": "success",
                       "message": "Delivery zones imported",
                     };
          	 }
          	 else {
          		return {"status": "error", "message": "Error during delivery zone save"};
          	 }
          } else {
            return {"status": "error", "message": "Error during delivery zone import"};
          }
      }
      
      getBndUrl() {
          return this.urlService[BndSettings["api_region"]][BndSettings["api_env"]];
      }
}