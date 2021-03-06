
<?php 

  function upload($mode){

    $エラーコード = ERR種類['エラー無し'];
    $エラーチェック結果 = array();

    $cls_Mstユーザ = new MstUser_Ctrl();
    $Mstユーザ_登録情報 = array();

    /* 登録内容配列作成 */
      // 入力後の処理であれば、とりあえず連想配列を作成
      $Mstユーザ_登録情報= array(
        'ID'=>$_SESSION['ID'],
        'パスワード'=>$_POST['パスワード'],
        'ニックネーム'=>$_POST['ニックネーム'],
        '生年月日'=>$_POST['生年月日'],
        '男'=>0,
        '女'=>0,
        '職業'=>$_POST['職業'],
        '都道府県'=>$_POST['都道府県'],
        '市区町村'=>$_POST['市区町村'],
        '町名番地'=>$_POST['町名番地'],
        '建物等'=>$_POST['建物等'],
        '質問1'=>$_POST['質問1'],
        '回答1'=>$_POST['回答1'],
        '質問2'=>$_POST['質問2'],
        '回答2'=>$_POST['回答2'],
        '質問3'=>$_POST['質問3'],
        '回答3'=>$_POST['回答3']
      );

      if (isset($_POST['性別'])){
        if ($_POST['性別'] == 'men'){
          $Mstユーザ_登録情報['男'] = 1;
        }elseif ($_POST['性別'] == 'women'){
          $Mstユーザ_登録情報['女'] = 1;
        }
      }

    /*  */

    /* エラーチェック */

      $res_chk = $cls_Mstユーザ->check($Mstユーザ_登録情報);
      if ($res_chk['status'] == 結果['問題あり']){
        $エラーコード = ERR種類['エラーチェック'];
        $エラーチェック結果 = $res_chk['msg'];
      }

    /*  */

    if($mode == モードコード['確認']){
      return array('エラーコード'=>$エラーコード, 'エラーチェック結果'=>$エラーチェック結果);
    }

    /* エラーチェックに引っ掛かった */
    if($エラーコード != ERR種類['エラー無し']){
      return array('エラーコード'=>ERR種類['エラーチェック'], 'エラーチェック結果'=>$エラーチェック結果);
    }

    /* トランザクション*/
      try{
        global $cls_dbCtrl;
        $cls_dbCtrl->begin_tran();

        $return_dateUpdIns = array();
        $return_dateUpdIns = data_Update($cls_Mstユーザ, $Mstユーザ_登録情報);

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

?>

<hr width='80%'>
	<div id="pageTitle"> 
		<label id="pageTitle-lbl"><?php echo $_GET['page']; ?></label>
	</div> 
<hr width='80%'>

<?php

  if (isset($_SESSION["ID"]) == false){
    header("Location: ./?page=新着情報");
    exit ;
  }else{
    if($_SESSION["ID"] == "" || $_SESSION["ID"] == ゲストユーザ['ID']){
      header("Location: ./?page=新着情報");
      exit ;
    }
  }

  define("モードコード", array('入力'=>0,
                               '確認'=>1,
                               '登録'=>2,
                               '戻る'=>9
                              )
  );

  // 変数初期化
  $モード = モードコード['入力'];
  $エラーコード = ERR種類['エラー無し'];
  $エラーチェック結果 = array();
  $ログインユーザ情報 = array();
  $val = "";

  // echo $_POST['btn_confirm'];
  if( isset($_POST['btn_submit']) ) {
    $モード =  $_POST['btn_submit'];
  }

  if ($モード != モードコード['入力']){

      // 更新処理実行
      $return = upload($モード);
      $エラーコード = $return['エラーコード'];
      $エラーチェック結果 = $return['エラーチェック結果'];
      if($エラーコード == ERR種類['エラー無し']){

        $_SESSION['パスワード'] = $_POST['パスワード'];
        $_SESSION['ニックネーム'] = $_POST['ニックネーム'];

        $_POST = array();

      }
  }

  /* 結果に関わらず最新情報を取得する */
    $cls_Mstユーザ = new MstUser_Ctrl();
    $Mstユーザ_検索条件= array(
      'ID'=>$_SESSION['ID']
    );

    // ショップIDとパスワードを条件に検索
    $sql_select = $cls_Mstユーザ->select($Mstユーザ_検索条件);
    $res_select = $cls_dbCtrl->select($sql_select);
    if ($res_select["status"] == false){
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }elseif ($res_select["count"] < 1){
      
      //キーで検索しているためありえないが、
      //これをいれないと問答無用で配列の先頭をとるというのが意味不明なため
      $エラーコード = ERR種類['マスタ情報取得失敗'];

    }else{
      // 取得レコードを変数へ
      $ログインユーザ情報 = $res_select["rows"][0];
      
    }
  /*  */

  if($エラーコード == ERR種類['エラー無し']){

    // ポストした内容も消す
    $_POST = array();

  }
?>

 <form class="insert" action="" method="post">
  <div class="content-item">

    <div class="item">
      <?php

        // メッセージ
        $メッセージ = '';
        if ($エラーコード == ERR種類['エラー無し'] && $モード == モードコード['登録']) {
            $メッセージ = '更新しました。';
        }elseif ($エラーコード == ERR種類['更新失敗']) {
          $メッセージ = '更新に失敗しました。少し時間を開けてから、もう一度実行してください。';
          $メッセージ = $メッセージ.'<br>それでも解決しない場合は、運営まで御連絡ください。';
        }elseif ($エラーコード == ERR種類['マスタ情報取得失敗']) {
          $メッセージ = 'ユーザ基本情報の取得に失敗しました。ページの更新をしてください。';
          $メッセージ = $メッセージ.'<br>それでも解決しない場合は、運営まで御連絡ください。';
        }elseif ($エラーコード == ERR種類['エラーチェック']) {
          $メッセージ = '入力内容に誤りを検知しました。';
          $メッセージ = $メッセージ.'<br>内容を確認後、もう一度更新ボタンを押してください。';
        }elseif ($モード == モードコード['入力']){

        }
        echo '<label id="top-msg">'.$メッセージ.'</label>';

      ?>
    </div>

    <div class="item">
      <label class="itemLabel-noedit" >ID</label>
      <label ><?php echo $_SESSION['ID']; ?></label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="パスワード">パスワード</label>
      <?php
        $val = $ログインユーザ情報['パスワード'];
        if(array_key_exists('パスワード', $_POST)){
          $val = $_POST['パスワード'];
        }
      ?>
      <input type="passwd" class="text" id="パスワード" name="パスワード" placeholder="英数字、8文字以上" value="<?php echo $val; ?>">
      <label class="errMsg">
      <?php
        if (array_key_exists('パスワード', $エラーチェック結果)){
          echo $エラーチェック結果['パスワード'];
        }
      ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="ニックネーム">ニックネーム</label>
      <?php
        $val = $ログインユーザ情報['ニックネーム'];
        if(array_key_exists('ニックネーム', $_POST)){
          $val = $_POST['ニックネーム'];
        }
      ?>
      <input type="text" class="text" id="ニックネーム" name="ニックネーム" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
      <?php
        if (array_key_exists('ニックネーム', $エラーチェック結果)){
          echo $エラーチェック結果['ニックネーム'];
        }
      ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="text">生年月日</label>
      <?php
        $val = $ログインユーザ情報['生年月日'];
        if(array_key_exists('生年月日', $_POST)){
          $val = $_POST['生年月日'];
        }
      ?>
      <input type="date" class="date" id="生年月日" name="生年月日" placeholder="2000/04/01" value="<?php echo $val; ?>">
      <label class="errMsg">
      <?php
        if (array_key_exists('生年月日', $エラーチェック結果)){
          echo $エラーチェック結果['生年月日'];
        }
      ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="性別">性別</label>
      <?php
        $men_checked = "";
        $women_checked = "";
        if($ログインユーザ情報['男'] == '1'){
          $men_checked = "checked";
          $women_checked = "";
        }elseif($ログインユーザ情報['女'] == '1'){
          $men_checked = "";
          $women_checked = "checked";
        }
        if(array_key_exists('男', $_POST)){
          if($ログインユーザ情報['男'] == '1'){
            $men_checked = "checked";
            $women_checked = "";
          }
        }elseif(array_key_exists('女', $_POST)){
          if($ログインユーザ情報['女'] == '1'){
            $men_checked = "";
            $women_checked = "checked";
          }
        }
      ?>
        <p>
          <input type="radio" class="radio" id="men"   name="性別" value="men" <?php echo $men_checked; ?> >男
          <input type="radio" class="radio" id="women" name="性別" value="women" <?php echo $women_checked; ?> >女
        </p>
      <label class="errMsg">
      <?php
        if (array_key_exists('男', $エラーチェック結果)){
          echo $エラーチェック結果['男'];
        }
        if (array_key_exists('女', $エラーチェック結果)){
          echo $エラーチェック結果['女'];
        }
      ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="職業">職業</label>
      <?php
        $val = $ログインユーザ情報['職業'];
        if(array_key_exists('職業', $_POST)){
          $val = $_POST['職業'];
        }
      ?>
      <input type="text" class="text" id="職業" name="職業" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('職業', $エラーチェック結果)){
            echo $エラーチェック結果['職業'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="text">都道府県</label>
      <?php
        $val = $ログインユーザ情報['都道府県'];
        if(array_key_exists('都道府県', $_POST)){
          $val = $_POST['都道府県'];
        }
      ?>
      <input type="text" class="text" id="都道府県" name="都道府県" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('都道府県', $エラーチェック結果)){
            echo $エラーチェック結果['都道府県'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="市区町村">市区町村</label>
      <?php
        $val = $ログインユーザ情報['市区町村'];
        if(array_key_exists('市区町村', $_POST)){
          $val = $_POST['市区町村'];
        }
      ?>
      <input type="text" class="text" id="市区町村" name="市区町村" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('市区町村', $エラーチェック結果)){
            echo $エラーチェック結果['市区町村'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="text">町名番地</label>
      <?php
        $val = $ログインユーザ情報['町名番地'];
        if(array_key_exists('町名番地', $_POST)){
          $val = $_POST['町名番地'];
        }
      ?>
      <input type="text" class="text" id="町名番地" name="町名番地" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('町名番地', $エラーチェック結果)){
            echo $エラーチェック結果['町名番地'];
          }
        ?>
      </label>
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="建物等">建物等</label>
      <?php
        $val = $ログインユーザ情報['建物等'];
        if(array_key_exists('建物等', $_POST)){
          $val = $_POST['建物等'];
        }
      ?>
      <input type="text" class="text" id="建物等" name="建物等" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('建物等', $エラーチェック結果)){
            echo $エラーチェック結果['建物等'];
          }
        ?>

      </label>
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="質問1">質問1</label>
      <?php
        $val = $ログインユーザ情報['質問1'];
        if(array_key_exists('質問1', $_POST)){
          $val = $_POST['質問1'];
        }
      ?>
      <input type="text" class="text" id="質問1" name="質問1" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('質問1', $エラーチェック結果)){
            echo $エラーチェック結果['質問1'];
          }
        ?>
      </label>  
    </div>

    <div class="item">
      <label class="itemLabel-primary" for="回答1">回答1</label>
      <?php
        $val = $ログインユーザ情報['回答1'];
        if(array_key_exists('回答1', $_POST)){
          $val = $_POST['回答1'];
        }
      ?>
      <input type="text" class="text" id="回答1" name="回答1" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('回答1', $エラーチェック結果)){
            echo $エラーチェック結果['回答1'];
          }
        ?>
      </label>  
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="質問2">質問2</label>
      <?php
        $val = $ログインユーザ情報['質問2'];
        if(array_key_exists('質問2', $_POST)){
          $val = $_POST['質問2'];
        }
      ?>
      <input type="text" class="text" id="質問2" name="質問2" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('質問2', $エラーチェック結果)){
            echo $エラーチェック結果['質問2'];
          }
        ?>
      </label>  
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="回答2">回答2</label>
      <?php
        $val = $ログインユーザ情報['回答2'];
        if(array_key_exists('回答2', $_POST)){
          $val = $_POST['回答2'];
        }
      ?>
      <input type="text" class="text" id="回答2" name="回答2" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('回答2', $エラーチェック結果)){
            echo $エラーチェック結果['回答2'];
          }
        ?>
      </label>  
    </div>
    
    <div class="item">
      <label class="itemLabel-nomal" for="質問3">質問3</label>
      <?php
        $val = $ログインユーザ情報['質問3'];
        if(array_key_exists('質問3', $_POST)){
          $val = $_POST['質問3'];
        }
      ?>
      <input type="text" class="text" id="質問3" name="質問3" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('質問3', $エラーチェック結果)){
            echo $エラーチェック結果['質問3'];
          }
        ?>
      </label>  
    </div>

    <div class="item">
      <label class="itemLabel-nomal" for="回答3">回答3</label>
      <?php
        $val = $ログインユーザ情報['回答3'];
        if(array_key_exists('回答3', $_POST)){
          $val = $_POST['回答3'];
        }
      ?>
      <input type="text" class="text" id="回答3" name="回答3" placeholder="" value="<?php echo $val; ?>">
      <label class="errMsg">
        <?php
          if (array_key_exists('回答3', $エラーチェック結果)){
            echo $エラーチェック結果['回答3'];
          }
        ?>
      </label>  
    </div>

    <div class="item">
      <button name="btn_submit" value="<?php echo モードコード['登録'] ?>">登録</button>
      <label class="errMsg">
        ※パスワードを変更した場合、必ずメモを取ってください。
      </label>  
    </div>  
  </div>
</form>