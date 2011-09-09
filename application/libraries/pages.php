<?php 

class Luminous_page {
  public $name;
  public $url;
  public $active = false;
  public $children = array();
  public $parent = null;
  public function __construct($name, $url) {
    $this->name = $name;
    $this->url = $url;
  }
  public function add_child($page) {
    $this->children[] = $page;
    $page->parent = $this;
  }
}

class Pages {
  public $pages = array();
  public $active = null;

  public function search($name) {
    foreach($this->pages as $p) {
      if ($p->name === $name) return $p;
    }
    throw new Exception('No such page');
  }

  public function set_active($name=null) {
    if ($this->active !== null) $this->active->active = false;
    $page = $this->search($name);
    $page->active = true;
    $this->active = $page;
  }

  public function active_name() {
    if ($this->active) return $this->active->name;
    return null;
  }

  public function add_page($name, $url=null) {
    if ($url === null) $p = $name;
    else $p = new Luminous_page($name, $url);
    $this->pages[] = $p;
  }

  public function url($page_name) {
    $page = $this->search($page_name);
    return $page->url;
  }
}
