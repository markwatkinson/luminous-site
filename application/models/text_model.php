<?php

/*
 * This model covers most of the bases: anything that can be transcribed as
 * plain text is stored in a file in the 'text' directory. This
 * model effectively therefore just deals with that disk IO and decoding.
 *
 * The docs and demo pages are covered by different models.
 */

class Text_model extends CI_Model {
  
  private static $dir = './assets/text/';

  // this is basically a security check to limit the number of files we
  // can read from
  private static $page_to_uri = array(
    'index' => 'index',
    'download' => 'download',
    'features' => 'features',
    'faq' => 'faq'
  );
  
  private static $index_file = 'index';
  private static $download_file = 'download';
  private static $features_file = 'features';
  private static $faq_file = 'faq';

  public function __construct() {
    parent::__construct();
    $this->load->library('Markup');
    $this->load->helper('file');
  }

  function index() {
    return array_keys(self::$page_to_uri);
  }

  function change_time($page) {
    $file = isset(self::$page_to_uri[$page])? self::$page_to_uri[$page] : null;
    return ($file !== null)? filemtime(self::$dir . $file) : 0;
  }

  function get($page) {
    $file = isset(self::$page_to_uri[$page])? self::$page_to_uri[$page] : null;
    $text = ($file === null)? '' : read_file(self::$dir . $file);
    return $this->markup->format($text);
  }
  

}
