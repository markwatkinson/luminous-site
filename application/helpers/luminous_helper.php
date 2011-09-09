<?php 
set_time_limit(TIME_LIMIT);

require_once LUMINOUS . '/luminous.php';

function luminous_code_to_language($code) {
  foreach(luminous::scanners() as $lang=>$codes)
    if (in_array($code, $codes)) return $lang;
  return null;
}

function luminous_language_to_code($language) {
  $s = luminous::scanners();
  if (isset($s[$language])) return $s[$language][0];
  return null;  
}


// helper functions to allow us to use language names for URL segments.
// using URL encode seems to break CI.
function luminous_encode_language($language) {
  return str_replace(
    array('+', '-', '/', '#', ' '),
    array('plus', 'dash', 'slash', 'hash', '-'),
    $language
  );
}
function luminous_decode_language($language) {
  return str_replace(
    array('-', 'plus', 'dash', 'slash', 'hash'),
    array(' ', '+', '-', '/', '#'),
    $language
  );
}

function luminous_sql($sql) {
  $CI =& get_instance();
  $CI->load->database();
  $q = $CI->db->query($sql);
  if (is_bool($q)) return $q;
  $ret = array();
  if ($q->num_rows()) {
    foreach($q->result_array() as $row)
      $ret[] = $row;
  }
  return $ret;
}

luminous::set('sql_function', 'luminous_sql');