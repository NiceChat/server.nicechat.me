<?php
class Pages extends CI_Controller {
  // 试图类
  public function view($page = 'home') {
    // echo 'hello world';exit;
    if( ! file_exists(APPPATH.'views/pages/'.$page.'.php')) {
      // Ooops, we don`t have a page for that
      show_404();
    }
    // 首字母大写
    $data['title'] = ucfirst($page);

    $this->load->view('templates/header', $data);
    $this->load->view('pages/'.$page, $data);
    $this->load->view('templates/footer', $data);
  }
}