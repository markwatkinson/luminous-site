<?php

class Demo extends MY_Controller {

  public $CODE_MAX = 102400; // byte limit
  public $BROWSE_MAX = 20; // demos per page

 
  
  public function __construct() {
    parent::__construct();
    $this->pages->set_active('Online Demo');
    $this->load->database();

    $t = $this->input->post('theme');
    if ($t && luminous::theme_exists($t)) {
      $this->session->set_userdata('theme', $t);
    }
    if (!$this->session->userdata('theme')) {
      $this->session->set_userdata('theme', $this->default_theme);
    }
    $this->scripts->js('bytelimit');
  }

  protected function _load_header($data=array()) {
    $theme = $this->session->userdata('theme');
    $data['theme'] = $theme? $theme : null;
    parent::_load_header($data);
    $this->load->view('demoheaderview.php');
  }


  public function show($id) {
    $this->load->model('Demorecord_model');
    
    $this->Demorecord_model->read($id);
    
    $this->_load_header();
    $this->load->view('demooutview.php',
      array('demo' => $this->Demorecord_model)
    );
    $this->_load_footer();
    
  }
  
  // XXX it seems that we have to have the offset as the last segment
  // or codeigniter will drop the remaining URL segments. There may be a
  // workaround but I couldn't find any that work.
  function browse($language_='all', $sort='time', $sort_dir='down', $offset=0) {
    // XXX: get/post variables don't play nicely with url segments,
    // but are necessary for the <select>, so redirect to the URL segment.
    if ($this->input->post('language') !== false) {
      header ('Location: '. 
        site_url("demo/browse/" . $this->input->post('language') . "/"));
      exit();
    }

    $this->load->model('Demo_model');
    $this->load->library('pagination');

    $this->_load_header();

    $language_ = luminous_decode_language($language_);
    $language = $language_;

    if ($language === 'all') $language = false;

    $demos = $this->Demo_model->get_all(
      $language,
      $offset,
      $sort,
      $sort_dir==='down',
      $this->BROWSE_MAX
    );

    $count = $this->Demo_model->get_count($language);

    $this->pagination->initialize( array(
      'base_url' => site_url("demo/browse/{$language_}/{$sort}/{$sort_dir}"),
      'total_rows' => $count,
      'per_page' => $this->BROWSE_MAX,
      'uri_segment' => 6,
    ));

    $this->load->view('demolistview.php', 
      array('demos'=> $demos, 
      'language' => $language_,
      'page' => $offset,
      'sort_dir' => $sort_dir,
      'sort_by' => $sort,
      )
    );
    $this->_load_footer();
  }

  function index() {
    $this->browse();
  }


  public function _do_paste_or_edit($errors=array()) {
    $this->_load_header();
    $this->load->view('demoview.php',
      array('demo' => $this->Demorecord_model,
        'errors' => $errors
        )
      );
    $this->_load_footer();
  }

