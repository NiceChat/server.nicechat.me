<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  require 'vendor/autoload.php';
  use Qiniu\Auth;
  /**
  * 一个提供Qiniu相关的桥接类 
  */
  class QiniuBirdge
  {
    protected static $accessKey = 'mBX2BAcEFBL4lf3eN6TJz9WERagFZc3pprQPe1nO';
    protected static $secretKey = 'IlQgBy1TdR12ap4yObvmp0bRyGAPoIevEpxX8XB3';

    // 像客户端发送上传凭证
    function sendUpToken() 
    {   
      // 初始化签权对象
      $auth = new Auth(self::$accessKey, self::$secretKey);
      $bucket = 'images';
      $token = $auth->uploadToken($bucket);
      return $token;
    }
  }