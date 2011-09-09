<?php


class Docs_model extends CI_Model {
  
  private $DOCS_DIR = './assets/luminous/docs/site/';
  private static $legal_regex = '/^[a-zA-Z_0-9\\-]+$/';

  // an array of trees representing the page hierarchy. Each tree is represented
  // as the root node, and each node as elements:
  // 'children', 'name', 'indent'
  public $map = array();

  public $page_hierarchy = array();
  
  public function __construct() {
    parent::__construct();
    $this->load->helper('file');
    $this->load->helper('directory');
    $this->load->library('Markup');
    $this->markup->linker = array($this, 'markup_linker');
    $this->map = $this->_parse_map();
  }

  function markup_linker($url) {
    $fn = $this->DOCS_DIR . '/' . $url;
    if (file_exists($fn)) return array('uri' => site_url('/docs/show/' . $url));
    return $this->markup->linker($url);
  }
    
  static function legal($filename) {
    return !!preg_match(self::$legal_regex, $filename);
  }

  private function _parse_map() {
    $map_fn = $this->DOCS_DIR . '/map.meta';
    $array = array();
    $stack = array();
    $lines = @file($map_fn);
    if ($lines === false) return;
    $last_indent = -1;
    // the map is hierarchical and uses indents to specify parent/child levels
    foreach($lines as $l) {
      $trimmed = ltrim($l);
      $indent = strlen($l) - strlen($trimmed);
      $trimmed = rtrim($trimmed);
      $page = array(
        'children' => array(),
        'name' => $trimmed,
        'indent' => $indent
      );
      // we need to figure out what the correct parent is
      // if the current indent is <= parent's index, we need to keep popping
      // until the highest on the stack has a lower indent than ours.
      while( !empty($stack)
        &&  $indent <= $stack[count($stack)-1]['indent']) {
        $node = array_pop($stack);
        if (empty($stack)) {
          // we've emptied the stack, which means we're at the root of the tree
          // but there might be another tree.
          // we need to keep a note of this tree or else we'll lose it.
          $array[] = $node;
        } else {
          $stack[count($stack)-1]['children'][] = $node;
        }
      }
      $stack[] = $page;
    }
    // clean up
    // is this duplication necessary?
    while(!empty($stack)) {
      $node = array_pop($stack);
      if (empty($stack)) {
        $array[] = $node;
      } else {
         $stack[count($stack)-1]['children'][] = $node;
      }
    }
    return $array;
  }


  private function _set_active($page, $node, $parents) {
    $parents = array_merge($parents, array($node));
    if ($page == $node['name']) {
      $this->page_hierarchy = array();
      foreach($parents as $p) {
        $this->page_hierarchy[] = $p['name'];
      }
      return true;
    }
    else {
      foreach($node['children'] as $n) {
        if ($this->_set_active($page, $n, $parents))
          return true;
      }
      return false;
    }
  }
  
  public function set_active($page) {
    // sets the page as 'active', which means filling out the hierarchy list
    // remember that the map is a tree, but it may contain multiple nodes, in
    // which case it's an array of trees.
    foreach($this->map as $node) {
      if ($this->_set_active($page, $node, array())) break;
    }
  }

  private function _parse_meta(&$str) {
    $array = array();
    if (preg_match("/^(#.*(\r\n|[\r\n]))+/", $str, $m)) {
      $str = substr($str, strlen($m[0]));
      $lines = preg_split("/\r\n|[\r\n]/", $m[0]);
      foreach($lines as $l)  {
        $l = ltrim($l, '#');
        $l = explode(':', $l, 2);
        if (count($l) === 2) {
          list($key, $val) = $l;
          $array[trim($key)] = trim($val);
        }
      }
    }
    return $array;
  }
  
  function get($page) {
    if (!self::legal($page)) return false;
    $page = $this->DOCS_DIR . '/' . $page;
    $contents = @file_get_contents($page);
    if ($contents !== false) {
      $meta = $this->_parse_meta($contents);
      return $this->markup->format($contents);
    }
    else return false;
  }
  function index() {
    $files = directory_map($this->DOCS_DIR, 1);
    $files = array_filter($files, array($this, 'legal'));
    return $files;
  }

  function change_time($page) {
    if (!$this->legal($page)) return false;
    $fn = $this->DOCS_DIR . '/' . $page;
    if (!file_exists($fn)) return false;
    return filemtime($fn);
  }

}