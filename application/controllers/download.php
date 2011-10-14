<?php 
class Download extends MY_Controller {
  const DOWNLOAD_DIR = './assets/downloads';
  const LOG_FILE = './assets/download_log';

  public function __construct() {
    parent::__construct();
    $this->load->model('Download_model');
    
    $this->pages->set_active('Download');
    $this->load->helper('downloads_helper');
    $this->scripts->js('hints.js');
  }

  private function _current() {
    $model_data = $this->Download_model->get_current();
    $release = $model_data[0];
    return $release;
  }

  protected function _load_footer($data=array()) {
    if (!isset($data['modified'])) {
      $c = $this->_current();
      $data['modified'] = $c['release_date'];
    }
    parent::_load_footer($data);
  }


  public function index() {
    $this->description = 'Download Luminous PHP syntax highlighter';
    $release = $this->_current();
    $this->_load_header();
    $this->load->view('downloads/current.php', array('release' => $release));
    $this->_load_footer();
  }

  public function archive() {
    $this->description = 'Download archive for Luminous Syntax Highlighter';
    $this->_load_header();
    $this->load->view('downloads/list.php');
    $this->_load_footer();
  }

  public function get($file=null) {
    // TODO move this to a model
    $this->load->helper('url');
    if ($file === null || !file_exists(self::DOWNLOAD_DIR . '/' . $file)
      || !preg_match('/^[\w\-\.]+$/', $file)) {
        throw new Exception404();
    }
    $fh = @fopen(self::LOG_FILE, 'a');
    if(@flock($fh, LOCK_EX)) {
      fwrite($fh, time() . " $file\n");
      flock($fh, LOCK_UN);
    }
    @fclose($fh);
    header('Location: ' . base_url() . 'assets/downloads/' . $file);
  }

  public function release($format='zip') {
    $current = $this->_current();
    foreach($current['files'] as $f) {
      if ($f['format'] === $format) {
        $this->get($f['filename']);
        exit(0);
      }
    }
    throw new HTTPException(404);
  }
}
