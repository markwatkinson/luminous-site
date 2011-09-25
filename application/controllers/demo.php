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
      $this->session->set_userdata('theme', 'zenophilia.css');
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
    $data = array();
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
      $data['id'] = $id;
      // this will throw a 404 if the ID does not exist
      $this->Demorecord_model->read($id);
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
  
}
