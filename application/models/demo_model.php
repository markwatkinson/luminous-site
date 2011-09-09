<?php 

class Demo_model extends CI_Model {
  
  
  function insert($language, $raw, $highlighted, $description, $submitter) {
    $query_text = <<<EOF
INSERT INTO luminous(time, scanner, size, description, raw, highlighted, submitter)
  VALUES(?, ?, ?, ?, ?, ?, ?)
EOF;

    $this->load->helper('luminous');
    if (luminous_language_to_code($language) === null)
      return false;
    $values = array(time(), $language, strlen($raw), $description, $raw, $highlighted, $submitter);
    $q = $this->db->query($query_text, $values);
    return $this->db->insert_id();
  }
  
  function get($id) {
    $query;
    if ($id === false) 
      $query = $this->db->query('SELECT * FROM luminous ORDER BY RAND() LIMIT 1');
    else
      $query = $this->db->query('SELECT * FROM luminous WHERE id=?', array($id));
    if (!$query->num_rows()) return false;
    return $query->row_array();
  }

  function update_language($id, $new_language, $new_output) {
    $this->db->query('UPDATE luminous SET scanner=?, highlighted=? WHERE id=?',
      array($new_language, $new_output, $id));
  }

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