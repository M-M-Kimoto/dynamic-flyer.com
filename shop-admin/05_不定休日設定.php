
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_Tra不定休 = new TraHoliday_Ctrl;
    $更新内容 = array();

    /* 配列へ */
      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        '日付'=>$_POST['日付'],
        '全日フラグ'=>$_POST['全日フラグ']
      );

      if($更新内容['全日フラグ'] != '1'){
        $更新内容['開始時'] = str_pad($_POST['開始時'], 2,0,STR_PAD_LEFT);
        $更新内容['開始分'] = str_pad($_POST['開始分'], 2,0,STR_PAD_LEFT);
        $更新内容['終了時'] = str_pad($_POST['終了時'], 2,0,STR_PAD_LEFT);
        $更新内容['終了分'] = str_pad($_POST['終了分'], 2,0,STR_PAD_LEFT);
      }else{
        $更新内容['開始時'] = '00';
        $更新内容['開始分'] = '00';
        $更新内容['終了時'] = '29';
        $更新内容['終了分'] = '59';
      }


    /*  */

    /* エラーチェック */
      $res_check = $cls_Tra不定休->check($更新内容);
      if ($res_check['status'] == false){
        $エラーコード = ERR種類['更新失敗'];
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
        $return_dateUpdIns = data_Update_or_Insert($cls_Tra不定休, $更新内容);
        
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
    
    $cls_Tra不定休 = new TraHoliday_Ctrl;

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
            '日付'=>$_POST['reg_日付'.$seq],
            '開始時'=>$_POST['reg_開始時'.$seq],
            '開始分'=>$_POST['reg_開始分'.$seq],
            '終了時'=>$_POST['reg_終了時'.$seq],
            '終了分'=>$_POST['reg_終了分'.$seq]
          );
    
          // エラー無しで更新処理実行
          $sql_del = $cls_Tra不定休->delete($削除内容);
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
    /*  */

    // エラー無し
    return array('エラーコード'=>ERR種類['エラー無し'], 'エラーチェック結果'=>$エラーチェック結果);

  }

?>

<?php

   
  
  /* 変数初期化 */
  $エラーコード = ERR種類['エラー無し'];
  $モード = モードコード['入力'];

  $Tra不定休_更新情報 = array(); // sqlを作るための引数用
  $エラーチェック結果 = array(); // エラーチェックに引っかかった項目名をキーにエラーメッセージは入った連想配列

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


  /* 最後に必ずマスタ最新値を取得し直す */
    $cls_Tra不定休 = new TraHoliday_Ctrl;
    $Tra不定休_検索条件 = array(); // sqlを作るための引数用
    $ary_不定休 = array(); // ログインショップIDのレコード

    $Tra不定休_検索条件= array(
      'ショップID'=>$index['ショップID']
    );

    // ショップIDを条件に検索
    $Sql_不定休fromNow = $cls_Tra不定休->select_fromNow($Tra不定休_検索条件);
    $res_select = $cls_dbCtrl->select($Sql_不定休fromNow);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];
    }else{
      // 取得レコードを変数へ
      $ary_不定休 = $res_select["rows"];
    }
  /*  */

  /* mst_曜日取得処理 */
    $cls_Mst曜日 = new MstWeek_Ctrl;
    $Mst曜日_検索条件 = array(); // sqlを作るための引数用
    $ary_曜日 = array(); // ログインショップIDのmst_ショップのレコード

    // ショップIDを条件に検索
    $sql_select = $cls_Mst曜日->select($Mst曜日_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ary_曜日 = $res_select["rows"];
      
    }
  /*  */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();

  }
  clearstatcache(); 

?>

<script language="javascript" type="text/javascript">

  function kindChange(PI_val) {

    if(PI_val == 0){
      document.getElementById( "開始時間タイトルlbl").className = 'itemLabel-primary';
      document.getElementById( "終了時間タイトルlbl").className = 'itemLabel-primary';

      document.getElementById( "開始時").readOnly = false;
      document.getElementById( "開始分").readOnly = false;
      document.getElementById( "終了時").readOnly = false;
      document.getElementById( "終了分").readOnly = false;

      document.getElementById( "開始時").value = '10';
      document.getElementById( "開始分").value = '00';
      document.getElementById( "終了時").value = '17';
      document.getElementById( "終了分").value = '00';

    }else{
      document.getElementById( "開始時間タイトルlbl").className = 'itemLabel-noedit';
      document.getElementById( "終了時間タイトルlbl").className = 'itemLabel-noedit';

      document.getElementById( "開始時").readOnly = true;
      document.getElementById( "開始分").readOnly = true;
      document.getElementById( "終了時").readOnly = true;
      document.getElementById( "終了分").readOnly = true;


      document.getElementById( "開始時").value = '--';
      document.getElementById( "開始分").value = '--';
      document.getElementById( "終了時").value = '--';
      document.getElementById( "終了分").value = '--';

    }

  }

