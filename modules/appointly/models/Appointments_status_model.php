
<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class helper model responsible for appointment attendeees
 */
class Appointments_status_model extends App_Model
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
    public function create($name, $description)
    {
        $this->db->insert('appointly_appointments_statuses', [
            'name' => $name,
            'description' => $description,
            'is_active' => '1'
        ]);
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
        return $this->db->get('appointly_appointments_statuses')->result_array();
    }

    /**
     * Get all subjects
     *
     * @return array
     */
    public function get_all()
    {
        $this->db->where('is_active', 1);
        return $this->db->get('appointly_appointments_statuses')->result_array();
    }


    /**
     * delete subject
     *
     * @param string $subject
     * @return array
     */
    public function delete($subject_id)
    {
       // Use delete query directly to delete records based on subject
        $this->db->where('id', $subject_id);
        $this->db->delete('appointly_appointments_statuses');
        
        // Optionally, return true or false based on deletion success
        return $this->db->affected_rows() > 0;
    }


}
