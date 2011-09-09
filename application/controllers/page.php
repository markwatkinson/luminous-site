<?php

class Page extends MY_Controller {

  public function __construct() {
    parent::__construct();
  }
  private function _load_text_page($name) {
    $this->load->model('Text_model');    
    $this->_load_header();
    $this->load->view('pageview.php', 
      array('content' => $this->Text_model->get($name)));
    $this->_load_footer();
  }

  public function index() {
    if (strpos($this->uri->uri_string, 'page/index') === false) {
      // As this is the entrance page, we might just be on '/' or
      // /index.php, we need to redirect to the full URL to avoid
      // duplicate pages
      redirect(site_url('page/index'), 'location', 301);
      exit(0);
    }
    $this->pages->set_active('Main');
    $this->load->model('Download_model');
    $model_data = $this->Download_model->get_current();
    $this->_load_header();
    $this->load->view('indexview.php', array('releases'=>$model_data));
    $this->_load_footer();
  }
  public function download() {
    redirect($this->pages->url('Download'), 'location', 301);
  }
  public function features() {
    $this->pages->set_active('Features');
    $this->_load_text_page('features');
  }
  
  public function faq() {
    $this->pages->set_active('FAQ');
    $this->_load_text_page('faq');
  }
  
  public function languages() {
    $this->pages->set_active('Languages');
    $this->_load_header();
    $this->load->view('languageview.php',
      array('content' => Luminous::scanners())
    );
    $this->_load_footer();
  }
}

