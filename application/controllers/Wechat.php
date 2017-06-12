<?php
  /**
   * wx 测试微信订阅号相关
   */
  class Wechat extends CI_Controller
  {
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
      $signature = isset($_GET['signature']) ? $_GET['signature'] : false;
      $timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : false;
      $nonce = isset($_GET['nonce']) ? $_GET['nonce'] : false;
      $echostr = isset($_GET['echostr']) ? $_GET['echostr'] : false;
      $token = 'Yangleilei';
      if ($signature && $timestamp && $nonce && $echostr) 
      {
        $list = array($token, $timestamp, $nonce);
        sort($list);
        $list = implode('',$list);
        $hashcode = sha1($list);
        if ($hashcode == $signature) {
          echo $echostr;
        } else {
          echo 'Ooops, no';
        }
      } 
      else {
        $data['code'] = 200;
        $data['message'] = '该数据源不是来自微信后台';
        echo json_encode($data);
      }
    }

    public function test() {
      echo 'This is a test pages';
    }
  }