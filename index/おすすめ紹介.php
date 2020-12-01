<?php

	// 基本情報ページ専用のPHPファイル
	define('ショップ情報取得処理', comフォルダ . 'bat_おすすめ情報取得処理.php');

	clearstatcache(); 
?>

<script type="text/javascript">

	// フラグ true:処理中 false:待機　※0は使わない
	var procFlg_ShopInfo = ['', true, true, true, true];


	function pageScript_start(){

		document.getElementById("link").style.display = "none";

		// スタート直後にロックする
    	screenLock();

		// おすすめ紹介設定
		Sub_Get_ShopInfo(1);

		// ショップ情報
		Sub_Get_ShopInfo(2);

		// リンク
		Sub_Get_ShopInfo(3);

		// SNS
		Sub_Get_ShopInfo(4);

	}

	// ajaxを使用したショップ情報取得処理
	function Sub_Get_ShopInfo(PI_kbn){
		procFlg_ShopInfo = true;
		$.ajax({
			type: 'post',
			url: '<?php echo ショップ情報取得処理 ?>',
			data: {"kbn":PI_kbn , "shopID":"<?php echo $_GET['shopID']; ?>"},
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
		for (let idx = 1; idx <= 4; idx++) {
			if(procFlg_ShopInfo[idx] == true){
				return ;
			}
		}
		// trueが全てなくなったのでロック解除
		screenUnLock();
	}

	/*
	*
	*おすすめ設定
	*
	*/
	function Sub_SetHTML_kbn1(PI_JsonData){

		var ID_name = '';
		var row = PI_JsonData[0];

		document.getElementById("pageTitle-lbl").innerText = row.キャッチコピー;

		/*
		*
		*トップ画像、キャッチコピー
		*
		*/
		let 商品名 = row.商品名.trimEnd();
		document.getElementById("catchMsg").innerText = 商品名;
		if(商品名 == ""){
			document.getElementById("catchText").style.display = "none";
		}else{
			document.getElementById("catchText").style.display = "block";
		}

		/*
		*
		* 詳細メッセージ
		*
		*/
		let 詳細 = row.詳細.trimEnd();
		if(詳細 == ""){
			document.getElementById("details").style.display = "none";
		}else{
			document.getElementById("details").style.display = "block";
			document.getElementById("detailsMsg").innerText = 詳細;
		}

		/*
		*
		* おすすめ情報
		*
		*/
		var recomInfo_list = document.getElementById("recomInfo-list");
		var tr = "";
		var th_item = "";
		var th_val = "";

		if(row.販売開始日.trimEnd() != ""){
			tr = document.createElement("tr");
			th_item = document.createElement("th");
			th_item.setAttribute("class", "clm-item");
			th_item.innerText = "販売期間";

			th_val = document.createElement("th");
			th_val.setAttribute("class", "clm-val");

			th_val.innerText = row.販売開始日.trimEnd();
			if(row.販売終了日　!= null){
				if(row.販売終了日.trimEnd() != ""){
					th_val.innerText = th_val.innerText + " 〜 " + row.販売終了日.trimEnd();
				}
			}
			tr.appendChild(th_item);
			tr.appendChild(th_val);
			recomInfo_list.appendChild(tr);

		}

		if(row.数量制限区分.trimEnd() != "" & row.数量制限区分.trimEnd() != "0"){
			tr = document.createElement("tr");

			th_item = document.createElement("th");
			th_item.setAttribute("class", "clm-item");
			if(row.数量制限区分.trimEnd() == "2"){
				th_item.innerText = "販売数量(日)";
			}else{
				th_item.innerText = "販売数量";
			}
			th_val = document.createElement("th");
			th_val.setAttribute("class", "clm-val");
			th_val.innerText = row.販売数量.trimEnd();

			tr.appendChild(th_item);
			tr.appendChild(th_val);
			recomInfo_list.appendChild(tr);

			if(row.残数 != null){
				if(row.残数.trimEnd() != ""){

					tr = document.createElement("tr");

					th_item = document.createElement("th");
					th_item.setAttribute("class", "clm-item");
					th_item.innerText = "残数";

					th_val = document.createElement("th");
					th_val.setAttribute("class", "clm-val");
					th_val.innerText = row.残数.trimEnd();

					tr.appendChild(th_item);
					tr.appendChild(th_val);
					recomInfo_list.appendChild(tr);
				}
			}

		}

		if(row.値段 != null){

			tr = document.createElement("tr");

			th_item = document.createElement("th");
			th_item.setAttribute("class", "clm-item");
			th_item.innerText = "値段";

			th_val = document.createElement("th");
			th_val.setAttribute("class", "clm-val");
			th_val.innerText = row.値段.trimEnd();

			tr.appendChild(th_item);
			tr.appendChild(th_val);
			recomInfo_list.appendChild(tr);

		}
		
	}

	/*
	*
	*ショップ基本情報
	*
	*/
	function Sub_SetHTML_kbn2(PI_JsonData){

		var row = PI_JsonData[0];

		var mstInfo = document.getElementById("mstInfo-list");
		var tr = "";
		var th_item = "";
		var th_val = "";


		if(row.正式名称.trimEnd() != ""){
			tr = document.createElement("tr");
			th_item = document.createElement("th");
			th_item.setAttribute("class", "clm-item");
			th_item.innerText = "店名";

			th_val = document.createElement("th");
			th_val.setAttribute("class", "clm-val");
			var a = document.createElement("a");
			a.setAttribute("href", "https://" + document.domain + "/?shopID=<?php echo $_GET['shopID']; ?>&page=ショップ基本情報") ;
			a.innerText = row.正式名称.trimEnd();
			th_val.appendChild(a);

			tr.appendChild(th_item);
			tr.appendChild(th_val);
			mstInfo.appendChild(tr);
		}

		document.getElementById("google-map").style.display = "none";
		if(row.市区町村.trimEnd() != "" & row.町名番地.trimEnd() != ""){
			tr = document.createElement("tr");
			th_item = document.createElement("th");
			th_item.setAttribute("class", "clm-item");
			th_item.innerText = "住所";

			th_val = document.createElement("th");
			th_val.setAttribute("class", "clm-val");

			if(row.建物等.trimEnd() != ""){
				th_val.innerText = row.住所.trimEnd() + " " + row.建物等.trimEnd();
			}else{
				th_val.innerText = row.住所.trimEnd();
			}

			tr.appendChild(th_item);
			tr.appendChild(th_val);
			mstInfo.appendChild(tr);

			// google-map
			document.getElementById("google-map").style.display = "block";
			document.getElementById("google-map-iframe").src = "https://maps.google.co.jp/maps?output=embed&q=" + row.住所 + " " + row.建物等;
		
		}
	}

	/*
	*
	* リンク
	*
	*/
	function Sub_SetHTML_kbn3(PI_JsonData){

		if(PI_JsonData.length == 0){
			// 基本ページのみは表示しない
			document.getElementById("link").style.display = "none";
			return;
		}else{
			document.getElementById("link").style.display = "block";
		}

		// ショップ基本情報ページへのリンク
		var ul = document.getElementById("link-list");
		var li = document.createElement("li");
		var a = document.createElement("a");
		
		for(let idx1 = 0; idx1 <= PI_JsonData.length - 1; idx1++){

			let row = PI_JsonData[idx1];
			
			if(row.URL == "" || row.名称 == ""){
				return;
			}

			// 一行目
			li = document.createElement("li");
			a = document.createElement("a");

			a.setAttribute("href", row.URL) ;
			a.innerText = row.名称;

			li.appendChild(a);

			// tableタグに追加
			ul.appendChild(li);
			
		}

	}

	/*
	*
	*　SNS
	*
	*/
	function Sub_SetHTML_kbn4(PI_JsonData){

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

</script>

<hr width='80%'>
	<div id="pageTitle"> 
		<label id="pageTitle-lbl"></label>
	</div> 
<hr width='80%'>

<div class="shopDetails">

	<?php /* トップ画像、店名 */ ?>
	<div class="content-shopDetails" id="top">
		<div class="img">
			<img id="topImg" alt="" src="./shop/<?php echo $_GET['shopID']; ?>/recommended/img/top.jpg?<?php echo date("YmdHis");?>" />
			<div class="text" id="catchText">
				<p id="catchMsg"></p>
			</div>
		</div>
	</div>

	<?php /* 詳細メッセージ */ ?>
	<div class="content-shopDetails" id="details">
		<h1>詳細</h1>
		<div class="inner" id="details-inner">
			<p id="detailsMsg"></p>
		</div>
	</div>

	<?php /* 詳細情報 */ ?>
	<div class="content-shopDetails" id="info">
		<div class="inner" id="info-inner">

			<?php /* おすすめ情報 */ ?>
			<div class="left">
				<div id="recomInfo">
					<h2>おすすめ商品情報</h2>
					<table id="recomInfo-list">
					</table>
				</div>
				<div id="mstInfo">
					<h2>ショップ情報</h2>
					<table id="mstInfo-list">
					</table>
				</div>
			</div>

			<?php /* ショップマスタ情報 */ ?>
			<div class="right">
				<div id="google-map">
					<iframe id="google-map-iframe" ></iframe>
				</div>
			</div>
		</div>
	</div>

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
