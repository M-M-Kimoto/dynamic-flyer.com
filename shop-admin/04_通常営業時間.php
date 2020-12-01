
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_Mst営業時間 = new MstSalesTime_Ctrl;
    $更新内容 = array();

    /* 配列へ */
      // フラグ項目はチェック意外全て０とする
      $weekName = "日曜フラグ";
      if (isset($_POST[$weekName]) == false){
        $_POST[$weekName] = '0';
      }elseif($_POST[$weekName] != '1'){
        $_POST[$weekName] = '0';
      }
      $weekName = "月曜フラグ";
      if (isset($_POST[$weekName]) == false){
        $_POST[$weekName] = '0';
      }elseif($_POST[$weekName] != '1'){
        $_POST[$weekName] = '0';
      }
      $weekName = "火曜フラグ";
      if (isset($_POST[$weekName]) == false){
        $_POST[$weekName] = '0';
      }elseif($_POST[$weekName] != '1'){
        $_POST[$weekName] = '0';
      }
      $weekName = "水曜フラグ";
      if (isset($_POST[$weekName]) == false){
        $_POST[$weekName] = '0';
      }elseif($_POST[$weekName] != '1'){
        $_POST[$weekName] = '0';
      }
      $weekName = "木曜フラグ";
      if (isset($_POST[$weekName]) == false){
        $_POST[$weekName] = '0';
      }elseif($_POST[$weekName] != '1'){
        $_POST[$weekName] = '0';
      }
      $weekName = "金曜フラグ";
      if (isset($_POST[$weekName]) == false){
        $_POST[$weekName] = '0';
      }elseif($_POST[$weekName] != '1'){
        $_POST[$weekName] = '0';
      }
      $weekName = "土曜フラグ";
      if (isset($_POST[$weekName]) == false){
        $_POST[$weekName] = '0';
      }elseif($_POST[$weekName] != '1'){
        $_POST[$weekName] = '0';
      }

      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        '日曜フラグ'=>$_POST['日曜フラグ'],
        '月曜フラグ'=>$_POST['月曜フラグ'],
        '火曜フラグ'=>$_POST['火曜フラグ'],
        '水曜フラグ'=>$_POST['水曜フラグ'],
        '木曜フラグ'=>$_POST['木曜フラグ'],
        '金曜フラグ'=>$_POST['金曜フラグ'],
        '土曜フラグ'=>$_POST['土曜フラグ'],
        '開始時'=>$_POST['開始時'],
        '開始分'=>$_POST['開始分'],
        '終了時'=>$_POST['終了時'],
        '終了分'=>$_POST['終了分']
      );

    /*  */

    /* エラーチェック */
      $res_check = $cls_Mst営業時間->check($更新内容);
      if ($res_check['status'] == 結果['問題あり']){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = $res_check["msg"];
      }

    /* */

    if($エラーコード != ERR種類['エラー無し']){
        // 引っ掛かった場合
        return array('エラーコード'=>ERR種類['エラーチェック'], 'エラーチェック結果'=>$エラーチェック結果);
    }

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();

        $return_dateUpdIns = array();
        $return_dateUpdIns = data_Update_or_Insert($cls_Mst営業時間, $更新内容);
        
        if($return_dateUpdIns['status'] == false){
          $cls_dbCtrl->rollback();
          return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
        }

        $cls_dbCtrl->commit();
        
      }catch ( Exception $ex ) {
        $cls_dbCtrl->rollback();
        return array('エラーコード'=>ERR種類['更新失敗'], 'エラーチェック結果'=>$エラーチェック結果);
      }
      // エラー無し
      return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);
    /*  */
  }

  function delete(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();
    
    $cls_Mst営業時間 = new MstSalesTime_Ctrl;
    
    /* トランザクション*/
    try{
      global $cls_dbCtrl;
      $cls_dbCtrl->begin_tran();
      
      for ($seq = 1; $seq <= $_POST['reg_MaxSeq']; $seq++){

        if (isset($_POST['reg_削除'.$seq]) == false){
          continue;
        }elseif($_POST['reg_削除'.$seq] != '1'){
          continue;
        }

        $削除内容 = array(
          'ショップID'=>$_SESSION['shop_id'],
          '開始時'=>$_POST['reg_開始時'.$seq],
          '開始分'=>$_POST['reg_開始分'.$seq],
          '終了時'=>$_POST['reg_終了時'.$seq],
          '終了分'=>$_POST['reg_終了分'.$seq]
        );

        // エラー無しで更新処理実行
        $sql_del = $cls_Mst営業時間->delete($削除内容);
        $res_delete = $cls_dbCtrl->delete($sql_del);
        if($res_delete['status'] == false){
          $エラーコード = ERR種類['削除失敗'];
        }
      }

      if($エラーコード != ERR種類['エラー無し']){
        $cls_dbCtrl->rollback();
        return array('エラーコード'=>$エラーコード, 'エラーチェック結果'=>$エラーチェック結果);
      }else{
        $cls_dbCtrl->commit();
      }

    }catch ( Exception $ex ) {
      $cls_dbCtrl->rollback();
      return array('エラーコード'=>$エラーコード, 'エラーチェック結果'=>$エラーチェック結果);
    }
    // エラー無し
    return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);
  /*  */
  }

