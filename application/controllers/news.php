<?php

class News extends MY_Controller {

  private $remote_index = 'http://blog.asgaard.co.uk/t/luminous/news?f=rss&p=%d';
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
      throw new HTTPException(404);
    }
    $this->_load_header(array(
      'extra_head' 
        => "<link rel='canonical' href='$canonical'/>"
    ));
    $this->load->view('news/item.php', array('item' => $item, 'canonical' => $canonical));
    $this->_load_footer();
  }
  
  public function index() {
    $this->load->library('pagination');  
    $offset = (int)$this->input->get('p');    
    $url = sprintf($this->remote_index, $offset);
    //echo $url; die();
    $feed = $this->simplepieloader->feed($url);
    $items = $feed->get_items();
    $this->_load_header(array(
      'extra_head' 
        => "<link rel='canonical' href='{$url}'/>"
    ));    
    if ($items) {
      $count_ = $feed->get_channel_tags('', 'count');
      $count = 0;
      if (isset($count_[0]) && isset($count_[0]['data']))
        $count = (int)$count_[0]['data'];
      $this->pagination->initialize(array(
        'base_url' => current_url() . '?',
        'total_rows' => (int)$count,
        'per_page' => 10,
        'page_query_string' => true,
        'query_string_segment' => 'p'
      ));
      $this->load->view('news/index.php', array('items' => $items));
    }
    $this->_load_footer();
  }
}