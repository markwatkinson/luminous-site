<?php

/*
 * This model covers most of the bases: anything that can be transcribed as
 * plain text is stored in a file in the 'text' directory. This
 * model effectively therefore just deals with that disk IO and decoding.
 *
 * The docs and demo pages are covered by different models.
 */

class Text_model extends CI_Model {
  
  private static $dir = './assets/text';

  public function __construct() {
    parent::__construct();
    $this->load->library('Markup');
    $this->load->helper('file');
  }

  private function _is_valid($filename) {
    return preg_match('/^[\w\-]*$/', $filename);
  }

  function index() {
    $output = array();
    foreach (glob(self::$dir . '/*') as $f) {
      $f = str_replace(self::$dir . '/', '', $f);
      if ($this->_is_valid($f)) $output[] = $f;
    }
    return $output;
  }

  function change_time($page) {
    if ($this->_is_valid($page) && file_exists(self::$dir . '/' . $page)) {
      return filemtime(self::$dir . '/' . $page);
    }
    return 0;
  }

  function get($page) {
    $path = self::$dir . '/' . $page;
    if ($this->_is_valid($page) && file_exists($path)) {
      return $this->markup->format( file_get_contents($path) );
    }
    else {
      throw new HTTPException(404);
    }
  }
  

}
