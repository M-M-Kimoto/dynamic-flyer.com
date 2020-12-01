
<?php

// https://qiita.com/yasumodev/items/bd2ba476f31804d527d3

//define('DB接続情報', array('host'=>'dynamic-flyer.com','username'=>'df-user','passwd'=>'White-0516', 'dbname'=>'df0516', 'port'=>'3306'));
define('DB接続情報', array('host'=>'localhost','username'=>'df-user','passwd'=>'White-0516', 'dbname'=>'df-service', 'port'=>'3306'));

/*
 * MyDBクラス
 */
class MyDB
{
    public $mysqli; // mysqliオブジェクト
    public $mode;   // 戻り値の形式："json" or "array"（連想配列）
    public $count;  // SQLによって取得した行数 or 影響した行数

    // コンストラクタ
    function __construct($mode = "array") 
    {
        global $host;
        global $username;
        global $passwd;
        global $dbname;
        global $port;

        $this->mode = $mode;


        // DB接続
        $this->mysqli = new mysqli(DB接続情報['host'], DB接続情報['username'], DB接続情報['passwd'], DB接続情報['dbname']);
        if ($this->mysqli->connect_error) {
            echo $this->mysqli->connect_error;
            exit;
        } else {
            $this->mysqli->set_charset("utf8");
        }
    }

    // デストラクタ
    function __destruct()
    {
        // DB接続を閉じる
        $this->mysqli->close();
    }

    function begin_tran(){
        $this->mysqli->begin_transaction();
    }

    function commit(){
        $this->mysqli->commit();        
    }

    function rollback(){
        $this->mysqli->rollback();        
    }
    function close(){
        $this->__destruct();     
    }



    // SQL実行（SELECT/INSERT/UPDATE/DELETE に対応）
    function query($sql)
    {
        // SQL実行
        $result = $this->mysqli->query($sql);
        // エラー
        if ($result === FALSE) {
            // エラー内容
            $error = $this->mysqli->errno.": ".$this->mysqli->error;
            // 戻り値
            $rtn = array(
                'status' => FALSE,
                'count'  => 0,
                'result' => "",
                'error'  => $error
            );
            if($this->mode == "array")
                return $rtn;
            else
                return json_encode($rtn); // JSON形式で返す（デフォルト）
        }

        // SELECT文以外
        if($result === TRUE) {
            // 影響のあった行数を格納
            $this->count = $this->mysqli->affected_rows;
            // 戻り値
            $rtn = array(
                'status' => TRUE,
                'count'  => $this->count,
                'result' => "",
                'error'  => ""
            );
            if($this->mode == "array")
                return $rtn;
            else
                return json_encode($rtn); // JSON形式で返す（デフォルト）
        } 
        // SELECT文
        else {
            // SELECTした行数を格納
            $this->count = $result->num_rows;
            // 連想配列に格納
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            // 結果セットを閉じる
            $result->close();
            // 戻り値
            $rtn = array(
                'status' => TRUE,
                'count'  => $this->count,
                'result' => $data,
                'error'  => ""
            );
            if($this->mode == "array")
                return $rtn;
            else
                return json_encode($rtn); // JSON形式で返す（デフォルト）
        }
    }

    // 文字列をエスケープする
    function escape($str)
    {
        return $this->mysqli->real_escape_string($str);
    }
}
?>