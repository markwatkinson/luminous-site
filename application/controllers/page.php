<?php

class Page extends MY_Controller {

  public function __construct() {
    parent::__construct();
  }
  private function _load_text_page($name, $title=null) {
    $this->load->model('Text_model');
    $params = array();
    if ($title !== null) $params['title'] = $title;
    $this->_load_header($params);
    $params = array('content' => $this->Text_model->get($name));
    $this->load->view('pageview.php', $params);
    $this->_load_footer(array('modified' => $this->Text_model->change_time($name)));
  }

  // we're going to override the remap function to try to fetch anything in
  // the text model as a 'function'.
  public function _remap($method, $params=array()) {
    if (!method_exists($this, $method)) {
      try {
        // this shouldn't be here, but title case the method name for the
        // HTML <title>
        $title = ucwords(str_replace('-', ' ', $method));
        $this->_load_text_page($method, $title);
      } catch (exception $e) {
        // failed - send it back up to the parent's remap. This will deal with
        // 404s and whatnot.
        parent::_remap($method, $params);
      }
    } else {
      parent::_remap($method, $params);
    }
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
    $this->description = 'The features that make Luminous the best PHP syntax highlighter for you';
    $this->pages->set_active('Features');
    $this->_load_text_page('features');
  }
  
  public function faq() {
    $this->pages->set_active('FAQ');
    $this->_load_text_page('faq');
  }
  
  public function languages() {
    $this->description = 'Languages supported by the Luminous syntax highlighting engine';
    $this->pages->set_active('Languages');
    $this->_load_header();
    $this->load->view('languageview.php',
      array('content' => Luminous::scanners())
    );
    $this->_load_footer();
  }
}

