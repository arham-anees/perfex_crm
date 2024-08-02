<?php defined('BASEPATH') or exit('No direct script access allowed');

class Prospects_model extends CI_Model
{
    public $mdb;
    private $table = 'tblleadevo_prospects';

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
}
