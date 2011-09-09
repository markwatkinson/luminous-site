<?php 
class Download extends MY_Controller {
  const DOWNLOAD_DIR = './assets/downloads';
  const LOG_FILE = './assets/download_log';

  public function __construct() {
    parent::__construct();
    $this->pages->set_active('Download');
  }

  public function index() {
    $this->load->model('Download_model');
    $model_data = $this->Download_model->get_current();
    $this->_load_header();
    $this->load->view('downloadview.php', array('releases' => $model_data));
    $this->_load_footer();
  }

  public function get($file=null) {
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
}
