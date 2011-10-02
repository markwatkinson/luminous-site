<?php

/**
 * This is essentially an Active-Record for the Demo table.
 * This is really the only place we currently want an active-record, so there's
 * no higher abstraction for now.
 */
class Demorecord_model extends CI_Model {

  // it's probably easiest if we init all of these to empty types, but leave
  // id as null so we can definitely check whether or not it really exists
  // yet
  public $id = null;
  public $time = 0;
  public $scanner = '';
  public $size = '';
  public $submitter = '';
  public $description = ''; 
  public $raw = '';
  public $highlighted = '';
  public $editable = false;

  public function Demorecord_model() {
    parent::__construct();
  }


  /**
   * Returns the record as an assoc. array. This is used internally and maybe
   * could be generally useful
   *
   * This is read only. Use $this's properties if you want to write.
   */
  public function record() {
    // FIXME: is there some magic to automate this?
    return array(
      'id' => $this->id,
      'time' => $this->time,
      'scanner' => $this->scanner,
      'size' => $this->size,
      'submitter' => $this->submitter,
      'description' => $this->description,
      'raw' => $this->raw,
      'highlighted' => $this->highlighted,
      'editable' => $this->editable,
    );
  }

  /**
   * Reads the record from an array
   */
  public function set($array, $set_time=true) {
    foreach($array as $k=>$v) {
      if (!property_exists($this, $k)) {
        throw new Exception('No such property ' . $k);
      }
      $this->$k = $v;
    }
    if ($set_time) {
      $this->time = time();
    }
  }
  

  public function read($id = 'random') {
    $query = ($id == 'random')?
      $this->db->query('SELECT * FROM luminous ORDER BY RAND() LIMIT 1')
      : $this->db->get_where('luminous', array('id'=>$id));

    if (!$query->num_rows()) {
      throw new Exception404();
    } else {
      foreach($query->row_array() as $k=>$v) {
        $this->$k = $v;
      }
    }
  }


  public function delete() {
    $this->db->where('id', $this->id);
    $this->db->delete('luminous');
  }

  public function save() {
    $record = $this->record();
    if ($this->id === null) {
      // this is a new record, we have to insert it
      unset($record['id']); // don't insert the non-existent ID
      $this->db->insert('luminous', $record);
      // set the ID
      $this->id = $this->db->insert_id();
    } else {
      // this is an old record being updated
      unset($record['id']);
      $this->db->where('id', $this->id);
      $this->db->update('luminous', $record);
    }
  }

}  