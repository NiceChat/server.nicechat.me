<?php
  /**
   * wx 测试微信订阅号相关
   */
  class Wechat extends CI_Controller
  {
    public $access_token;

    public function __construct()
    {
      parent::__construct();
      // 加载数据模型
      $this->load->model('wechat_model');
      // 加载辅助类
      $this->load->library('LogLeiLei');
    }

    // 此方法是给微信验证服务器
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

    // 提供给前端获取微信授权内容
    public function getAuth()
    {
      $url = isset($_POST['url']) ? $_POST['url'] : '';
      if (empty($url)) {
        echo json_encode(array('code' => -1, 'errmsg' => '传入参数有误！'));
        return false;
      }
      $result = $this->wechat_model->query_token();
      $current_time = (int) time();
      // 确保
      if (!isset($result -> access_token)) {
        self::update_token();
      } else {
        // 存放token的时间超过微信设置的7200s,需要重新获取下token
        if ($current_time > (int) $result -> expires_in) {
          self::update_token();
        }
      }
      // 重新获取一次
      self:: get_jsapi_ticket($url);
    }

    // 通过access_token换区jsapi_ticket
    public function get_jsapi_ticket($path)
    {
      $token = $this->wechat_model->query_token();
      $access_token = $token -> access_token;
      $ticket = $this->wechat_model->query_ticket();
      $current_time = time();
      $path = urldecode($path);
      // 查看ticket是否存或者在是否过期
      if (!isset($ticket['jsapi_ticket']) || ($current_time > (int) $ticket['expires_in'])) {
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
        // 初始化
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);
        $result = json_decode($result, true);
        self::log((string) json_encode($result), 'GetTicketFromWechat');
        if ($result['errcode'] == 0) {
          $ticket['jsapi_ticket'] = $result['ticket'];
          // 成功写入到库
          $result['expires_in'] = $time = (int) time() + 7000;
          $this->wechat_model->update_ticket($result);
        } else {
          echo json_encode(array('code' => $result['errcode'], 'errmsg' => $result['errmsg']));
        }
      }
      $noncestr = md5(uniqid());
      $timestamp = time();
      // 确保了jsapi_ticket 生成鉴权字段送到前端
      $string = 'jsapi_ticket='.$ticket['jsapi_ticket'].'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$path;
      $data['code'] = 0;
      $data['data']['signature'] = sha1($string);
      $data['data']['noncestr'] = $noncestr;
      $data['data']['appId'] = 'wxc873affaabdc40d5';
      $data['data']['timestamp'] = $timestamp;
      $data['data']['orginal'] = $string;
      echo json_encode($data);
    }

    public function update_token()
    {
      $row = json_decode(self::get_token_url(), true);
      $arr['access_token'] = $row['access_token'];
      $time = (int) time() + 7000;
      $arr['expires_in'] = $time;
      $this->wechat_model->update_token($arr);
    }

    public function get_token_url()
    {
      $appid = 'wxc873affaabdc40d5';
      $secret = 'a0bea4cf88ab23bf3108e8d27f6621ab';
      $grant_type = 'client_credential';
      $host_name = 'https://api.weixin.qq.com/cgi-bin/token';
      $url = $host_name.'?grant_type='.$grant_type.'&appid='.$appid.'&secret='.$secret;
      // 初始化
      $ch = curl_init($url);
      // Disable SSL verification
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      // Will return the response, if false it print the response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Execute
      $result = curl_exec($ch);
      // Closing
      curl_close($ch);
      self::log((string) json_encode($result), 'GetTokenFromWechat');
      return $result;
    }

    // 生成日志 写入run.log日志文件
    public function log($str, $action) 
    {
      $timezone = "PRC";
      if(function_exists('date_default_timezone_set')){
        date_default_timezone_set($timezone);
      }
      $res = date("Y-m-d H:i:s").'【'.$action.'】'.$str;
      $this->logleilei->write_log($res);
    }
  }