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
        $sql = "SELECT  * FROM `" . db_prefix() . "leadevo_onboarding`
        WHERE client_id = " . $id . "
        ORDER BY id DESC
        LIMIT 1;";
        return $this->db->query($sql)->row();
    }

    public function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status'] = true;
        return $this->db->insert($this->table, $data);
    }


    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('client_id', $id)->update($this->table, $data);
    }
}
