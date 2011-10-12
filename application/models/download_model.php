<?php
class Download_model extends CI_Model {
  const DOWNLOAD_DIR = './assets/downloads';
  const SHA1S_FILE = 'checksums.sha1';
  private $sha1s = array();

  private static function format($name) {
    if (preg_match('/\.zip$/', $name)) return 'zip';
    elseif(preg_match('/\.tar(\.bz2?|\.gz)?$|.t(gz|bz2?)?$/', $name)) 
      return 'tarball';
    else return 'unknown';
  }
  private function _load_sha1s(){
    $s = @file_get_contents(self::DOWNLOAD_DIR . '/' . self::SHA1S_FILE);
    if ($s) {
      $s_ = explode("\n", $s);
      foreach($s_ as $line) {
        $line = trim($line);
        $split = preg_split('/\s+/', $line);
        if (count($split) !== 2) continue;
        list($file, $hash) = $split;
        $this->sha1s[$file] = $hash;
      }
    }
  }
  
  private function _get_release_files($version) {
    $return = array();
    $files = glob(self::DOWNLOAD_DIR . '/*' . $version . '.*');
    $this->_load_sha1s();
    foreach($files as $f) {
      // cache the sha1s or this might start getting intensive
      if (preg_match('/\\.sha1$/', $f)) continue;
      $filename = preg_replace('@.*/@', '', $f);
      $sha1 = null;
      if (!isset($this->sha1s[$filename])) {
        $sha1 = sha1(file_get_contents($f));
        @file_put_contents(self::DOWNLOAD_DIR . '/' . self::SHA1S_FILE,
          "$filename $sha1\n", FILE_APPEND|LOCK_EX);
      } else {
        $sha1 = $this->sha1s[$filename];
      }
      $file = array('format' => self::format($f),
		    'filename' => $filename,
		    'size' => filesize($f),
		    'sha1' => $sha1
		    );    
      $return[] = $file;
    }
    return $return;
  }

  public function get_current() {
    $return = array();
    $lines = file(self::DOWNLOAD_DIR . '/release');
    if ($lines === false || empty($lines))
      return $return;
    foreach($lines as $line) {
      $line = trim($line);
      if ($line === '') continue;
      $data = array();      
      list($release, $date) = explode('|', $line);
      $release = trim($release);
      $date = trim($date);
      $data['release_number'] = $release;
      $data['release_date'] = $date;
      $data['files'] = $this->_get_release_files($release);
      $return[] = $data;
    }
    return $return;
  }

  public function current_release() {
    $c = $this->get_current();
    return $c[0];
  }
}
