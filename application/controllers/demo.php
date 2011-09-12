<?php

class Demo extends MY_Controller {
  
  const BROWSE_MAX = 20;
  const CODE_MAX = 51200; // byte limit
 
  
  public function __construct() {
    parent::__construct();
    $this->pages->set_active('Online Demo');
    $this->load->database();
    $this->load->model('Demo_model');
    
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

  protected function _add_ownership($id) {
    $ids = $this->session->userdata('owned_demos');
    if ($ids === false) $ids = array();
    $ids[] = $id;
    $this->session->set_userdata('owned_demos', $ids);
  }

  protected function _has_ownership($id) {
    $ids = $this->session->userdata('owned_demos');
    $is_owner = $ids !== false && is_numeric($id) && in_array($id, $ids);
    return $is_owner;
  }



  private function _load_out_view($data) {
    $data = array_merge(array(
        'output' => null,
        'scanner' => null,
        'code' => null,
        'theme' => $this->session->userdata('theme'),
        'themeable' => true,
        'error' => null,
        'description' => null,
        'submitter' => null,
        'size' => null,
        'save' => false,
        'ownership' => isset($data['id'])?
          $this->_has_ownership($data['id']) : false,
      ), $data);
    $this->load->view('demooutview.php', $data);
  }

  private function _do_update() {
    $id = $this->input->post('id');
    $lang = $this->input->post('lang');
    $return = array('output' => null, 'error' => null);
    if (luminous_language_to_code($lang) === null) {
      $return['error'] = 'Unrecognised language: ' . htmlentities($language);
      return $return;
    }
    if (!$this->_has_ownership($id)) {
      $this->output->set_status_header('403');
      $return['error'] = 'You do not have ownership of this demo';
      return $return;
    }
    $row = $this->Demo_model->get($id);
    if (!$row) {
      $return['error'] = 'No such ID';
      return $return;
    }
    $raw = $row['raw'];
    $highlighted = luminous::highlight(
      luminous_language_to_code($lang),
      $raw);
    $this->Demo_model->update_language($id, $lang, $highlighted);
    $return['output'] = $highlighted;
    return $return;
  }


  private function _do_submit() {
    $code = $this->input->post('code');
    $language = $this->input->post('lang');
    $description = $this->input->post('description');
    $submitter = $this->input->post('submitter');
    $output = null;
    $error = null;
    $size = strlen($code);
    $save = false;
    $id = false;

    if (luminous_language_to_code($language) === null) {
      $error = 'Unrecognised language: ' . htmlentities($language);
      $output = '';
    }
    elseif ($size > self::CODE_MAX) {
      $output = '';
      $error = 'Your code was past the limit of ' . self::CODE_MAX
        . ' bytes, by ' . ($size - self::CODE_MAX) . ' bytes.';
    } elseif(strlen(trim($code)) === 0) {
      $output = '';
      $error = 'Please enter some code';
    }
    else {
      $output = luminous::highlight(luminous_language_to_code($language), $code);
      if ($this->input->post('save')) {
        $save = true;
        $id = $this->Demo_model->insert($language,
          $code, $output, $description, $submitter);
        if ($id !== false)
          $this->_add_ownership($id);
      }
    }
    return array(
      'output' => $output,
      'error' => $error,
      'scanner' => $language,
      'description' => $description,
      'submitter' => $submitter,
      'size'=>$size,
      'save' => $save,
      'themeable' => false,
      'id' => $id,
      );
  }

  private function _do_lookup($id) {
    $data = $this->Demo_model->get(($id==='random')? false : $id );
    if ($data !== false) {
      return array(
        'output' => $output = $data['highlighted'],
        'scanner' => $data['scanner'],
        'description' => $data['description'],
        'submitter' => $data['submitter'],
        'size' => $data['size'],
      );
    }
    else {
      throw new Exception404();
    }
  }


  public function show($id=false) {
    $this->_load_header();
    $data = array();
    if ($this->input->post('change_language')) {
      $data = $this->_do_update();
    }

    $data = array_merge($data, $this->_do_lookup($id));

    $data = array_merge(array(
      'output' => null,
      'scanner' => null,
      'code' => null,
      'theme' => $this->session->userdata('theme'),
      'themeable' => true,
      'error' => null,
      'description' => null,
      'submitter' => null,
      'size' => null,
      'save' => false,
      'id' => $id,
    ), $data);
    $this->_load_out_view($data);

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
    
    $this->_load_header();
    
    $language_ = luminous_decode_language($language_);
    $language = $language_;

    if ($language === 'all') $language = false;
    
    $demos = $this->Demo_model->get_all($language, $offset, $sort,
      $sort_dir==='down', self::BROWSE_MAX);
    $count = $this->Demo_model->get_count($language);

    $this->load->library('pagination');
    $this->pagination->initialize( array(
      'base_url' => site_url("demo/browse/{$language_}/{$sort}/{$sort_dir}"),
      'total_rows' => $count,
      'per_page' => self::BROWSE_MAX,
      'uri_segment' => 6,
    ));

    
    $this->load->view('demolistview.php', 
      array('demos'=>$demos, 
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
  
  public function paste($language=false) {
    $this->_load_header();
    $post = $this->input->post('code') !== false;
    $load_demo_view = true;
    $data = array();
    if ($post) {
      $data = $this->_do_submit();
      $language = $this->input->post('lang');
    }

    if (!$post || isset($data['error'])) {
      $language = luminous_decode_language($language);
      $this->load->view('demoview.php',
        array(
          'language' => $language,
          'max' => self::CODE_MAX,
          'code' => $this->input->post('code', ''),
          'save' => $post? $this->input->post('save') : true,
          'submitter' => $this->input->post('submitter', ''),
          'description' => $this->input->post('description', ''),
          'error' => isset($data['error'])? $data['error'] : null
        ));
    }
    else {
      // redirect to permalink
      if ($data['save']) 
        redirect(site_url('/demo/show/' . $data['id']));
      else 
        $this->_load_out_view($data);
    }

    $this->_load_footer();
  }
  
}
