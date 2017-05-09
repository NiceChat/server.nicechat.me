<?php
  /**
   * wx 测试微信订阅号相关
   */
  class Wx extends CI_Controller
  {
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
      $this->load->view('wx/index');
    }
  }