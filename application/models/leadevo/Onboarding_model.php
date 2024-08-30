<?php defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding_model extends App_Model
{
    protected $table = 'leadevo_onboarding'; // Define the table name

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id)
    {
        // Query to get the last record for the specified client_id
        $sql = "SELECT * FROM `" . db_prefix() . "leadevo_onboarding`
                WHERE client_id = " . intval($id) . "
                ORDER BY updated_at DESC
                LIMIT 1;";

        // Execute the query and get the result
        $result = $this->db->query($sql)->row();

        // Check if a record was found
        if ($result) {
            // Record exists, return it
            return $result;
        } else {
            // Record does not exist, insert a new record
            $insert_sql = "INSERT INTO `" . db_prefix() . "leadevo_onboarding` (client_id)
               VALUES (" . intval($id) . ")";

            // Execute the insert query
            if ($this->db->query($insert_sql)) {
                // Retrieve the newly inserted record
                $new_sql = "SELECT * FROM `" . db_prefix() . "leadevo_onboarding`
                WHERE client_id = " . intval($id) . "
                ORDER BY id DESC
                LIMIT 1;";
                return $this->db->query($new_sql)->row();
            } else {
                // Handle insertion failure (optional)
                return null;
            }
        }
    }

    public function insert($data)
    {
        // Check if a record already exists for the client_id
        if ($this->exists($data['client_id'])) {
            return false; // Record already exists, no need to insert again
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status'] = true;
        return $this->db->insert($this->table, $data);
    }

    private function exists($client_id)
    {
        $this->db->where('client_id', $client_id);
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('client_id', $id)->update($this->table, $data);
    }
}
