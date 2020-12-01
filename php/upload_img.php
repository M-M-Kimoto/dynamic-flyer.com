<?php

class imgUpload
{

    /*
    * 基本ページトップ画像
    */
    function top($PI_filePath, $PI_filePath_exc, $PI_uploadFolder){

        if(!file_exists($PI_uploadFolder)){
            $this->make_folder($PI_uploadFolder);
        }

        $this->Fnc_CopyImg($PI_filePath, $PI_filePath_exc, $PI_uploadFolder . 'top.jpg', 850, 650, 100);
        $this->Fnc_CopyImg($PI_filePath, $PI_filePath_exc, $PI_uploadFolder . 'thumbnail.jpg', 180, 110, 80);
        
        return true;
    }

    /*
    * 基本ページ詳細画像
    */
    function details($PI_filePath, $PI_filePath_exc, $PI_uploadFolder, $PI_UploadFileName){

        if(!file_exists($PI_uploadFolder)){
            $this->make_folder($PI_uploadFolder);
        }

        $this->Fnc_CopyImg($PI_filePath, $PI_filePath_exc, $PI_uploadFolder . $PI_UploadFileName, 600, 600, 100);
        
        return true;
    }

    


    private function make_folder($PI_Path){

        // フォルダが存在しなければ作る
        if(!file_exists($PI_Path)){

            $mask = umask();  //事前のマスク値を退避
            umask(000);  //マスクを無効にする

            mkdir($PI_Path,0775, TRUE);

            umask($mask);  //マスク値をもとに戻す
        }
    }


    /*
    * 画像ファイルを指定の方法でリサイズし、jpgファイルとして保存する
    * http://www.kaasan.info/archives/2343
    */
    private function Fnc_CopyImg($PI_fileBefore, $PI_fileBefore_exc, $PI_fileAfterFolder, $PI_width, $PI_height, $PI_Quality){
        
        //　元画像ファイル読み込み
        $in = "";
        $PI_fileBefore_exc = strtolower($PI_fileBefore_exc);
        if($PI_fileBefore_exc == "png"){

            $in = ImageCreateFromPNG($PI_fileBefore); 

        }elseif($PI_fileBefore_exc == "jpg" || $PI_fileBefore_exc == "jpeg"){

            $in = ImageCreateFromJPEG($PI_fileBefore); 

        }
 
        $size = GetImageSize($PI_fileBefore);                            //　元画像サイズ取得  
                
        $width = $PI_width;                                //　リサイズしたい画像サイズ(width)  
        $height = $PI_height;                                //　リサイズしたい画像サイズ（height）  
        
        $ratio_orig = $size[0]/$size[1];//元画像の比率計算  
        
        /*画像の比率を計算する*/  
        if ($width/$height > $ratio_orig) {  
            $width = $height*$ratio_orig;  
        } else {  
            $height = $width/$ratio_orig;  
        }  
        
        /*画像の生成ここから*/   
        $out = ImageCreateTrueColor($width, $height);  
        ImageCopyResampled($out, $in, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);    //　サイズ変更・コピー  
        ImageJPEG($out, $PI_fileAfterFolder, $PI_Quality);                            //最後に合体した画像を保存して終了
        
        // 保存した画像の向きについて取得し、補正する
        $exif_datas = exif_read_data($PI_fileBefore);

        $angle = 0;
        if(isset($exif_datas['Orientation'])){

            switch(ceil($exif_datas['Orientation'] / 2)){

                case 3:
                    $angle = -90;
                    break;

                case 2:
                    $angle = -180;
                    break;

                case 4:
                    $angle = 90;
                    break;


            }

        }

        // 回転が必要な場合は回して保存する
        if($angle != 0){
            $in = imagecreatefromjpeg($PI_fileAfterFolder);
            $out = imagerotate($in, $angle, 0);
            imagejpeg($out, $PI_fileAfterFolder, 100);
        }


        //メモリ開放  
        ImageDestroy($in);  
        ImageDestroy($out); 
        clearstatcache(); 

        return true;
    }

}

?>