  public function _do_paste_or_edit_post() {
    $errors = array();
    $data = array(); //user submitted data
    foreach(array('submitter', 'description', 'language', 'raw') as $field) {
      $post = $this->input->post($field);
      if ($post === false) {
        // this implies a bot or something mangling the form
        $errors[] = 'Missing post field: ' . $field;
      } else {
        $data[$field] = $post;
      }
    }
    $id = $this->input->post('id');
    
    if ($id !== false) {
      // edited submission
      $data['id'] = $id;
      // this will throw a 404 if the ID does not exist
      $this->Demorecord_model->read($id);
      // security check, not sure if this is redundant or not
      if (!$this->Demorecord_model->editable) {
        throw new HTTPException(403);
      }
    } else {
      // we only check for the editable input if it's a new submission
      // otherwise people could set previous editables to non-editable
      $data['editable'] = (bool)$this->input->post('editable');
    }

    $data['submitter'] = trim($data['submitter']);
    $data['description'] = trim($data['description']);
    $data['scanner'] = luminous_decode_language($data['language']);
    $data['size'] = strlen($data['raw']);
    unset($data['language']); // don't need this anymore

    // this is a language code for Luminous
    $language_code = luminous_language_to_code($data['scanner']);
    $code_length = strlen($data['raw']);

    // now let's populate the active record so far, and then if there are no
    // errors we highlight it and save it
    $this->Demorecord_model->set($data);

    if ($language_code === null)
      $errors[] = 'No such language';
    if ($code_length > $this->CODE_MAX) {
      $errors[] = sprintf('Your code was %d bytes too long',
        $code_length - $this->CODE_MAX);
    }
    elseif($code_length === 0) {
      $errors[] = 'Please enter some code';
    }

    if (!empty($errors)) {
      // we hit some errors, so load the paste view with the demo so far
      // and the list of errors
      $this->_do_paste_or_edit($errors);
      return;
    }

    // if we get to here, the post looks okay
    $this->Demorecord_model->highlighted = luminous::highlight($language_code,
      $this->Demorecord_model->raw);

    if ($this->input->post('save')) {
      $this->Demorecord_model->save();
      // now redirect to the permaURL
      redirect(site_url('/demo/show/' . $this->Demorecord_model->id));
    }
    else {
      // otherwise this is just a one off
      $this->_load_header();
      $this->load->view('demooutview.php',
        array('demo' => $this->Demorecord_model));
      $this->_load_footer();
    }
  }

  
  public function edit($id='') {
    if (!is_numeric($id)) throw new Exception404();
    $this->load->model('Demorecord_model');
    $this->Demorecord_model->read($id);
    if (!$this->Demorecord_model->editable) {
      // I can't let you do that, Dave
      throw new HTTPException(403);
    }
    $this->_do_paste_or_edit();
  }

  public function paste($language=false) {
    $this->load->model('Demorecord_model');

    if ($this->input->post('post')) {
      // post request
      $this->_do_paste_or_edit_post();
    } else {
      // not a post request
      if ($language !== false) {
        $this->Demorecord_model->scanner = luminous_decode_language($language);
      }
      $this->_do_paste_or_edit();
    }
  }

  public function embed($id=false, $theme=false) {
    if ($id === false) {
      // we don't want the overloaded version
      parent::_load_header();
      $this->load->view('demoembedview.php');
      $this->_load_footer();
      return;
    }
    $this->load->model('Demorecord_model');
    $this->Demorecord_model->read($id);
    $callback = $this->input->get('callback');
    $json_ = array(
      'code' => $this->Demorecord_model->highlighted,
      'layout' => assets_url('luminous/style/luminous.css'),
      'theme' => ($theme && in_array($theme, luminous::themes()))?
        assets_url('luminous/style/' . $theme)
        : '',
      'language' => $this->Demorecord_model->scanner
    );
    if ($callback) {
      header('Content-type: text/javascript');
      echo $callback . '(' . json_encode($json_) . ');';
    } else {
      header('Content-type: application/json');
      echo json_encode($json_);
    }
    

  }

  private function _test_embed($id, $theme) {
//     $this->_load_header();
    $url = site_url("demo/embed/$id/$theme");
    $f = <<<EOF
<!doctype html>
<html>
  <head>
    <script type='text/javascript' src='%s'></script>
    <script>
      $(document).ready(function() {
      $.getJSON('$url',
        function(data) {
          $('head').append($('<link rel="stylesheet" type="text/css">').attr('href', data.layout));
          $('head').append($('<link rel="stylesheet" type="text/css">').attr('href', data.theme));
          $('body').append('Language: ' + data.language);
          $('body').append('<br/>Path to theme: ' + data.theme);
          $('body').append($(data.code));
        });
      });
    </script>
  </head>
  <body>
  </body>
</html>
EOF;
  $f = sprintf($f, assets_url('/script/jquery-1.6.2.min.js'));
    $this->output->append_output($f);
//     $this->_load_footer();
  }

  public function test($arg0) {
    $args = func_get_args();
    if ($args[0] === 'embed') {
      $this->_test_embed($args[1], isset($args[2])? $args[2] : $this->session->userdata('theme'));
    }
  }
  
}
