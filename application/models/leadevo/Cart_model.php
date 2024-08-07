<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cart_model extends CI_Model
{

    protected $table = 'leadevo_cart'; // Define the table name

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('client_id', get_client_user_id());
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_cart_prospects()
    {
        if (!is_client_logged_in())
            return [];
        $sql = "SELECT DISTINCT p.* FROM tblleadevo_cart c
                INNER JOIN tblleadevo_prospects p ON p.id = c.prospect_id
                WHERE c.client_id = '" . get_client_user_id() . "';";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function add_item($data)
    {
        $data['client_id'] = get_client_user_id();
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function remove_item($id)
    {
        $this->db->where('client_id', get_client_user_id());
        $this->db->where('prospect_is', $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}