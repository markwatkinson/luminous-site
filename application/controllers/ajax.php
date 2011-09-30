<?php

/**
 * Handles various AJAX-like requests and responses which don't relate to
 * any particular controller.
 */

class Ajax extends MY_Controller {

  // in case we want more than just JSON at some point.
  // like that's going to happen
  private function _encode($data) {
    return json_encode($data);
  }

  private function _luminous_set_theme($theme) {
    if (in_array($theme, luminous::themes())) {
      $this->session->set_userdata('theme', $theme);
    } else {
      throw new Exception404();
    }
  }
  private function _luminous_list_themes() {
    return luminous::themes();
  }
  private function _luminous_get_theme() {
    return $this->session->userdata('theme');
  }
  /**
   * public interface.
   * 
   * Currently recognised arguments:
   * set, theme-name
   * current - returns the current theme
   * list  - returns a list of server side themes
   */
  public function luminous(/*variadic*/) {
    // in future if this is expanded it might need a better way to parse the
    // argument tree
    $args = func_get_args();
    if (empty($args)) return;
    if ($args[0] === 'theme') {
      if (!isset($args[1])) return;
      if ($args[1] === 'list') {
        echo $this->_encode($this->_luminous_list_themes());
        return;
      } elseif ($args[1] === 'set') {
        if (!isset($args[2])) return;
        $this->_luminous_set_theme($args[2]);
        return;
      } elseif($args[1] === 'current') {
        echo $this->_encode($this->_luminous_get_theme());
        return;
      }
    }
  }
  public function index() {}
}
