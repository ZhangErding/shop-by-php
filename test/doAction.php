<?php
require_once '../lib/string.func.php';
header("content-type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/16
 * Time: 11:29
 */

/*
php.ini
服务器端配置
;;;;;;;;;;;;;;;;
; File Uploads ;
;;;;;;;;;;;;;;;;

; Whether to allow HTTP file uploads.
file_uploads = On 支持通过HTTP POST方式上传

; Temporary directory for HTTP uploaded files (will use system default if not
; specified).
upload_tmp_dir = "d:/wamp/tmp"
临时文件保存目录

; Maximum allowed size for uploaded files.
upload_max_filesize = 2M
默认值是2M，上传的文件最大大小
*/

//客户端进行配置 <input type="hidden" name="MAX_FILE_SIZE" value="1024">
//上传文件的信息保存在$_FILES
//print_r($_FILES);
$filename=$_FILES['myFile']['name'];
$type=$_FILES['myFile']['type'];
$tmp_name=$_FILES['myFile']['tmp_name'];
$error=$_FILES['myFile']['error'];
$size=$_FILES['myFile']['size'];
$allowExt=array('gif','jpeg','jpg','png','wbmp');
$maxSize=1048576;
$imgFlag=true;
//判断错误信息
if($error==UPLOAD_ERR_OK){
    $ext=getExt($filename);
    //判断非法类型
    if(!in_array($ext,$allowExt)){
        exit('非法文件类型');
    }
    //限制上传文件大小
    if($size>$maxSize){
        exit("文件过大");
    }
    //验证是否是真正的图片类型
    if($imgFlag){
        $info=getimagesize($tmp_name);
//        var_dump($info);
        if(!$info) {
            exit('不是真正的图片类型');
        }
    }
//文件是否是通过HTTP POST方式上传的
    if(is_uploaded_file($tmp_name)){
        $filename=getUniName().".".$ext;
        $path='uploads';
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
        $destination=$path."/".$filename;
        if(move_uploaded_file($tmp_name,$destination)){
            $mes="文件上传成功";
        }
    }
    else{
        $mes="文件上传失败";
    }
}else{
    switch ($error){
        case 1:
            $mes="UPLOAD_ERR_INI_SIZE";
            break;
        case 2:
            $mes="UPLOAD_ERR_FORM_SIZE";
            break;
        case 3:
            $mes="UPLOAD_ERR_PARTIAL";
            break;
        case 4:
            $mes="UPLOAD_ERR_NO_FILE";
            break;
        case 6:
            $mes="UPLOAD_ERR_NO_TMP_DIR";
            break;
        case 7:
            $mes="UPLOAD_ERR_CANT_WRITE";
            break;
        case 8:
            $mes="UPLOAD_ERR_EXTENSION";
            break;
        case 9:
            $mes="U_PARSE_ERROR";
            break;
    }
}
echo $mes;