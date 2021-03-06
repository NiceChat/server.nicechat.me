<?php
  /**
   * api for blog
   */
  class Api extends CI_Controller
  {
    // 构造函数
    public function __construct()
    {
      parent:: __construct();
      $this->load->model('blogs_model');
    }

    // 提供七牛文件上传凭证
    public function sendUpToken()
    {
      // 加载辅助类
      $this->load->library('QiniuBirdge');
      $upToken = $this->qiniubirdge->sendUpToken();
      $data['code'] = 0;
      $data['upToken'] = $upToken;
      echo json_encode($data);
    }

    // 获取博客列表
    public function getBlogList($offset = 10, $page = 1)
    {
      header('content-type:application/json;charset=utf8');
      $offset = $_POST['offset'];
      $page = $_POST['page'];
      $data['list'] = $this->blogs_model->get_blogs($page, $offset);
      $data['code'] = 0;
      $data['offset'] = $offset; 
      $data['page'] = $page;

      echo json_encode($data);
    }

    // 获取单条博客信息
    public function getSingleBlog()
    {
      header('content-type:application/json;charset=utf8');
      $id = $this->input->post('id');
      $data['code'] = 0;
      $data['data'] = $this->blogs_model->get_one_blog($id);
      echo json_encode($data);
    }

    // 更新单条博客
    public function updateSingleBlog()
    {
      header('content-type:application/json;charset=utf8');
      $id = $this->input->post('id');
      $title = $this->input->post('title');
      $content = $this->input->post('content');
      $html = $this->input->post('html');
      $author = 'Yangleilei';
      $result = $this->blogs_model->update_one_blog($id, $title, $content, $html, $author);

      if ($result !== -1) {
        $data["code"] = 0;
        $data["message"] = '插入成功';
      }else{
        $data["code"] = 1;
        $data["message"] = '插入失败';
      }
      echo json_encode($data);
    }

    // 新增单条博客
    public function insertSingleBlog()
    { 
      header('content-type:application/json;charset=utf8');
      $title = $this->input->post('title');
      $content = $this->input->post('content');
      $html = $this->input->post('html');
      $ctime = time();
      $author = $this->input->post('author');
      $result = $this->blogs_model->insert_one_blog($title, $content, $html, $ctime, $author);

      if ($result !== -1) {
        $data["code"] = 0;
        $data["message"] = '插入成功';
      }else{
        $data["code"] = 1;
        $data["message"] = '插入失败';
      }

      echo json_encode($data);
    }

    // 删除单条博客
    public function delsingleBlog()
    {
      header('content-type:application/json;charset=utf8');
      $id = $this->input->post('id');
      if (!isset($id)) {
        $data['code'] = -1;
        $data['message']  = '无法删除，请传入删除id值';
        echo json_encode($data);
        exit; 
      }

      $result = $this->blogs_model->delete_one_blog($id);

      if ($result !== -1) {
        $data["code"] = 0;
        $data["message"] = '删除成功';
      }else{
        $data["code"] = 1;
        $data["message"] = '删除失败';
      }

      echo json_encode($data);
    }
  }