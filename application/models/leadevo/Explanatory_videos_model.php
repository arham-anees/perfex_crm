<?php defined('BASEPATH') or exit('No direct script access allowed');

class Explanatory_videos_model extends CI_Model
{
    protected $table = 'tblleadevo_explanatory_videos'; // Correct table name


    public function __construct()
    {
        parent::__construct();
        // Load the database connection for 'leadevo_marketplace'
        $this->load->database();
    }

    // Fetch all explanatory videos
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    // Insert a new explanatory video
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Update an existing explanatory video
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Delete an explanatory video
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    // Get a single explanatory video by ID
    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row_array();
    }
}
