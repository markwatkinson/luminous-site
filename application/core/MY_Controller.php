<?php

class MY_Exception extends Exception {
}
class Exception404 extends MY_Exception {
}

class MY_Controller extends CI_Controller {
  
  public $scripts = array();

  const ASSETS_DIR = './assets/';
  
  function __construct() {
    parent::__construct();
    $this->load->helper('url');
    $this->load->helper('assets');
    $this->load->helper('luminous');
    $this->load->helper('text');
    $this->load->helper('misc');
    $this->load->library('pages');

    $this->pages->add_page('Main', 'page/index');
    $this->pages->add_page('Download', 'download/');
    $this->pages->add_page('Features', 'page/features');
    $this->pages->add_page('Languages', 'page/languages');
    $this->pages->add_page('Online Demo', 'demo/');
    $this->pages->add_page('FAQ', 'page/faq');
    $this->pages->add_page('Documentation', 'docs/show/index');

    $this->load->library('ci-script-loader/scripts');
    $this->scripts->js('jquery-1.6');
    $this->scripts->js('jknotify');
    $this->scripts->js('visuals');
    $this->scripts->css('main.css');
  }

  public function _remap($method, $params = array()) {
    try {
      if (!method_exists($this, $method))
        throw new Exception404();
      return call_user_func_array(array($this, $method), $params);
    } catch(Exception404 $e) {
      $this->show_404();
    }
  }

  protected function _load_header($data=array()) {
    $this->load->view('headerview.php', $data);
  }
  protected function _load_footer() {
    $this->load->view('footerview.php');
  }

  protected function _load_404($data=array()) {
    $this->load->view('404.php', $data);
  }

  protected function show_404() {
    $this->output->set_output('');
    $this->output->set_status_header('404');
    $this->_load_header();
    $this->_load_404();
    $this->_load_footer();
  }


  protected function load_js($script, $exact=false) {
    $script_dir = self::ASSETS_DIR . 'script/';
    if (!$exact) {
      $matches = glob($script_dir . "/*$script*.js");
      if (empty($matches)) return false;
      foreach($matches as $m) {
        $this->scripts[] =  substr($m, strlen($script_dir)+1);
      }
    } else {
      if (file_exists($script_dir . '/' . $script))
        $this->scripts[] = $script;
      else return false;
    }
  }

  public function assets_url($asset) {
    return $this->assets['url'] . rtrim(htmlentities($asset), '/');
  }
}
