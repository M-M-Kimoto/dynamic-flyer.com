<?php

	// 基本情報ページ専用のPHPファイル
	define('ショップ情報取得処理', comフォルダ . 'bat_ショップ情報取得処理.php');

	clearstatcache(); 
?>

<script type="text/javascript" src="./js/tapCtrl.js"></script>
<script type="text/javascript">

	function Set_Eve_favImg(){

		if("<?php echo $_SESSION['ID']; ?>" == "<?php echo ゲストユーザ['ID'] ; ?>"){
			return ;
		}

		if(document.getElementById("favImg").getAttribute("flg") == 0){

			// お気に入り登録
			Sub_regFav("<?php echo $_SESSION['ID']; ?>", "<?php echo $_GET['shopID']; ?>", 0);
			document.getElementById("favImg").src = "./img/星_黄.png";
			document.getElementById("favImg").setAttribute("flg", "1");

		}else{

			// お気に入り解除
			Sub_regFav("<?php echo $_SESSION['ID']; ?>", "<?php echo $_GET['shopID']; ?>", 1);
			document.getElementById("favImg").src = "./img/星_青.png";
			document.getElementById("favImg").setAttribute("flg", "0");
		}

	}

</script>

<script type="text/javascript">

	// フラグ true:処理中 false:待機　※0は使わない
	var procFlg_ShopInfo = ['', true, true, true, true, true, true, true, true , true, true];


	function pageScript_start(){

		document.getElementById("warning").style.display = "none";
		document.getElementById("function").style.display = "none";
		document.getElementById("siteContent").style.display = "none";
		document.getElementById("detailsImg").style.display = "none";
		document.getElementById("salesTime").style.display = "none";
		document.getElementById("address").style.display = "none";
		document.getElementById("payment").style.display = "none";
		document.getElementById("other").style.display = "none";
		document.getElementById("link").style.display = "none";

		// スタート直後にロックする
    	screenLock();

		// ショップ情報
		Sub_Get_ShopInfo(1);

		// 通知情報
		Sub_Get_ShopInfo(2);

		// 通常営業時間
		Sub_Get_ShopInfo(3);

		// 不定休
		Sub_Get_ShopInfo(4);

		// 不定営
		Sub_Get_ShopInfo(5);

		// リンク
		Sub_Get_ShopInfo(6);

		// SNS
		Sub_Get_ShopInfo(7);

		// 基本情報画像設定
		Sub_Get_ShopInfo(8);

		// 支払方法
		Sub_Get_ShopInfo(9);

		// その他
		Sub_Get_ShopInfo(10);

	}

	// ajaxを使用したショップ情報取得処理
	function Sub_Get_ShopInfo(PI_kbn){
		procFlg_ShopInfo = true;
		$.ajax({
			type: 'post',
			url: '<?php echo ショップ情報取得処理 ?>',
			data: {"kbn":PI_kbn , "userID":"<?php echo $_SESSION['ID']; ?>", "shopID":"<?php echo $_GET['shopID']; ?>"},
			success : function(res){
				console.log("ajax通信に成功しました");
				console.log(res);

				if(res != ''){
					// select結果のJSONを取得
					var jsonData = JSON.parse(res);

					// 取得データをhtmlに埋め込む
					switch (PI_kbn){
						case 1:
							Sub_SetHTML_kbn1(jsonData);
							break;
						case 2:
							Sub_SetHTML_kbn2(jsonData);
							break;
						case 3:
							Sub_SetHTML_kbn3(jsonData);
							break;
						case 4:
							Sub_SetHTML_kbn4(jsonData);
							break;
						case 5:
							Sub_SetHTML_kbn5(jsonData);
							break;
						case 6:
							Sub_SetHTML_kbn6(jsonData);
							break;
						case 7:
							Sub_SetHTML_kbn7(jsonData);
							break;
						case 8:
							Sub_SetHTML_kbn8(jsonData);
							break;
						case 9:
							Sub_SetHTML_kbn9(jsonData);
							break;
						case 10:
							Sub_SetHTML_kbn10(jsonData);
							break;
					}
						
				}

				proc_screenLock(PI_kbn);

			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				console.log("ajax通信に失敗しました");
				console.log("XMLHttpRequest : " + XMLHttpRequest.status);
				console.log("textStatus     : " + textStatus);
				console.log("errorThrown    : " + errorThrown.message);

				document.getElementById("errMsg").innerText = '通信に失敗しました。時間を開けてからもう一度実行してください。';

				proc_screenLock(PI_kbn);
			}
		});
	}

	// 引数の処理フラグを待機にし、全てのフラグが待機になればロックを解除する
	function proc_screenLock(PI_kbn){

		// 待機に
		procFlg_ShopInfo[PI_kbn] = false;

		// 一つでも処理中があれば処理を終了する
		for (let idx = 1; idx <= 10; idx++) {
			if(procFlg_ShopInfo[idx] == true){
				return ;
			}
		}
		// trueが全てなくなったのでロック解除
		screenUnLock();
	}

	/*
	*
	*ショップ基本情報
	*
	*/
	function Sub_SetHTML_kbn1(PI_JsonData){

		var ID_name = '';
		var row = PI_JsonData[0];

		document.getElementById("pageTitle-lbl").innerText = row.正式名称;

		/*
		*
		* 運営管理フラグ
		*
		*/
		if(row.運営管理フラグ == "1"){
			
			var warning = document.getElementById("warning-inner");

			var msg_1 = document.createElement("p");
			msg_1.innerText ="運営者によってメンテされているページです。";

			var msg_2 = document.createElement("a");
			msg_2.href = "https://<?php echo $_SERVER["HTTP_HOST"]; ?>/shop/?shopID=addnew" ;
			msg_2.innerText = "店長・オーナー様の方はこちらへ";


			warning.appendChild(msg_1);
			warning.appendChild(msg_2);
			document.getElementById("warning").style.display = "block";

		}

		/*
		*
		* 機能divタグについて
		*
		*/
		//初期化
		document.getElementById("function").style.display = "none";
		document.getElementById("icon-fav").style.display = "none";
		document.getElementById("icon-Connect").style.display = "none";
		document.getElementById("icon-reserva").style.display = "none";
		document.getElementById("icon-job").style.display = "none";

		if(row.お気に入り機能 == "1"){
			document.getElementById("function").style.display = "block";
			document.getElementById("icon-fav").style.display = "block";

			if(row.お気に入りフラグ == "1"){
				document.getElementById("favImg").src = "./img/星_黄.png"
				document.getElementById("favImg").setAttribute("flg", "1")
			}else{
				document.getElementById("favImg").src = "./img/星_青.png"
				document.getElementById("favImg").setAttribute("flg", "0")
			}
		}

		if(row.連絡機能 == "1"){
			document.getElementById("function").style.display = "block";
			document.getElementById("icon-Connect").style.display = "block";
		}

		if(row.予約機能 == "1"){
			document.getElementById("function").style.display = "block";
			document.getElementById("icon-reserva").style.display = "block";
		}

		if(row.採用活動機能 == "1"){
			document.getElementById("function").style.display = "block";
			document.getElementById("icon-job").style.display = "block";
		}

		/*
		*
		*トップ画像、キャッチコピー
		*
		*/
		let val = row.メッセージ.trimEnd();
		document.getElementById("catchMsg").innerText = val;
		if(val == ""){
			document.getElementById("catchText").style.display = "none";
		}else{
			document.getElementById("catchText").style.display = "block";
		}

		/*
		*
		*住所、連絡先
		*
		*/
		document.getElementById("google-map").style.display = "none";

		// 郵便番号
		document.getElementById("postNo").style.display = "none";
		if (row.郵便番号 != ''){
			document.getElementById("postNo").style.display = "list-item";
			document.getElementById("postNo").innerText = '〒' + row.郵便番号;
		}

		//住所
		document.getElementById("shopAddress_1").style.display = "none";
		document.getElementById("shopAddress_2").style.display = "none";
		if (row.都道府県 != '' && row.市区町村 != '' && row.町名番地 != ''){
			document.getElementById("address").style.display = "block";
			document.getElementById("shopAddress_1").style.display = "list-item";
			document.getElementById("shopAddress_1").innerText = row.都道府県 + row.市区町村 + row.町名番地;

			if (row.建物等 != ''){
				document.getElementById("shopAddress_2").style.display = "list-item";
				document.getElementById("shopAddress_2").innerText = row.建物等;
			}
		}

		// 電話番号
		document.getElementById("tel").style.display = "none";
		if (row.電話番号 != ''){
			document.getElementById("address").style.display = "block";
			document.getElementById("tel").style.display = "list-item";
			document.getElementById("tel").innerText = 'Tel：' + row.電話番号;
		}

		// fax
		document.getElementById("fax").style.display = "none";
		if (row.FAX番号 != ''){
			document.getElementById("fax").style.display = "list-item";
			document.getElementById("fax").innerText = 'Fax：' + row.FAX番号;
		}

		// メールアドレス
		document.getElementById("mail").style.display = "none";
		if (row.メールアドレス != ''){
			document.getElementById("address").style.display = "block";
			document.getElementById("mail").style.display = "list-item";
			document.getElementById("mail").innerText = 'Mail：' + row.メールアドレス;
		}

		// google-map
		document.getElementById("google-map").style.display = "none";
		if (document.getElementById("shopAddress_1").style.display != "none"){
			document.getElementById("address").style.display = "block";
			document.getElementById("google-map").style.display = "block";
			document.getElementById("google-map-iframe").src = "https://maps.google.co.jp/maps?output=embed&q=" + row.都道府県 + row.市区町村 + row.町名番地 + " " + row.建物等;
		}

	}

	/*
	*
	*通知情報取得
	*
	*/
	function Sub_SetHTML_kbn2(PI_JsonData){

		document.getElementById("siteContent").style.display = "block";
		if(PI_JsonData.length == 0){
			
			document.getElementById("siteContent").style.display = "none";
			
			var shopDetails =  document.getElementById("siteContent");

			var h4 = document.createElement("h4");
			h4.innerText = "現在お店からの通知はありません。"
			shopDetails.appendChild(h4);
			return;
		}

		var div_noticeList = document.getElementById("notice-list");
		let idx = 1;
		PI_JsonData.forEach(function (row){

			var div_item = document.createElement("div");
			div_item.setAttribute("class", "notice-item") ;
			if(row.通知区分コード == 1){
				div_item.style.backgroundColor = 'yellow';
			}

			var a_link = document.createElement("a");
			if(decodeURI(location.href) != row.表示ページURL){
				a_link.href = row.表示ページURL ;
				a_link.style.color = 'blue';
			}else{
				a_link.textDecoration = 'none';
			}

			var label_date = document.createElement("label");
			label_date.setAttribute("class", "notice-date") ;
			label_date.innerText = row.開始日時 + ' ~ ' + row.終了日時;

			var label_msg = document.createElement("label");
			label_msg.setAttribute("class", "notice-msg") ;
			label_msg.innerText = row.メッセージ;


			a_link.appendChild(label_date);
			a_link.appendChild(label_msg);
			div_item.appendChild(a_link);

			div_noticeList.appendChild(div_item);
		});

	}

	/*
	*
	* 通常営業日時
	*
	*/
	function Sub_SetHTML_kbn3(PI_JsonData){

		if(PI_JsonData.length == 0){
			document.getElementById("saleTime-week").style.display = "none";
			return;
		}else{
			document.getElementById("salesTime").style.display = "block";
		}

		var table = document.getElementById("table-week");

		for(let idx = 0; idx <= PI_JsonData.length - 1; idx++){

			let row = PI_JsonData[idx];
			
			// 一行目
			var tr_weekRowA = document.createElement("tr");

			var th_weekA = document.createElement("th");
			th_weekA.setAttribute("class", "week") ;

			if(row.日曜フラグ == 1){
				th_weekA.innerText = th_weekA.innerText + "日";
			}else{
				th_weekA.innerText = th_weekA.innerText + "　";
			}
			if(row.月曜フラグ == 1){
				th_weekA.innerText = th_weekA.innerText + "月";
			}else{
				th_weekA.innerText = th_weekA.innerText + "　";
			}
			if(row.火曜フラグ == 1){
				th_weekA.innerText = th_weekA.innerText + "火";
			}else{
				th_weekA.innerText = th_weekA.innerText + "　";
			}
			if(row.水曜フラグ == 1){
				th_weekA.innerText = th_weekA.innerText + "水";
			}else{
				th_weekA.innerText = th_weekA.innerText + "　";
			}
			if(row.木曜フラグ == 1){
				th_weekA.innerText = th_weekA.innerText + "木";
			}else{
				th_weekA.innerText = th_weekA.innerText + "　";
			}
			if(row.金曜フラグ == 1){
				th_weekA.innerText = th_weekA.innerText + "金";
			}else{
				th_weekA.innerText = th_weekA.innerText + "　";
			}
			if(row.土曜フラグ == 1){
				th_weekA.innerText = th_weekA.innerText + "土";
			}else{
				th_weekA.innerText = th_weekA.innerText + "　";
			}

			var th_timeA = document.createElement("th");
			th_timeA.setAttribute("class", "time") ;
			th_timeA.innerText = row.開始時分 + ' ~ ' + row.終了時分;

			tr_weekRowA.appendChild(th_weekA);
			tr_weekRowA.appendChild(th_timeA);

			// tableタグに追加
			table.appendChild(tr_weekRowA);
			
		}

	}

	/*
	*
	*不定休
	*
	*/
	function Sub_SetHTML_kbn4(PI_JsonData){

		if(PI_JsonData.length == 0){
			document.getElementById("saleTime-SpecialHoriday").style.display = "none";
			return;
		}else{
			document.getElementById("salesTime").style.display = "block";
		}

		var table = document.getElementById("table-SpecialHoriday");

		for(let idx1 = 0; idx1 <= PI_JsonData.length - 1; idx1++){

			let row = PI_JsonData[idx1];
			
			// 一行目
			var tr = document.createElement("tr");

			var th_day = document.createElement("th");
			th_day.setAttribute("class", "day") ;
			th_day.innerText = row.日付;

			var th_time = document.createElement("th");
			th_time.setAttribute("class", "specialTime") ;

			if(row.全日フラグ == 1){
				th_time.innerText = "全日";
			}else{
				th_time.innerText = row.開始時 + ':' + row.開始分 + " ~ " + row.終了時 + ':' + row.終了分;
			}

			tr.appendChild(th_day);
			tr.appendChild(th_time);

			// tableタグに追加
			table.appendChild(tr);
			
		}

	}

	/*
	*
	*不定営
	*
	*/
	function Sub_SetHTML_kbn5(PI_JsonData){

		if(PI_JsonData.length == 0){
			document.getElementById("saleTime-Special").style.display = "none";
			return;
		}else{
			document.getElementById("salesTime").style.display = "block";
		}

		var table = document.getElementById("table-Special");

		for(let idx1 = 0; idx1 <= PI_JsonData.length - 1; idx1++){

			let row = PI_JsonData[idx1];
			
			// 一行目
			var tr = document.createElement("tr");

			var th_day = document.createElement("th");
			th_day.setAttribute("class", "day") ;
			th_day.innerText = row.日付;

			var th_time = document.createElement("th");
			th_time.setAttribute("class", "specialTime") ;

			if(row.全日フラグ == 1){
				th_time.innerText = "全日";
			}else{
				th_time.innerText = row.開始時 + ':' + row.開始分 + " ~ " + row.終了時 + ':' + row.終了分;
			}

			tr.appendChild(th_day);
			tr.appendChild(th_time);

			// tableタグに追加
			table.appendChild(tr);
			
		}

	}

	/*
	*
	* リンクURL
	*
	*/
	function Sub_SetHTML_kbn6(PI_JsonData){

		if(PI_JsonData.length == 0){
			// 基本ページのみは表示しない
			document.getElementById("regPage").style.display = "none";
			return;
		}else{
			document.getElementById("link").style.display = "block";
		}

		var ul = document.getElementById("link-list");

		for(let idx1 = 0; idx1 <= PI_JsonData.length - 1; idx1++){

			let row = PI_JsonData[idx1];
			
			if(row.URL == "" || row.名称 == ""){
				return;
			}

			// 一行目
			var li = document.createElement("li");
			var a = document.createElement("a");

			a.setAttribute("href", row.URL) ;
			a.innerText = row.名称;

			li.appendChild(a);

			// tableタグに追加
			ul.appendChild(li);
			
		}


	}

	/*
	*
	* SNS
	*
	*/
	function Sub_SetHTML_kbn7(PI_JsonData){

		if(PI_JsonData.length == 0){
			document.getElementById("SNS").style.display = "none";
			return;
		}else{
			document.getElementById("link").style.display = "block";
		}

		var div_SNS = document.getElementById("SNS");

		for(let idx1 = 0; idx1 <= PI_JsonData.length - 1; idx1++){

			let row = PI_JsonData[idx1];
			
			var div = document.createElement("div");
			var a = document.createElement("a");
			var img = document.createElement("img");

			div.setAttribute("class", "SNS-icon") ;
			a.setAttribute("href", row.URL) ;
			img.setAttribute("src", row.imgパス) ;
			
			a.appendChild(img);
			div.appendChild(a);
			div_SNS.appendChild(div);
		}
	}

	/*
	*
	* 詳細画像メッセージ
	*
	*/
	function Sub_SetHTML_kbn8(PI_JsonData){

		if(PI_JsonData.length == 0){
			document.getElementById("detailsImg").style.display = "none";
			return;
		}else{
			document.getElementById("detailsImg").style.display = "block";
		}


		var div_inner = document.getElementById("detailsImg-inner");
		for(let idx = 0; idx <= PI_JsonData.length - 1 ; idx= idx + 2){

			var div_row = document.createElement("div");
			div_row.setAttribute("class", "detailsImg-row");

			var imgPath = PI_JsonData[idx].画像パス + '?<?php echo date("YmdHis");?>';
			var imgMsg = PI_JsonData[idx].画像メッセージ;
			if(imgPath == ""){
				// 画像ファイルパスがからの場合は表示しない
				// 画像あり、メッセージ無しはOK
				continue;
			}

			var div_left = document.createElement("div");
			div_left.setAttribute("class", "left");

			var div_img = document.createElement("div");
			var img_detailsImg = document.createElement("img");
			div_img.setAttribute("class", "img");
			img_detailsImg.src = imgPath;
			div_img.appendChild(img_detailsImg);

			if(imgMsg != ""){
				var div_text = document.createElement("div");
				var p_detailsMsg = document.createElement("p");
				div_text.setAttribute("class", "text");
				p_detailsMsg.innerText = imgMsg;
				div_text.appendChild(p_detailsMsg);
				div_img.appendChild(div_text) ;
			}

			div_left.appendChild(div_img) ;
			div_row.appendChild(div_left);


			if(PI_JsonData.length - 1 != idx){
					
				var imgPath = PI_JsonData[idx + 1].画像パス + '?<?php echo date("YmdHis");?>';
				var imgMsg = PI_JsonData[idx + 1].画像メッセージ;
				if(imgPath == ""){
					// 画像ファイルパスがからの場合は表示しない
					// 画像あり、メッセージ無しはOK
					continue;
				}

				var div_right = document.createElement("div");
				div_right.setAttribute("class", "right");

				var div_img = document.createElement("div");
				var img_detailsImg = document.createElement("img");
				div_img.setAttribute("class", "img");
				img_detailsImg.src = imgPath;
				div_img.appendChild(img_detailsImg);

				if(imgMsg != ""){
					var div_text = document.createElement("div");
					var p_detailsMsg = document.createElement("p");
					div_text.setAttribute("class", "text");
					p_detailsMsg.innerText = imgMsg;
					div_text.appendChild(p_detailsMsg);
					div_img.appendChild(div_text) ;
				}

				div_right.appendChild(div_img) ;
				div_row.appendChild(div_right);
			}

			div_inner.appendChild(div_row);

		}

	}

	/*
	*
	* 支払方法
	*
	*/
	function Sub_SetHTML_kbn9(PI_JsonData){

		if(PI_JsonData.length == 0){
			document.getElementById("payment").style.display = "none";
			return;
		}else{
			document.getElementById("payment").style.display = "block";
			document.getElementById("credit").style.display = "none";
			document.getElementById("barcode").style.display = "none";
		}

		var row = PI_JsonData[0];
		var ul = document.getElementById("credit-name");

		if(row.American_Express == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "American Express";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.Diners_Club == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "Diners Club";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.JCB == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "JCB";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.Mastercard == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "Mastercard";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.Visa == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "Visa";
			li.appendChild(p);
			ul.appendChild(li);
		}



		var ul = document.getElementById("barcode-name");

		if(row.au_PAY == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "au PAY";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.d払い == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "d払い";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.LINE_Pay == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "LINE Pay";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.PayPay == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "PayPay";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.メルペイ == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "メルペイ";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.楽天ペイ == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "楽天ペイ";
			li.appendChild(p);
			ul.appendChild(li);
		}

	}

	/*
	*
	* 支払方法
	*
	*/
	function Sub_SetHTML_kbn9(PI_JsonData){

		if(PI_JsonData.length == 0){
			document.getElementById("payment").style.display = "none";
			return;
		}else{
			document.getElementById("payment").style.display = "block";
			document.getElementById("credit").style.display = "none";
			document.getElementById("barcode").style.display = "none";
		}

		var row = PI_JsonData[0];
		var ul = document.getElementById("credit-name");

		if(row.American_Express == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "American Express";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.Diners_Club == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "Diners Club";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.JCB == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "JCB";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.Mastercard == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "Mastercard";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.Visa == "1"){
			document.getElementById("credit").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "Visa";
			li.appendChild(p);
			ul.appendChild(li);
		}



		var ul = document.getElementById("barcode-name");

		if(row.au_PAY == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "au PAY";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.d払い == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "d払い";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.LINE_Pay == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "LINE Pay";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.PayPay == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "PayPay";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.メルペイ == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "メルペイ";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.楽天ペイ == "1"){
			document.getElementById("barcode").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "楽天ペイ";
			li.appendChild(p);
			ul.appendChild(li);
		}

	}

	/*
	*
	* その他
	*
	*/
	function Sub_SetHTML_kbn10(PI_JsonData){

		if(PI_JsonData.length == 0 ){
			document.getElementById("other").style.display = "none";
			return;
		}else{
			document.getElementById("option").style.display = "none";
			document.getElementById("tags").style.display = "none";
		}

		var row = PI_JsonData[0];
		var ul = document.getElementById("option-name");

		if(row.喫煙可 == "1"){
			document.getElementById("other").style.display = "block";
			document.getElementById("option").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "喫煙可";
			li.appendChild(p);
			ul.appendChild(li);
		}
		if(row.駐車場有 == "1"){
			document.getElementById("other").style.display = "block";
			document.getElementById("option").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = "駐車場有";
			li.appendChild(p);
			ul.appendChild(li);
		}


		var ul = document.getElementById("tags-name");

		if(row.タグ.trim() != ""){
			document.getElementById("other").style.display = "block";
			document.getElementById("tags").style.display = "block";
			var li = document.createElement("li");
			var p = document.createElement("p");
			p.innerText = row.タグ.trim();
			li.appendChild(p);
			ul.appendChild(li);
		}
	}


