<?php 

class Demo_model extends CI_Model {

  function get_count($language=false) {
    $this->db->select('id')->from('luminous');
    if ($language !== false)
      $this->db->where('scanner', $language);
    return $this->db->count_all_results();
  }
  
  function get_all($language=false, $offset=0, $sort='time', $desc=true, $number=20) {
    $this->db->select('id, time, scanner, size, description, submitter')->from('luminous');
    if ($language !== false)
      $this->db->where('scanner', $language);
    
    $this->db->order_by($sort, $desc? 'desc' : 'asc')->limit($number, $offset);
    $query = $this->db->get();
    $out = array();
    foreach($query->result_array() as $r) $out[] = $r;
    return $out;
  }
}