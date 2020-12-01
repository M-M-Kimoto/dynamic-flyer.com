<?php


/*
エラーチェック
true：問題なし
false：問題あり
*/
define('結果', array('問題なし'=>true, '問題あり'=>false)); 


define('エラーメッセージ', array('chk_charSize'=>'文字数が長過ぎます。', 
                                 'chk_less'=>'有効範囲未満の値です。',
                                 'chk_over'=>'有効範囲より大きい値です。',
                                 'chk_while'=>'指定範囲外です。',
                                 'chk_duplicate'=>'入力内容が重複しています。',
                                 'chk_nothing'=>'未入力です。',
                                 'check_flg'=>'フラグ項目に想定外の値が入力されています。',
                                 'array_key_exists'=>'値がありません。',
                                 'chk_change_Datetime'=>'入力値が日時ではありません。',
                                 'chk_returnCode_overCount'=>'改行数が多すぎます。'
)); 

// 必須項目
function check_primaryItem($PI_key, $PI_ary, $PI_max, $PO_ary){

    if (array_key_exists($PI_key, $PI_ary) == false){
        // 配列に存在しない
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['array_key_exists'];
    }

    $val = $PI_ary[$PI_key];

    if ($val == ''){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['chk_nothing'];
    }elseif(chk_charSize($val, $PI_max) == false){
        // 字数
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['chk_charSize'];
    }

    return $PO_ary;
}
// 項目（パターンマッチあり）
function check_primaryItem_match($PI_key, $PI_ary, $PI_max, $PI_matchWord, $PO_ary){

    if (array_key_exists($PI_key, $PI_ary) == false){
        // 配列に存在しない
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['array_key_exists'];
    }

    $val = $PI_ary[$PI_key];

    if ($val == ''){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['chk_nothing'];
    }elseif(chk_charSize($val, $PI_max) == false){
        // 字数
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['chk_charSize'];
    }elseif (preg_match($PI_matchWord,  $val) == false) {
      //パターンに一致しない
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg'][$PI_key] = '入力不可文字を検知しました。';
    }

    return $PO_ary;
}


// 項目
function check_nomalItem($PI_key, $PI_ary, $PI_max, $PO_ary){

    if (array_key_exists($PI_key, $PI_ary) == false){
        // 配列に存在しない
        return $PO_ary;
    }

    $val = $PI_ary[$PI_key];
    
    if($val == ""){
        // 入力無しで処理終了
        return $PO_ary;
    }if(chk_charSize($val, $PI_max) == false){
        // 字数
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['chk_charSize'];
    }

    return $PO_ary;
}

// 項目（パターンマッチあり）
function check_nomalItem_match($PI_key, $PI_ary, $PI_max, $PI_matchWord, $PO_ary){

    if (array_key_exists($PI_key, $PI_ary) == false){
        // 配列に存在しない
        return $PO_ary;
    }

    $val = $PI_ary[$PI_key];

    if($val == ""){
        // 入力無しで処理終了
        return $PO_ary;
    }elseif(chk_charSize($val, $PI_max) == false){
        // 字数
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['chk_charSize'];
    }elseif (preg_match($PI_matchWord,  $val) == false) {
      //パターンに一致しない
      $PO_ary['status'] = 結果['問題あり'];
      $PO_ary['msg'][$PI_key] = '入力不可文字を検知しました。';
    }

    return $PO_ary;
}

function check_flg($PI_key, $PI_ary, $PO_ary ){

    if (array_key_exists($PI_key, $PI_ary) == false){
        // 配列に存在しない
        return $PO_ary;
    }

    $val = $PI_ary[$PI_key];

    if($val != 0 && $val != 1){
        $PO_ary['status'] = 結果['問題あり'];
        $PO_ary['msg'][$PI_key] = エラーメッセージ['check_flg'];
    }

    return $PO_ary;

}

// 文字数、けた溢れチェック
function chk_charSize($PI_str, $PI_MaxSize){

    if(mb_strlen($PI_str) > $PI_MaxSize){
        // サイズオーバー
        return 結果['問題あり'];
    }
    return 結果['問題なし'];
}

// 未満チェク
function chk_less($PI_str, $PI_min){

    if($PI_str < $PI_min){
        // 未満
        return 結果['問題あり'];
    }
    return 結果['問題なし'];
}

// より上チェク
function chk_over($PI_str, $PI_max){

    if($PI_max < $PI_str){
        // 未満
        return 結果['問題あり'];
    }
    return 結果['問題なし'];
}

// 範囲チェック
function chk_while($PI_str, $PI_min ,$PI_max){

    if($PI_str < $PI_min){
        // 未満
        return 結果['問題あり'];
    }elseif($PI_max < $PI_str){
        // より大きい
        return 結果['問題あり'];
    }
    return 結果['問題なし'];
}

// 重複チェック
function chk_duplicate($PI_str, $PI_ary){

    for($seq=1; $seq <= count($PI_ary); $seq++){
        $val = $PI_ary[$seq-1];
        if($PI_str == $val){
            // 未満
            return 結果['問題あり'];
        }
    }
    return 結果['問題なし'];
}

/*
型変換：DateTime
*/
function chk_change_Datetime($PI_key, $PI_ary, $PO_ary){

    try {
        $val = new DateTime($PI_ary[$PI_key]);
    } catch (Exception $e) {
        return 結果['問題あり'];
    }
    return 結果['問題なし'];
}


?>