</script>

<hr width='80%'>
	<div id="pageTitle"> 
		<label id="pageTitle-lbl"></label>
	</div> 
<hr width='80%'>

<div class="shopDetails">

	<?php /* 運営管理警告メッセージ */ ?>
	<div class="content-shopDetails" id="warning">
		<div class="inner" id="warning-inner">

		</div>
	</div>

	<?php /* サイト機能 */ ?>
	<div class="content-shopDetails" id="function">
		<div class="inner" id="function-inner">
			<ul>
				<li>
					<div class="function-icon" id="icon-fav"><a>
						<img id="favImg" src="./img/星_青.png" onClick="Set_Eve_favImg();"></img>
					</a></div>
				</li>
				<li>
					<div class="function-icon" id="icon-Connect"><a>
						<img src="./img/連絡.png" ></img>
					</a></div>
				</li>
				<li>
					<div class="function-icon" id="icon-reserva"><a>
						<img src="./img/カレンダー.png" ></img>
					</a></div>
				</li>
				<li>
					<div class="function-icon" id="icon-job"><a>
						<img src="./img/金袋.png" ></img>
					</a></div>
				</li>
			</ul>
		</div>
	</div>

	<?php /* トップ画像、店名 */ ?>
	<div class="content-shopDetails" id="top">
		<div class="img">
			<img id="topImg" alt="" src="./shop/<?php echo $_GET['shopID']; ?>/main/img/top.jpg?<?php echo date("YmdHis");?>" />
			<div class="text" id="catchText">
				<p id="catchMsg">メッセージ</p>
			</div>
		</div>
	</div>

	<?php /*　通知 */ ?>
	<div class="content-shopDetails" id="siteContent">
		<h1>お知らせ</h1>
		<div class="inner" id="siteContent-inner">
			<!--
			<table id="notice-table">

				<tr>
					<th class="clm-noticeKbn"></th>
					<th class="clm-time">通知期間</th>
					<th class="clm-msg">メッセージ</th>
					<th class="clm-linkBtn"></th>
				</tr>

			</table>
			-->
			<div id="notice-list">

			</div>

		</div>
	</div>

	<?php /* 詳細 */ ?>
	<div class="content-shopDetails" id="detailsImg">
		<div class="inner" id="detailsImg-inner">
		</div>
	</div>

	<?php /* 営業時間 */ ?>
	<div class="content-shopDetails" id="salesTime">
		<div class="inner" id="salesTime-inner">
			<div class="left">
				<div id="saleTime-week">
					<h2>通常営業時</h2>
					<table id="table-week">
						<tr>
							<th class="week">曜日</th>
							<th class="time">営業時間</th>
						</tr>

					</table>
				</div>
			</div>
			<div class="right">
				<div id="saleTime-Special">
					<h2>不定営日時</h2>
					<table id="table-Special">
						<tr>
							<th class="day">日付</th>
							<th class="specialTime">時間</th>
						</tr>

					</table>
				</div>
				<div id="saleTime-SpecialHoriday">
					<h2>不定休日時</h2>
					<table id="table-SpecialHoriday">
						<tr>
							<th class="day">日付</th>
							<th class="specialTime">時間</th>
						</tr>	
					</table>
				</div>
			</div>
		</div>
	</div>

	<?php /* 住所、googleMap */ ?>
	<div class="content-shopDetails" id="address">
		<h1>住所・連絡先</h1>
		<div class="inner" id="address-inner">
			<div class="left">
				<div id="text">
					<ul>
						<li id="postNo"></li>
						<li id="shopAddress_1"></li>
						<li id="shopAddress_2"></li>
						<li id="tel"></li>
						<li id="fax"></li>
						<li id="mail"></li>
					</ul>
				</div>
			</div>
			<div class="right">
				<div id="google-map">
					<iframe id="google-map-iframe" ></iframe>
				</div>
			</div>
		</div>
		
	</div>

	<?php /* 支払方法 */ ?>
	<div class="content-shopDetails" id="payment">
		<h1>利用可能な支払方法</h1>
		<div class="inner" id="payment-inner">

			<?php /* クレジット */ ?>
			<div class="left">
				<div id="credit">
					<h2>クレジット</h2>
					<ul id="credit-name">
					</ul>
				</div>
			</div>

			<?php /* バーコード */ ?>
			<div class="right">
				<div id="barcode">
					<h2>QR・バーコード決済</h2>
					<ul id="barcode-name">
					</ul>
				</div>
			</div>

		</div>
	</div>

	<?php /* その他 */ ?>
		<div class="content-shopDetails" id="other">
			<div class="inner" id="other-inner">

				<?php /* タグ */ ?>
				<div class="right">
					<div id="tags">
						<h2>タグ</h2>
						<ul id="tags-name">
						</ul>
					</div>
				</div>

				<?php /* その他 */ ?>
				<div class="left">
					<div id="option">
						<h2>その他</h2>
						<ul id="option-name">
						</ul>
					</div>
				</div>

			</div>
		</div>
	<?PHP /*  */ ?>

	<?php /* リンク登録、SNS登録URLリンク集める */ ?>
	<div class="content-shopDetails" id="link">
		<div class="inner" id="link-inner">
			<div class="left">
				<div id="regPage">
					<h2>リンク</h2>
					<ul id="link-list">
					</ul>
				</div>
			</div>

			<?php /* SNSリンク */ ?>
			<div class="right">
				<div id="SNS">
					<h2>SNS</h2>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// スクリプト実行
	pageScript_start();
</script>
