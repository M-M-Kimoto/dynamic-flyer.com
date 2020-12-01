<?php

class DB_Ctrl
{

  public $db;

  function select($PI_Sql){

    $result;
    try {

      // tranが始まっていない時は開始する
      if(empty($this->db)){
        $this->begin_tran();
      }

      // 実行
      $result = $this->db->query($PI_Sql);

    } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      return false;
    }

    return array('status'=>$result["status"],
                 'count'=>$result["count"],
                 'rows'=>$result["result"]);
  }

  
  function insert($PI_Sql){

    $result;
    try {
      // tranが始まっていない時は開始する
      if(empty($this->db)){
        $this->begin_tran();
      }

      // 実行
      $result = $this->db->query($PI_Sql);

    } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      return false;
    }

    // 実行失敗
    if ($result["status"] == FALSE){
      // 不一致
      return array('status'=>false, 'count'=>-1);
    }

    // 実行結果：0件
    if ($result["count"] < 1){
      return array('status'=>true, 'count'=>0);
    }

    return array('status'=>$result["status"],
                 'count'=>$result["count"]);

  }

  function update($PI_Sql){

    $result;

    try {
      // tranが始まっていない時は開始する
      if(empty($this->db)){
        $this->begin_tran();
      }

      // 実行
      $result = $this->db->query($PI_Sql);

    } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      return false;
    }

    // 実行失敗
    if ($result["status"] == FALSE){
      // 不一致
      return array('status'=>false, 'count'=>-1);
    }

    // 実行結果：0件
    if ($result["count"] < 1){
      return array('status'=>true, 'count'=>0);
    }

    return array('status'=>$result["status"],
                 'count'=>$result["count"]);

  }

  function delete($PI_Sql){

    $result;
    try {
      // tranが始まっていない時は開始する
      if(empty($this->db)){
        $this->begin_tran();
      }

      // 実行
      $result = $this->db->query($PI_Sql);

    } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      return false;
    }

    // 実行失敗
    if ($result["status"] == FALSE){
      // 不一致
      return array('status'=>false, 'count'=>-1);
    }

    // 実行結果：0件
    if ($result["count"] < 1){
      return array('status'=>true, 'count'=>0);
    }

    return array('status'=>$result["status"],
                 'count'=>$result["count"]);

  }

  function begin_tran(){

    // mysqliオブジェクトの取得とtranの開始
    $this->db = new MyDB();
    $this->db->begin_tran();

  }

  function commit(){
    $this->db->commit();
  }

  function rollback(){
    $this->db->rollback();
  }
}



