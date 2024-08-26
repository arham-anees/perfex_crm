<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lead_statuses_model extends CI_Model
{
    protected $table = 'tblleadevo_report_lead_statuses'; // Define the table name

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $this->db->where('is_active', 1);
        return $this->db->get($this->table)->result();
    }

    public function get($id)
    {
        $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('is_active', 1);
        return $this->db->where('id', $id)->delete($this->table);
    }
}
