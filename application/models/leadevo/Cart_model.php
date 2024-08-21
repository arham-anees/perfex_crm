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
        $sql = "SELECT * FROM " . db_prefix() . "leadevo_cart WHERE invoice_id IS NULL AND client_id = " . get_client_user_id();
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
    public function get_cart_prospects()
    {
        if (!is_client_logged_in()) {
            return [];
        }

        // Get client ID
        $client_id = get_client_user_id();

        // Construct the SQL query
        $sql = "SELECT DISTINCT c.prospect_id, p.first_name, p.email, p.desired_amount, p.last_name, p.phone
                FROM tblleadevo_cart c
                INNER JOIN tblleadevo_prospects p ON p.id = c.prospect_id
                WHERE c.client_id = ? AND c.invoice_id IS null";

        // Execute the query with parameter binding
        $query = $this->db->query($sql, array($client_id));

        // Return the results as an associative array
        return $query->result_array();
    }
    public function get_by_prospect_id($prospect_id)
    {
        $this->db->where('prospect_id', $prospect_id);
        $query = $this->db->get('tblleadevo_cart');
        return $query->result_array();
    }
    // Delete the item from the cart
    public function delete_item($client_id, $prospect_id)
    {
        $this->db->where('client_id', $client_id);
        $this->db->where('prospect_id', $prospect_id);
        $this->db->delete('tblleadevo_cart');
    }

    public function add_invoice_to_cart($invoice_id, $prospects)
    {
        $client_id = get_client_user_id();
        foreach ($prospects as $item) {
            $sql = "UPDATE " . db_prefix() . "leadevo_cart SET invoice_id = " . $invoice_id . "
            WHERE client_id = " . $client_id . " AND prospect_id = " . $item['prospect_id'];
            $this->db->query($sql);
        }
    }

    public function get_by_invoice($invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $query = $this->db->get('tblleadevo_cart');
        return $query->result_array();
    }
}