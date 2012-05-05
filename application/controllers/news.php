<?php

class News extends MY_Controller {

  private $remote_index = 'http://blog.asgaard.co.uk/t/luminous/news?f=rss';
  private $remote_item = 'http://blog.asgaard.co.uk/%s?f=rss';
  private $canonical = 'http://blog.asgaard.co.uk/%s';
  
  public function News() {
    parent::__construct();
    $this->load->library('Simplepieloader');
    $this->scripts->css('news.css');
  }
  
  public function story($story) {
    $args =  func_get_args();
    $story = implode('/', $args);
    $url = sprintf($this->remote_item, $story);
    $canonical = sprintf($this->canonical, $story);
    $feed = $this->simplepieloader->feed($url);
    $item = $feed->get_item();
    if (!$item) {
      echo $url; die();
      throw new HTTPException(404);
    }
    $this->_load_header(array(
      'extra_head' 
        => "<link rel='canonical' href='$canonical'/>"
    ));
    $this->load->view('news/item.php', array('item' => $item));
    $this->_load_footer();
  }
  
  public function index() {
    $feed = $this->simplepieloader->feed($this->remote_index);
    $items = $feed->get_items();
    
    $this->_load_header(array(
      'extra_head' 
        => "<link rel='canonical' href='{$this->remote_index}'/>"
    ));    
    if ($items) {
      $this->load->view('news/index.php', array('items' => $items));
    }
    $this->_load_footer();
  }
}