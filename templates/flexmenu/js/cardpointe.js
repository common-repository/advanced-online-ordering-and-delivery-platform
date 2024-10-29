class CardPointe {
	urlPayment = {
        "us": {
          "production": "https://fts.cardconnect.com/cardconnect/rest",
          "sandbox": "https://buynowdepot.com/is3M5mBEKLKHqWhq/cardpointe/api"
        },
        "eu": {
        	"production": "https://fts.cardconnect.com/cardconnect/rest",
            "sandbox": "https://buynowdepot.com/is3M5mBEKLKHqWhq/cardpointe/api"
        }
	};
	urlToken = {
	    "us": {
	    	"production": "https://fts.cardconnect.com/cardsecure/api/v1/ccn/tokenize",
	        "sandbox": "https://fts-uat.cardconnect.com/cardsecure/api/v1/ccn/tokenize"
	    },
	    "eu": {
	    	"production": "https://fts.cardconnect.com/cardsecure/api/v1/ccn/tokenize",
	        "sandbox": "https://fts-uat.cardconnect.com/cardsecure/api/v1/ccn/tokenize"
	    }
	};
	
	apiUrls = {
        'authCapture': '/auth',
	}
	
	
	async callAPI( apiName,  method, params,  jsonData)  {
        var apiUrl = this.apiUrls[apiName];
        $.each(params, function(key, value) {
            var regExp = new RegExp("\\{" + key + "\\}", 'g');
            apiUrl = apiUrl.replace(regExp, value);
        });
        console.log(apiUrl);
        var endPoint = this.getCardConnectUrl() + apiUrl;
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
        headers["Authorization"] = "Basic "+btoa(BndSettings["cardpointe_username"]+":"+BndSettings["cardpointe_password"]);
        headers["Access-Control-Allow-Origin"]="*";
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
          var headers = {
             "Authorization":"Basic "+btoa(BndSettings["cardpointe_username"]+":"+BndSettings["cardpointe_password"]),
             "Content-Type":"application/json",
          }
          console.log(headers);
          console.log(jsonData);
          try {
          	var result = await $.ajax({
                  url:url,
                  cache:false,
      			  crossDomain:true,
                  type:'POST',
                  headers:headers,
                  contentType:'application/json',
                  data:JSON.stringify(jsonData),
              });
          	  console.log(JSON.stringify(result));
              return {"error": false, "content": JSON.parse(result)};
        	  /*
        	  var httpRequest = new XMLHttpRequest();
        	  httpRequest.open('PUT', url, true);
        	  httpRequest.setRequestHeader("Content-Type", "application/json");
        	  //httpRequest.setRequestHeader("Authorization","Basic "+btoa(BndSettings["cardpointe_username"]+":"+BndSettings["cardpointe_password"]));
        	  httpRequest.onreadystatechange = function(){
        		  // Process the server response here.
        		  if (httpRequest.readyState === XMLHttpRequest.DONE) {
        		    if (httpRequest.status === 200) {
        		      return {"error": false, "content": httpRequest.responseText};
        		    } else {
        		      alert('There was a problem with the request.');
        		    }
        		  }
        	  }
        	  httpRequest.send(jsonData);*/
          }
          catch(error) {
          	console.log('Error' + JSON.stringify(error));
          	return {"error": true, "content": error};
          }
        }

      async sendGetRequest( url)  {
    	var headers = {};
        headers["Authorization"] = "Basic "+btoa(BndSettings["cardpointe_username"]+":"+BndSettings["cardpointe_password"]);
        headers["Access-Control-Allow-Origin"]="*";
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
        	return {"error": true, "content": error};
        }
      }
      
      async sendPayment(paymentData) {
    	  var response = await this.callAPI("authCapture","PUT",{},paymentData);
    	  return response;
      }
      
      getCardConnectUrl() {
          return this.urlPayment[BndSettings["api_region"]][BndSettings["api_env"]];
      }
      getCardConnectSecureUrl() {
          return this.urlPayment[BndSettings["api_region"]][BndSettings["api_env"]];
      }
}