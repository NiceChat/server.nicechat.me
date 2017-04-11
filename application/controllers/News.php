<?php
  /**
  * news
  */
  class News extends CI_Controller
  {
    
    public function __construct()
    {
      parent:: __construct();
      $this->load->model('news_model');
      $this->load->helper('url_helper');
    }

    // 列表
    public function index()
    {
      $data['news'] = $this->news_model->get_news();
      $data['title'] = 'News archive';

      $this->load->view('templates/header', $data);
      $this->load->view('news/index', $data);
      $this->load->view('templates/footer');
    }

    // 详情
    public function view($slug = NULL)
    {
      $data['news_item'] = $this->news_model->get_news($slug);
      if(empty($data['news_item']))
      {
        show_404();       
      }
      $data['title'] = $data['news_item']['title'];

      $this->load->view('templates/header', $data);
      $this->load->view('news/view', $data);
      $this->load->view('templates/footer');
    }

    // 新增
    public function add()
    {
      $this->load->helper('form');
      $this->load->library('form_validation');

      $data['title'] = 'Add a news item';

      $this->form_validation->set_rules('title', 'Title', 'required');
      $this->form_validation->set_rules('text', 'Text', 'required');
      
      if ($this->form_validation->run() === FALSE)
      {
        $this->load->view('templates/header', $data);
        $this->load->view('news/add');
        $this->load->view('templates/footer');
      }
      else
      {
        $this->news_model->set_news();
        $this->load->view('news/success');
      }
    }

    public function success()
    {
      $this->load->view('templates/header', $data);
      $this->load->view('news/success');
      $this->load->view('templates/footer');
    }
  }