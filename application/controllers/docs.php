<?php

class Docs extends MY_Controller {

  private $doc_page = null;
  
  public function __construct() {
    parent::__construct();
    $this->load->model('Docs_model');
    $super_page_name = 'Documentation';
    $this->pages->set_active($super_page_name);
    $this->doc_page = $this->pages->search($super_page_name);

    $this->description = 'Documentation, help and instructions for using
      and extending Luminous PHP syntax highlighter';
  }

  public function index() {
    redirect($this->pages->url('Documentation'), 'location', 301);
    $pages = $this->Docs_model->index();
    $this->_load_header();
    $this->load->view('docsindexview.php', array('pages'=>$pages));
    $this->_load_footer();
  }

  public function map() {
    $this->_load_header();
    $this->load->view('mapview.php', array(
      'map' => $this->Docs_model->map,
      'url_prefix' => 'docs/show/',
      'title' => 'Documentation Map',
    ));
    $this->_load_footer();
  }

  public function show($page=null) {
    $content = ($page !== null)? $this->Docs_model->get($page) : false;
    if ($content === false) {
      throw new Exception404();
    }
    $this->Docs_model->set_active($page);
    $hierarchy = array();
    foreach($this->Docs_model->page_hierarchy as $p) {
      $hierarchy[] = array(str_replace('-', ' ', $p), 'docs/show/' . $p);
    }
    $this->_load_header(array('title'=>title_case($page) . ' Documentation'));
    $this->load->view('pageview.php', array(
      'content'=>$content,
      'page_hierarchy' => $hierarchy
      ));
    $this->_load_footer(array('modified' => $this->Docs_model->change_time($page)));
  }
}