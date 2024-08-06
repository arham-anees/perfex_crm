<?php defined('BASEPATH') or exit('No direct script access allowed');

class Campaigns_model extends CI_Model
{
    public $mdb;

    protected $table = 'tblleadevo_campaign'; // Define the table name

    public function __construct()
    {
        parent::__construct();
        $this->mdb = $this->load->database('leadevo_marketplace', true);
    }

    public function get_all()
    {
        return $this->mdb->get($this->table)->result();
    }

    public function get($id)
    {
        return $this->mdb->where('id', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        return $this->mdb->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->mdb->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->mdb->where('id', $id)->delete($this->table);
    }
    public function get_campaign_statuses()
    {
        return $this->mdb->get('tblleadevo_campaign_statuses')->result_array();
    }
}
