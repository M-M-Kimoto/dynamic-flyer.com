
<?php 

  function update(){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_Tra表示設定 = new TraShopPageInfo_Ctrl;
    $更新内容 = array();

    /* 配列へ */
      $更新内容 = array(
        'ショップID'=>$_SESSION['shop_id'],
        'レベル'=>$_POST['レベル'],
        '名称'=>$_POST['名称'],
        '表示開始日時'=>$_POST['表示開始日'].' '.$_POST['表示開始時'].':'.$_POST['表示開始分'].':00',
        '表示終了日時'=>$_POST['表示終了日'].' '.$_POST['表示終了時'].':'.$_POST['表示終了分'].':59',
        '表示ページURL'=>$_POST['URL'],
      );

    /*  */

    /* エラーチェック */
      $res_check = $cls_Tra表示設定->check($更新内容);
      if ($res_check['status'] == false){
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
        $return_dateUpdIns = data_Update_or_Insert($cls_Tra表示設定, $更新内容);
        
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

    $cls_Tra表示設定 = new TraShopPageInfo_Ctrl;
    
    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();

        for ($seq = 1; $seq <= レベルMAX; $seq++){

          if (isset($_POST['reg_削除'.$seq]) == false){
            continue;
          }elseif($_POST['reg_削除'.$seq] != '1'){
            continue;
          }
          $Tra表示設定_更新情報 = array(
            'ショップID'=>$_SESSION['shop_id'],
            'レベル'=>$_POST['reg_レベル_'.$seq]
          );
    
          // エラー無しで更新処理実行
          $sql_del = $cls_Tra表示設定->delete($Tra表示設定_更新情報);
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

   
  // この画面で使う定数
  define('レベルMAX', 9);

  /* 変数初期化 */
  $エラーコード = ERR種類['エラー無し'];
  $モード = モードコード['入力'];
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

  /* tra_ページ表示タイミング取得処理 */
    $cls_Tra表示設定 = new TraShopPageInfo_Ctrl;
    $tra表示設定_検索条件 = array(); // sqlを作るための引数用
    $ary_tra表示設定 = array(); // ログインショップIDのmst_ショップのレコード

    // ショップIDを条件に検索
    $tra表示設定_検索条件 = array(
      'ショップID'=>$_SESSION['shop_id']
    );
    $sql_select = $cls_Tra表示設定->select($tra表示設定_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];
    }else{
      // 取得レコードを変数へ
      $ary_tra表示設定 = $res_select["rows"];
    }
  /*  */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();

  }
  clearstatcache(); 

?>

<link rel="stylesheet" href="./stylesheets/<?php echo $index['Me']['ファイル名']; ?>.css">

<script language="javascript" type="text/javascript">

  function lvChange(obj){
    var selectLv = obj.options[obj.selectedIndex].value;
    let MaxSeq = <?php echo レベルMAX; ?>;
    //let selectLv = document.getElementById("レベル").selected;

    document.getElementById("名称").value = '';
    document.getElementById("表示開始日").value =  '';
    document.getElementById("表示開始時").value =  '00';
    document.getElementById("表示開始分").value =  '00';
    document.getElementById("表示終了日").value =  '';
    document.getElementById("表示終了時").value =  '23';
    document.getElementById("表示終了分").value =  '59';
    document.getElementById("URL").value= "";
    if (document.getElementById( "reg_レベル_" + selectLv ) == null){
      // 存在しない場合は処理をぬける
      return;
    }

    // inputに値を入れる
    document.getElementById("名称").value = document.getElementById( "reg_名称_lv" + selectLv ).value;
    document.getElementById("表示開始日").value = document.getElementById( "reg_表示開始日_lv" + selectLv ).value;
    document.getElementById("表示開始時").value = document.getElementById( "reg_表示開始時_lv" + selectLv ).value;
    document.getElementById("表示開始分").value = document.getElementById( "reg_表示開始分_lv" + selectLv ).value;
    document.getElementById("表示終了日").value = document.getElementById( "reg_表示終了日_lv" + selectLv ).value;
    document.getElementById("表示終了時").value = document.getElementById( "reg_表示終了時_lv" + selectLv ).value;
    document.getElementById("表示終了分").value = document.getElementById( "reg_表示終了分_lv" + selectLv ).value;
    document.getElementById("URL").value = document.getElementById( "reg_表示ページURL_lv" + selectLv ).value;
    
  }

  function numChange(obj){
    obj.value = ( '00' + obj.value ).slice( -2 );
  }
  
  var window_A;
  function urlClick(){
    if (document.getElementById('URL').value == ""){
      return ;
    }
    window_A = window.open(document.getElementById('URL').value, "google");
  }

</script>

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


<div class="content-item">
  <form class="insert" action="" method="post">
    <input type="hidden" name="menu-btn" value="<?php  echo $index['Me']['NO']; ?>" />
    
    <div class="item" id="Lv">
    
      <label for="レベル">Lv</label>
      <select class="select-Lv" id="レベル" name="レベル" onChange="lvChange(this);">
        <option id="Lv0" value="0"></option>
        <?php
          $レベル = '';
          if($エラーコード != ERR種類['エラー無し']){
            $レベル = $_POST['レベル'];
          }
          ?>
          <?php for($seq=1; $seq <= レベルMAX; $seq++){
            $名称 = "";
            foreach($ary_tra表示設定 as $row){
              if($row['レベル'] == $seq){
                $名称 = $row['名称'];
                break;
              }
            }
            ?>
            <?php if($レベル == $seq){ ?>
              <option id="Lv<?php echo $seq; ?>" name="Lv<?php echo $seq; ?>" value="<?php echo $seq; ?>" selected><?php echo $seq; ?>：<?php echo $名称;?></option>
            <?php }else{ ?>
              <option id="Lv<?php echo $seq; ?>" name="Lv<?php echo $seq; ?>" value="<?php echo $seq; ?>"><?php echo $seq; ?>：<?php echo $名称; ?></option>
            <?php } ?>
         <?php } ?>
      </select>
      <br><label class="err">
      <?php
        if (array_key_exists('レベル', $エラーチェック結果)){
          echo $エラーチェック結果['レベル'];
        }
      ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="名称" >名称</label>
      <?php
        $名称 = ''; //初期値
        if($エラーコード != ERR種類['エラー無し']){
          $名称 = $_POST['名称'];
        }
      ?>
      <input type="text" class="text" id="名称" name="名称" value="<?php echo $名称 ?>">
      <br><label class="err">
        <?php
          if (array_key_exists('名称', $エラーチェック結果)){
            echo $エラーチェック結果['名称'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="表示開始日">表示開始日時</label>
      <?php
        $表示開始日= '';
        $表示開始時 = '00'; //初期値
        $表示開始分 = '00'; //初期値
        if($エラーコード != ERR種類['エラー無し']){
          $表示開始日 = $_POST['表示開始日'];
          $表示開始時 = $_POST['表示開始時'];
          $表示開始分 = $_POST['表示開始分'];
        }
      ?>
      <input type="date" class="date" id="表示開始日" name="表示開始日" value="<?php echo $表示開始日 ?>">
      <input type="number" class="number-time" id="表示開始時" name="表示開始時" min="0" max="23" value="<?php echo $表示開始時 ?>" onChange="numChange(this);" >
      <label class="tillde">：</label>
      <input type="number" class="number-time" id="表示開始分" name="表示開始分" min="0" max="59" value="<?php echo $表示開始分 ?>" onChange="numChange(this);" >
      <br><label class="err">
        <?php
          if (array_key_exists('表示開始日時', $エラーチェック結果)){
            echo $エラーチェック結果['表示開始日時'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="表示終了日">表示終了日時</label>
      <?php
        $表示終了日 = '';
        $表示終了時 = '23'; //初期値
        $表示終了分 = '59';
        if($エラーコード != ERR種類['エラー無し']){
          $表示終了日 = $_POST['表示終了日'];
          $表示終了時 = $_POST['表示終了時'];
          $表示終了分 = $_POST['表示終了分'];
        }
      ?>
      <input type="date" class="date" id="表示終了日" name="表示終了日" value="<?php echo $表示終了日 ?>">
      <input type="number" class="number-time" id="表示終了時" name="表示終了時" min="0" max="23" value="<?php echo $表示終了時 ?>" onChange="numChange(this);">
      <label class="tillde">：</label>
      <input type="number" class="number-time" id="表示終了分" name="表示終了分" min="0" max="59" value="<?php echo $表示終了分 ?>" onChange="numChange(this);" >
      <br><label class="annotation-lbl">※表示開始日から三ヶ月以内の日時を指定可能。</label>
      <br><label class="err">
        <?php
          if (array_key_exists('表示終了日時', $エラーチェック結果)){
            echo $エラーチェック結果['表示終了日時'];
          }
        ?>
      </label>
    </div>
      
    <div class="item">
        <label class="itemLabel-primary" id="label-URL" for="URL">URL</label>
        <?php
          $URL = "";
          if($エラーコード != ERR種類['エラー無し']){
            $URL = $_POST['URL'];
          }
        ?>
        <input type="text" id="URL" class="text" name="URL" value="<?php echo $URL; ?>" placeholder="<?php echo "https://" . $_SERVER["HTTP_HOST"] . "/?shopID=" . $index['ショップID'] . "&page=ショップ基本情報"; ?>">
        <br><label class="explain">未入力でショップ基本情報ページが設定されます。</label>
        <button type="button" id="url-btn" onClick="urlClick();">URLを開く</button>
        <br><label class="err">
          <?php
          if (array_key_exists('URL', $エラーチェック結果)){
            echo $エラーチェック結果['URL'];
          }
          ?>

    </div>

    <div class="item" id="upd-bak-btn">
      <button id="btn_Update" name="btn_Update" value="<?php echo モードコード['更新']; ?>" >更新</button>
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
    <input type="hidden" name="reg_MaxSeq" value="<?php echo count($ary_tra表示設定); ?>" />
    <?php
      if (count($ary_tra表示設定) == 0){
        //処理日以降の不定営表示はない
        echo '<h4>';
        echo '登録している表示ページ設定はありません。<br>';
        echo '現在は常に基本ページを表示しています。';
        echo '</h4>';
      }
    ?>
    <table id="reg-table">
      <tr>
        <th class="clm-level">レベル</th>
        <th class="clm-name">名称</th>
        <th class="clm-period">表示期間</th>
        <th class="clm-delBtn">削除</th>
      </tr>

      <?php for($seq = 1; $seq <= count($ary_tra表示設定); $seq++){
        $row = $ary_tra表示設定[$seq - 1];
        ?>

        <tr>
          <td class="clm-level"><?php echo $row['レベル']; ?></td>
          <td class="clm-name"><?php echo $row['名称']; ?></td>

          <td class="clm-period">
            <?php
              $開始日時 = new DateTime($row['表示開始日時']);
              $終了日時 = new DateTime($row['表示終了日時']);
              echo $開始日時->format('Y/m/d H:i');
              echo '<br>';
              echo '〜 '.$終了日時->format('Y/m/d H:i');
            ?>
          </td>

          <td class="clm-delBtn">
            <input type="checkbox" class="reg_checkbox" id="reg_checkbox<?php echo $row['レベル']; ?>" name="reg_削除<?php echo $row['レベル']; ?>" value="1" >
          </td>

          <?php /* 隠し要素 */ ?>
          <input type="hidden" class="reg_hidden" id="reg_レベル_<?php echo $row['レベル']; ?>" name="reg_レベル_<?php echo $row['レベル']; ?>" value="<?php echo $row['レベル']; ?>" >
          <input type="hidden" class="reg_hidden" id="reg_名称_lv<?php echo $row['レベル']; ?>" name="reg_名称_lv<?php echo $row['レベル']; ?>" value="<?php echo $row['名称']; ?>" >

          <?php $開始日時分 = new DateTime($row['表示開始日時']); ?>
          <input type="hidden" class="reg_hidden" id="reg_表示開始日_lv<?php echo $row['レベル']; ?>" name="reg_表示開始日_lv<?php echo $row['レベル']; ?>" value="<?php echo $開始日時分->format('Y-m-d'); ?>" >
          <input type="hidden" class="reg_hidden" id="reg_表示開始時_lv<?php echo $row['レベル']; ?>" name="reg_表示開始時_lv<?php echo $row['レベル']; ?>" value="<?php echo $開始日時分->format('H'); ?>" >
          <input type="hidden" class="reg_hidden" id="reg_表示開始分_lv<?php echo $row['レベル']; ?>" name="reg_表示開始分_lv<?php echo $row['レベル']; ?>" value="<?php echo $開始日時分->format('i'); ?>" >

          <?php $終了日時分 = new DateTime($row['表示終了日時']); ?>
          <input type="hidden" class="reg_hidden" id="reg_表示終了日_lv<?php echo $row['レベル']; ?>" name="reg_表示終了日_lv<?php echo $row['レベル']; ?>" value="<?php echo $終了日時分->format('Y-m-d'); ?>" >
          <input type="hidden" class="reg_hidden" id="reg_表示終了時_lv<?php echo $row['レベル']; ?>" name="reg_表示終了時_lv<?php echo $row['レベル']; ?>" value="<?php echo $終了日時分->format('H'); ?>" >
          <input type="hidden" class="reg_hidden" id="reg_表示終了分_lv<?php echo $row['レベル']; ?>" name="reg_表示終了分_lv<?php echo $row['レベル']; ?>" value="<?php echo $終了日時分->format('i'); ?>" >

          <input type="hidden" class="reg_hidden" id="reg_表示ページURL_lv<?php echo $row['レベル']; ?>" name="reg_表示ページURL_lv<?php echo $row['レベル']; ?>" value="<?php echo $row['表示ページURL']; ?>" >
        </tr>
      <?php } ?>
    </table>
    <div class="item">
      <button id="btn_delete" name="btn_delete" value="<?php echo モードコード['削除']; ?>" >削除</button>
    </div>
  </form>
</div>