?>

<?php

   
  
  /* 変数初期化 */
  $エラーコード = ERR種類['エラー無し'];
  $エラーチェック結果 = array(); 

  $モード = モードコード['入力'];
  
  if( isset($_POST['btn_Update']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_Update'];
  }elseif( isset($_POST['btn_back']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_back'];
  }elseif( isset($_POST['btn_delete']) ) {
    // エラーチェック処理に飛ばす
    $モード =  $_POST['btn_delete'];
  }

  if( $モード == モードコード['戻る'] ) {
    $_POST['menu-btn'] = ショップ管理機能['メニュー']['NO'];
    header("Location: index.php");
    exit ;

  
  }elseif ($モード == モードコード['削除']){

    $return = delete();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

  }elseif ($モード == モードコード['更新']){
    
    // 更新処理実行
    $return = update();
    $エラーコード = $return['エラーコード'];
    $エラーチェック結果 = $return['エラーチェック結果'];

  }

  global $cls_dbCtrl;

  /* 最後に必ずマスタ最新値を取得し直す */
    $cls_Mst営業時間 = new MstSalesTime_Ctrl;
    $ary_mst営業時間情報 = array(); // ログインショップIDのmst_ショップのレコード
 
    $Mst営業時間_検索条件= array(
      'ショップID'=>$index['ショップID']
    );

    // ショップIDを条件に検索
    $sql_select = $cls_Mst営業時間->select($Mst営業時間_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ary_mst営業時間情報 = $res_select["rows"];
    }
  /*  */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();
  
  }
  clearstatcache(); 
  
?>

<link rel="stylesheet" href="./stylesheets/<?php echo $index['Me']['ファイル名']; ?>.css">

<?php
  // メッセージ
  $メッセージ = '';
  if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['更新']) {
    $メッセージ = '更新しました。';
  }elseif ($エラーコード == ERR種類['エラーチェック']) {
      $メッセージ = ERRメッセージ[$エラーコード];
  }elseif ($エラーコード == ERR種類['更新失敗']) {
    $メッセージ = ERRメッセージ[$エラーコード];
  }
?>
<h4 id="top-msg"><?php echo $メッセージ; ?></h4>

