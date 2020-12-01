
var requestAjax = function(endpoint, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
      if (this.readyState==4 && this.status==200) {
        callback(this.response);
      }
    };
    xhr.responseType = 'json';
    xhr.open('GET',endpoint,true);
    xhr.send();
  };

function getLocation(){
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(success, error, {"enableHighAccuracy": false, "timeout": 3000, "maximumAge": 100});
		function success(result){
                    
            var latitude = result.coords.latitude;
            var longitude = result.coords.longitude;

            var apiKey = 'AIzaSyAmXMwo26Ag96sR5zmMy5Xn8FDdIWXE39M';
            var requestURL = 'https://maps.googleapis.com/maps/api/geocode/json?language=ja&sensor=false';
            requestURL += '&latlng=' + latitude + ',' + longitude;
            requestURL += '&key=' + apiKey;
            requestAjax(requestURL, function(response){
            if (response.error_message) {
                console.log(response.error_message);
                alert(response.error_message);
            } else {
                var formattedAddress = response.results[0]['formatted_address'];
                // 住所は「日本、〒100-0005 東京都千代田区丸の内一丁目」の形式
                var data = formattedAddress.split(' ');
                if (data[1]) {
                // id=addressに住所を設定する
                alert(data[1]);
                //document.getElementById('address').innerHTML = data[1];
                }
            }
            });



		}
		function error(error){
			var errorMsg = {
				0: "原因不明のエラーにより、現在位置を取得出来ませんでした。",
				1: "位置情報の取得が許可されていません。端末の設定をご確認下さい。",
				2: "位置情報が取得出来ませんでした。",
				3: "タイムアウトにより、位置情報が取得出来ませんでした。",
			};
			if(errorMsg[error.code] === undefined){error.code = 0;}
			alert(errorMsg[error.code]);
		}
	}else{
		alert("お使いの端末では、現在位置を取得出来ません。");
	}
}