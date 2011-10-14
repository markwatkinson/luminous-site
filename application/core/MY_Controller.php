<?php

class MY_Exception extends Exception {
}
class HTTPException extends MY_Exception {
  public $code;
  public function HTTPException($code) {
    $this->code = $code;
  }
}

class Exception404 extends HTTPException {
  /** @deprecated
    * this class is a leftover, it shouldn't be used anymore
    * use HTTPException instead
    */
  public function Exception404() {
    parent::__construct(404);
  }
}

class MY_Controller extends CI_Controller {
  
  public $scripts = array();
  public $default_theme = 'zenophilia.css';

  /// page meta description
  /// has to be public for some reason, protected can't be read from views.
  public $description = 'Luminous is a PHP syntax highlighter.
  It focusses on modern features like CSS and providing the best quality
  highlighting for your website or blog';

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
    $this->scripts->set_dir('js', 'assets/script/');
    $this->scripts->set_dir('css', 'assets/style/');
    $this->scripts->js('jquery-1.6');
    $this->scripts->js('jknotify');
    $this->scripts->js('visuals');
    $this->scripts->css('main.css', true);
    $this->scripts->css('buttons.css', true);

    // set the default theme
    if ($this->session->userdata('theme') === false ||
        !in_array($this->session->userdata('theme'), luminous::themes())
      ) {
      $this->session->set_userdata('theme', $this->default_theme);
    }
    
  }

  public function _remap($method, $params = array()) {
    try {
      if (!method_exists($this, $method))
        throw new HTTPException(404);
      return call_user_func_array(array($this, $method), $params);
    } catch(HTTPException $e) {
      if ($e->code === 404) {
        $this->show_404();
      } else {
        $this->output->set_output('');
        $this->output->set_status_header($e->code);
        $this->_load_header();
        // TODO work this into a view
        $this->output->append_output("
        <h1>Oops!</h1>
        Sorry, looks like you made a bad request or something just went wrong.
        <br>
        Status code: {$e->code}");
        $this->_load_footer();
      }
    }
  }

  protected function _load_header($data=array()) {
    $this->load->view('headerview.php', $data);
  }
  protected function _load_footer($data=array()) {
    $this->load->view('footerview.php', $data);
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

  public function assets_url($asset) {
    return $this->assets['url'] . rtrim(htmlentities($asset), '/');
  }
}
