<?php
class PermissionException extends Exception {}

class Webservice extends MY_Controller {

  public function __construct() {
    parent::__construct();
    header('Access-Control-Allow-Origin: *');
  }

  const LIMIT = 102400;


  private function _forbid() {
    $this->output->set_status_header('403');
    echo '403 Forbidden';
    return;
  }
  
  private function _index() {
    if (empty($_POST))
      throw new PermissionException();
    $code = $this->input->post('requests');
    if ($code === false) throw new PermissionException();
    if (count($code) > 20) throw new PermissionException();

    $outputs = array();
    foreach($code as $c) {

      $language = isset($c['language'])? $c['language'] : 'plain';
      $code = isset($c['code'])? $c['code'] : false;
      $line_numbers = isset($c['line_numbers'])? $c['line_numbers'] === 'true' : true;
      $format = (isset($c['inline']) && $c['inline'] === 'true')?  'html-inline' : 'html';
      $wrap = isset($c['wrap'])? (int)$c['wrap'] : 0;
      $settings = array(
          'line_numbers' => $line_numbers,
          'format' => $format,
          'wrap_width' => $wrap,
      );
      luminous::set($settings);
      if ($code === false) throw new PermissionException();
      if (strlen($code) > self::LIMIT) throw new PermissionException();
      $outputs[] = luminous::highlight($language, $code);
    }
    return json_encode($outputs);
  }
  public function index() {
    try {
      if (($callback = $this->input->get('callback')) === false) {
        throw new PermissionException();
      }
      $output = $this->_index();
      echo "$callback($output);"; 
    }
    catch (PermissionException $e) {
      $this->_forbid();
    }
  }

  public function test() {
    ?>
    <?= luminous::head_html() ?>
    <script src=http://code.jquery.com/jquery-1.6.1.js></script>
    <script type='text/javascript'>
    $(document).ready(function() {
      $.post('<?=site_url('webservice')?>', {
        requests: [{
          code:  $('pre').html(),
          language: 'js'
        }]
      }, function(data) {
        $('pre').text(data);
      });
    })
    </script>
    <pre> alert('hello world') </pre>
    <?php
  }

}

