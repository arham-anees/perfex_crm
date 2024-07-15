
<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class helper model responsible for appointment attendeees
 */
class Booking_page_model extends App_Model
{
    
    public function __construct() {
        parent::__construct();
        // Load any necessary libraries, helpers, or initialize settings
        $this->load->database();
    }
    /**
     * Create new subject
     *
     * @param string $subject
     * @return void
     */
    public function create($data)
    {
        $data['is_active'] = true;
        $this->db->insert('appointly_booking_pages', $data);
    }

    public function update($data, $id) {
        $this->db->where('id',$id);
        $this->db->update('appointly_booking_pages', $data);
        // Optionally, return true or false based on deletion success
        return $this->db->affected_rows() > 0;
    }

    /**
     * Get subject
     *
     * @param string $subject
     * @return array
     */
    public function get($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('appointly_booking_pages')->row_array();
    }

    public function get_by_url($url)
    {
        $this->db->where('url',$url);
        return $this->db->get('appointly_booking_pages')->row_array();
    }

    /**
     * Get all subjects
     *
     * @return array
     */
    public function get_all()
    {
        $this->db->where('is_active',true);
        return $this->db->get('appointly_booking_pages')->result_array();
    }


    /**
     * delete subject
     *
     * @param string $subject
     * @return array
     */
    public function delete($id)
    {
        // Use delete query directly to delete records based on subject
        $data = $this->get($id);
        $data['is_active']=false;
        return $this->update($data, $id);
    }


}
