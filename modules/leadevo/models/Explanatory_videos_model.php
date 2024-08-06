<?php defined('BASEPATH') or exit('No direct script access allowed');

class Explanatory_videos_model extends CI_Model
{
    protected $table = 'tblleadevo_explanatory_videos'; // Correct table name
    protected $db_leadevo_marketplace;

    public function __construct()
    {
        parent::__construct();
        // Load the database connection for 'leadevo_marketplace'
        $this->db_leadevo_marketplace = $this->load->database('leadevo_marketplace', true);
    }

    // Fetch all explanatory videos
    public function get_all()
    {
        return $this->db_leadevo_marketplace->get($this->table)->result_array();
    }

    // Insert a new explanatory video
    public function insert($data)
    {
        return $this->db_leadevo_marketplace->insert($this->table, $data);
    }

    // Update an existing explanatory video
    public function update($id, $data)
    {
        $this->db_leadevo_marketplace->where('id', $id);
        return $this->db_leadevo_marketplace->update($this->table, $data);
    }

    // Delete an explanatory video
    public function delete($id)
    {
        $this->db_leadevo_marketplace->where('id', $id);
        return $this->db_leadevo_marketplace->delete($this->table);
    }

    // Get a single explanatory video by ID
    public function get($id)
    {
        $this->db_leadevo_marketplace->where('id', $id);
        return $this->db_leadevo_marketplace->get($this->table)->row_array();
    }
}
