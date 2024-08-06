<?php defined('BASEPATH') or exit('No direct script access allowed');

class Campaigns_model extends CI_Model
{

    protected $table = 'tblleadevo_campaign'; // Define the table name

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
    public function get_campaign_statuses()
    {
        return $this->db->get('tblleadevo_campaign_statuses')->result_array();
    }
}