<h4>登録</h4>
<div class="content-item">
<form class="insert" action="" method="post">
  <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />
  
  <div class="item" >
    <label class="itemLabel-primary" >曜日</label>
    <?php
      $日曜checked = "";
      $月曜checked = "";
      $火曜checked = "";
      $水曜checked = "";
      $木曜checked = "";
      $金曜checked = "";
      $土曜checked = "";
      if($エラーコード != ERR種類['エラー無し']){
        // エラーがあった場合は入力内容を引き継ぐ
        if($_POST["日曜フラグ"] == 1){
          $日曜checked = "checked";
        }
        if($_POST["月曜フラグ"] == 1){
          $月曜checked = "checked";
        }
        if($_POST["火曜フラグ"] == 1){
          $火曜checked = "checked";
        }
        if($_POST["水曜フラグ"] == 1){
          $水曜checked = "checked";
        }
        if($_POST["木曜フラグ"] == 1){
          $木曜checked = "checked";
        }
        if($_POST["金曜フラグ"] == 1){
          $金曜checked = "checked";
        }
        if($_POST["土曜フラグ"] == 1){
          $土曜checked = "checked";
        }
      }
    ?>
    <input type="checkbox" class="checkbox" name="日曜フラグ" value="1" <?php echo $日曜checked; ?>><label for="日曜フラグ">日</label>
    <input type="checkbox" class="checkbox" name="月曜フラグ" value="1" <?php echo $月曜checked; ?>><label for="月曜フラグ">月</label>
    <input type="checkbox" class="checkbox" name="火曜フラグ" value="1" <?php echo $火曜checked; ?>><label for="火曜フラグ">火</label>
    <input type="checkbox" class="checkbox" name="水曜フラグ" value="1" <?php echo $水曜checked; ?>><label for="水曜フラグ">水</label>
    <input type="checkbox" class="checkbox" name="木曜フラグ" value="1" <?php echo $木曜checked; ?>><label for="木曜フラグ">木</label>
    <input type="checkbox" class="checkbox" name="金曜フラグ" value="1" <?php echo $金曜checked; ?>><label for="金曜フラグ">金</label>
    <input type="checkbox" class="checkbox" name="土曜フラグ" value="1" <?php echo $土曜checked; ?>><label for="土曜フラグ">土</label>
    <br><label class="err">
      <?php
        if (array_key_exists('日曜フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['日曜フラグ'];
        }
        if (array_key_exists('月曜フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['月曜フラグ'];
        }
        if (array_key_exists('火曜フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['火曜フラグ'];
        }
        if (array_key_exists('水曜フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['水曜フラグ'];
        }
        if (array_key_exists('木曜フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['木曜フラグ'];
        }
        if (array_key_exists('金曜フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['金曜フラグ'];
        }
        if (array_key_exists('土曜フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['土曜フラグ'];
        }
        if (array_key_exists('曜日フラグ', $エラーチェック結果)){
          echo $エラーチェック結果['曜日フラグ'];
        }
      ?>
    </label>
  </div>

  <div class="item" >
        <label class="itemLabel-primary" for="開始時" >開始時間</label>
        <?php
          $開始時 = '10'; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $開始時 = $_POST['開始時'];
          }
          $開始分 = '00'; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $開始分 = $_POST['開始分'];
          }
        ?>
        <input type="number" class="number-time" id="開始時" name="開始時" min="0" max="23" value="<?php echo $開始時; ?>">
        <label class="tillde">：</label>
        <input type="number" class="number-time" id="開始分" name="開始分" min="0" max="59" value="<?php echo $開始分; ?>">
      
        <br><label class="err">
          <?php
            if (array_key_exists('開始時', $エラーチェック結果)){
              echo '開始時：'.$エラーチェック結果['開始時'];
            }
            if (array_key_exists('開始分', $エラーチェック結果)){
              echo '開始分：'.$エラーチェック結果['開始分'];
            }
          ?>
        </label>
      
    
  </div>

  <div class="item" >
        <label class="itemLabel-primary" for="終了時" >終了時間</label>
        <?php
          $終了時 = '17'; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $終了時 = $_POST['終了時'];
          }
          $終了分 = '00'; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            $終了分 = $_POST['終了分'];
          }
        ?>
        <input type="number" class="number-time" id="終了時" name="終了時" min="0" max="29" value="<?php echo $終了時; ?>">
        <label class="tillde">：</label>
        <input type="number" class="number-time" id="終了分" name="終了分" min="0" max="59" value="<?php echo $終了分; ?>">
        <br><label class="explain">29:59まで入力可能です。</label>
        <br><label class="err">
          <?php
            if (array_key_exists('終了時', $エラーチェック結果)){
              echo '終了時：'.$エラーチェック結果['終了時'];
            }
            if (array_key_exists('終了分', $エラーチェック結果)){
              echo '終了分：'.$エラーチェック結果['終了分'];
            }
          ?>
        </label>
      
    
  </div>

  <div class="item" id="upd-bak-btn">
    <button id="btn_Update" name="btn_Update" value="<?php echo モードコード['更新']; ?>">更新</button>
    <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
  </div>

  <hr>
  <label class="title-lbl" id="sub-title-lbl">設定済み一覧</label>

  <?php

    // メッセージ
    $メッセージ = '';
    if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['削除']) {
      $メッセージ = '削除しました。';
    }elseif ($エラーコード == ERR種類['削除失敗']) {
      $メッセージ = ERRメッセージ[$エラーコード];
    }elseif ($エラーコード == ERR種類['マスタ情報取得失敗']) {
      $メッセージ = ERRメッセージ[$エラーコード];
    }
  ?>
  <br><br><label id="top-msg"><?php echo $メッセージ; ?></label>

  <form class="insert" action="" method="post">
    <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />
    <input type="hidden" name="reg_MaxSeq" value="<?php echo count($ary_mst営業時間情報); ?>" />
      
    <?php
      if (count($ary_mst営業時間情報) == 0){
        //処理日以降の不定休設定はない
        echo '<h4>有効な通知内容はありません。</h4>';
      }
    ?>
    <table id="reg-table">
      <tr>
        <th class="clm-week">曜日</th>
        <th class="clm-open">営業開始時間</th>
        <th class="clm-close">営業終了時間</th>
        <th class="clm-delBtn">削除</th>
      </tr>
      <?php
        for($seq = 1; $seq <= count($ary_mst営業時間情報); $seq++){
          $row = $ary_mst営業時間情報[$seq - 1];
      ?>
      <tr>
        <td class="clm-week">
          <?php
            $曜日名 = "";      
            if($row['日曜フラグ'] == 1){
              $曜日名 = $曜日名 . "日";
            }else{
              $曜日名 = $曜日名 . "　";
            }
            if($row['月曜フラグ'] == 1){
              $曜日名 = $曜日名 . "月";
            }else{
              $曜日名 = $曜日名 . "　";
            }
            if($row['火曜フラグ'] == 1){
              $曜日名 = $曜日名 . "火";
            }else{
              $曜日名 = $曜日名 . "　";
            }
            if($row['水曜フラグ'] == 1){
              $曜日名 = $曜日名 . "水";
            }else{
              $曜日名 = $曜日名 . "　";
            }
            if($row['木曜フラグ'] == 1){
              $曜日名 = $曜日名 . "木";
            }else{
              $曜日名 = $曜日名 . "　";
            }
            if($row['金曜フラグ'] == 1){
              $曜日名 = $曜日名 . "金";
            }else{
              $曜日名 = $曜日名 . "　";
            }
            if($row['土曜フラグ'] == 1){
              $曜日名 = $曜日名 . "土";
            }else{
              $曜日名 = $曜日名 . "　";
            }
            echo $曜日名;
          ?>
        </td>
        <td class="clm-open">
          <?php
            echo str_pad($row['開始時'], 2,0,STR_PAD_LEFT).'：'.str_pad($row['開始分'], 2,0,STR_PAD_LEFT);
          ?>
        </td>
        <td class="clm-close">
          <?php
            echo str_pad($row['終了時'], 2,0,STR_PAD_LEFT).'：'.str_pad($row['終了分'], 2,0,STR_PAD_LEFT);
          ?>
        </td>
        <td class="clm-delBtn">
          <input type="checkbox" class="reg_checkbox" id="reg_checkbox<?php echo $seq; ?>" name="reg_削除<?php echo $seq; ?>" value="1" />
        </td>
      </tr>
      <input type="hidden" name="reg_開始時<?php echo $seq; ?>" value="<?php echo $row['開始時']; ?>" />
      <input type="hidden" name="reg_開始分<?php echo $seq; ?>" value="<?php echo $row['開始分']; ?>" />
      <input type="hidden" name="reg_終了時<?php echo $seq; ?>" value="<?php echo $row['終了時']; ?>" />
      <input type="hidden" name="reg_終了分<?php echo $seq; ?>" value="<?php echo $row['終了分']; ?>" />
      <?php } ?>
    </table>

    <div class="item">
      <button id="btn_delete" name="btn_delete" value="<?php echo モードコード['削除']; ?>">削除</button>
    </div>
  </form>
</div>