</script>

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
      <label class="itemLabel-primary" for="日付" >日付</label>
      <?php
        $日付 = ''; //初期値
        if($エラーコード != ERR種類['エラー無し']){
          $日付 = $_POST['日付'];
        }
      ?>
      <input type="date" class="date" id="date" name="日付" value="<?php echo $日付; ?>">
      <br><label class="err">
        <?php
          if (array_key_exists('日付', $エラーチェック結果)){
            echo $エラーチェック結果['日付'];
          }
        ?>
      </label>  
    </div>

    <div class="item" >
      <label class="itemLabel-primary" for="区分" >区分</label>
      <div id="kinds">
        <?php
          $全日 = 'checked'; //初期値
          $一部 = ''; //初期値
          if($エラーコード != ERR種類['エラー無し']){
            if($_POST['全日フラグ'] == '0'){
              $全日 = '';
              $一部 = 'checked';
            }
          }
        ?>
        <div class="kind">
          <input type="radio" class="radio" id="全日フラグ1" name="全日フラグ" value="1" onChange="kindChange(1);" <?php echo $全日 ?>>
          <label class="radio-for-lbl" for="全日フラグ1" >全日</label>
        </div>
        <div class="kind">
          <input type="radio" class="radio" id="全日フラグ0" name="全日フラグ" value="0" onChange="kindChange(0);" <?php echo $一部 ?>>
          <label class="radio-for-lbl" for="全日フラグ0" >一部時間</label>
        </div>
      </div>
      <br><label class="err">
        <?php
          if (array_key_exists('全日フラグ', $エラーチェック結果)){
            echo $エラーチェック結果['全日フラグ'];
          }
        ?>
      </label>
    </div>

    <div class="item" >
      <?php
      $項目名ラベルクラス = 'itemLabel-noedit';
        if($エラーコード != ERR種類['エラー無し']){
          if($_POST['全日フラグ'] == '0'){
            $項目名ラベルクラス = 'itemLabel-primary';
          }
        }
      ?>
      <label class="<?php echo $項目名ラベルクラス; ?>" for="開始時" id="開始時間タイトルlbl" >開始時間</label>

      <?php
        $開始時 = ''; //初期値
        $開始時readOnly = 'readOnly';
        if($エラーコード != ERR種類['エラー無し']){
          $開始時 = $_POST['開始時'];
          if($_POST['全日フラグ'] == '0'){
            $開始時readOnly = '';
          }
        }
        $開始分 = ''; //初期値
        $開始分readOnly = 'readOnly';
        if($エラーコード != ERR種類['エラー無し']){
          $開始分 = $_POST['開始分'];
          if($_POST['全日フラグ'] == '0'){
            $開始分readOnly = '';
          }
        }
      ?>
      <input type="number" class="number-time" id="開始時" name="開始時" min="0" max="23" value="<?php echo $開始時; ?>" <?php echo $開始時readOnly; ?> >
      <label class="tillde">：</label>
      <input type="number" class="number-time" id="開始分" name="開始分" min="0" max="59" value="<?php echo $開始分; ?>" <?php echo $開始分readOnly; ?> >
      <br><label class="err">
        <?php
          if (array_key_exists('開始時', $エラーチェック結果)){
            echo '終了分'.$エラーチェック結果['開始時'];
          }
          if (array_key_exists('開始分', $エラーチェック結果)){
            echo '終了分'.$エラーチェック結果['開始分'];
          }
        ?>
      </label>
    </div>

    <div class="item" >
      <?php
        $項目名ラベルクラス = 'itemLabel-noedit';
        if($エラーコード != ERR種類['エラー無し']){
          if($_POST['全日フラグ'] == '0'){
            $項目名ラベルクラス = 'itemLabel-primary';
          }
        }
      ?>
      <label class="<?php echo $項目名ラベルクラス; ?>" for="終了時" id="終了時間タイトルlbl" >終了時間</label>
      <?php
        $終了時 = ''; //初期値
        $終了時readOnly = 'readOnly';
        if($エラーコード != ERR種類['エラー無し']){
          $終了時 = $_POST['終了時'];
          if($_POST['全日フラグ'] == '0'){
            $終了時readOnly = '';
          }
        }
        $終了分 = ''; //初期値
        $終了分readOnly = 'readOnly';
        if($エラーコード != ERR種類['エラー無し']){
          $終了分 = $_POST['終了分'];
          if($_POST['全日フラグ'] == '0'){
            $終了分readOnly = '';
          }
        }
      ?>
      <input type="number" class="number-time" id="終了時" name="終了時" min="0" max="29" value="<?php echo $終了時; ?>" <?php echo $終了時readOnly; ?> >
      <label class="tillde">：</label>
      <input type="number" class="number-time" id="終了分" name="終了分" min="0" max="59" value="<?php echo $終了分; ?>" <?php echo $終了分readOnly; ?> >
      <br><label class="explain">29:59まで入力可能です。</label>
      <br><label class="err">
        <?php
          if (array_key_exists('終了時', $エラーチェック結果)){
            echo '終了分'.$エラーチェック結果['終了時'];
          }
          if (array_key_exists('終了分', $エラーチェック結果)){
            echo '終了分'.$エラーチェック結果['終了分'];
          }
        ?>
      </label>
    </div>

    <div class="item" id="upd-bak-btn">
      <button id="btn_Update" name="btn_Update" value="<?php echo モードコード['更新']; ?>">更新</button>
      <button id="btn_back" name="btn_back" value="<?php echo モードコード['戻る']; ?>">戻る</button>
    </div>
  </form>

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

  <form class="delete" action="" method="post">
    <input type="hidden" name="menu-btn" value="<?php echo $index['Me']['NO']; ?>" />
    <input type="hidden" name="reg_MaxSeq" value="<?php echo count($ary_不定休); ?>" />
    <?php  
      if (count($ary_不定休) == 0){
        //処理日以降の不定休設定はない
        echo '<h4>有効な不定休設定はありません。</h4>';
      }
    ?>

    <table id="reg-table">
      <tr>
        <th class="clm-date">日付</th>
        <?php if(count($ary_曜日) == 7){ ?>
            <th class="clm-week">曜日</th>
        <?php } ?>
        <th class="clm-info">不定休内容</th>
        <th class="clm-delBtn">削除</th>
      </tr>

      <?php for($seq = 1; $seq <= count($ary_不定休); $seq++){
        $row = $ary_不定休[$seq - 1];

        // 曜日を取得
        $datetime = new DateTime($row['日付']); // 型変換
        ?>
        <tr>
          <td class="clm-date"><?php echo $row['日付']; ?></td>

          <?php if(count($ary_曜日) == 7){
              $曜日名 = $ary_曜日[(int)$datetime->format('w')]['名称'];
            ?>
            <td class="clm-week"><?php echo $曜日名; ?></td>
          <?php } ?>
          <td class="clm-info">
            <?php if($row['全日フラグ']){ ?>
              全日
            <?php }else{ ?>
              <?php 
                echo str_pad($row['開始時'], 2,0,STR_PAD_LEFT).'：'.str_pad($row['開始分'], 2,0,STR_PAD_LEFT);
                echo '〜';
                echo str_pad($row['終了時'], 2,0,STR_PAD_LEFT).'：'.str_pad($row['終了分'], 2,0,STR_PAD_LEFT);
              ?>
            <?php } ?>
          </td>
          <td class="clm-delBtn">
            <input type="checkbox" class="reg_checkbox" id="reg_checkbox<?php echo $seq; ?>" name="reg_削除<?php echo $seq; ?>" value="1" />
          </td>

          <?php /* 隠し項目 */ ?>
          <input type="hidden" class="reg_日付" name="reg_日付<?php echo $seq; ?>" value="<?php echo $row['日付']; ?>" />
          <input type="hidden" name="reg_全日フラグ<?php echo $seq; ?>" value="<?php echo $row['全日フラグ']; ?>" />
          <input type="hidden" name="reg_開始時<?php echo $seq; ?>" value="<?php echo $row['開始時']; ?>" />
          <input type="hidden" name="reg_開始分<?php echo $seq; ?>" value="<?php echo $row['開始分']; ?>" />
          <input type="hidden" name="reg_終了時<?php echo $seq; ?>" value="<?php echo $row['終了時']; ?>" />
          <input type="hidden" name="reg_終了分<?php echo $seq; ?>" value="<?php echo $row['終了分']; ?>" />  
        </tr>
      <?php } ?>
    </table>

    <div class="item">
      <button id="btn_delete" name="btn_delete" value="<?php echo モードコード['削除']; ?>">削除</button>
    </div>
  </form>
</div>