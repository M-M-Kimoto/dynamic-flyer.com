
/*
*短いタップでの処理
*
*/
function Sub_SingleTap(PI_URL, PI_NewTabFlg){

    if(!PI_URL){
        return;
    }

    if(PI_NewTabFlg == true){
        window_A = window.open(PI_URL);
    }else{
        location.href=PI_URL;
    }
}

/*
*ミドルタップでの処理
*/
function Sub_middleTap(PI_URL, PI_NewTabFlg){
    // 入り口は違うが、することは同じ
    Sub_SingleTap(PI_URL, PI_NewTabFlg);
}

/*
*ロングタップでの処理
*/
function Sub_longTap(PI_UserID, PI_ShopID, PI_GetsUserID, PI_DelFlg){

    if(PI_UserID == PI_GetsUserID){
        alert('お気に入り機能を使用するにはログインする必要があります。');
        return ;
    }

    Sub_regFav(PI_UserID, PI_ShopID, PI_DelFlg);
    
}


/*
* ショップお気に入り登録更新処理
*/
function Sub_regFav(PI_UserID, PI_shopID, PI_DelFlg){

    if(!PI_shopID){
        return;
    }

    var delFlg = '';
    if(PI_DelFlg != 1){
        delFlg = 0;
    }else{
        delFlg = PI_DelFlg;
    }

    //画面をロックする
    screenLock();
    $.ajax({
        type: 'post',
        url: './bat_ユーザお気に入り登録処理.php',
        data: {"userID": PI_UserID, "shopID":PI_shopID, "delFlg":delFlg},
        success : function(res){
            console.log("ajax通信に成功しました");
            console.log(res);
            if(PI_DelFlg == true) {
                alert('お気に入りから外しました。');
            }else{
                alert('お気に入り登録しました！\n画面を更新するとお気に入りユーザ限定の情報も表示されます。');
            }
            screenUnLock();

        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log("ajax通信に失敗しました");
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
            alert('お気に入り更新処理に失敗しました。\n時間を置いてからもう一度お願いします。');
            screenUnLock();
        }
    });
}
