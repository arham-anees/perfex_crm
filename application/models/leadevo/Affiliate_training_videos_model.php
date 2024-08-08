<?php defined('BASEPATH') or exit('No direct script access allowed');

class Affiliate_training_videos_model extends CI_Model
{
    protected $table = 'tblleadevo_affiliate_training_videos';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $query = $this->db->get($this->table);
        if ($query === FALSE) {
            log_message('error', 'Query failed in get_all method.');
            return [];
        }
        return $query->result_array();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row_array();
    }
}
