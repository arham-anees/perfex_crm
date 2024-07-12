
<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class helper model responsible for appointment attendeees
 */
class Appointments_subject_model extends App_Model
{
    public function __construct()
    {
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
    public function create($subject)
    {
        $this->db->insert('appointly_appointments_subjects', [
            'subject' => $subject
        ]);
    }

    /**
     * Get subject
     *
     * @param string $subject
     * @return array
     */
    public function get($subject)
    {
        $this->db->where('subject', $subject);
        return $this->db->get('appointly_appointments_subjects')->result_array();
    }
    public function get_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('appointly_appointments_subjects')->row_array();
    }

    /**
     * Get all subjects
     *
     * @return array
     */
    public function get_all()
    {
        return $this->db->get('appointly_appointments_subjects')->result_array();
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
        $this->db->delete('appointly_appointments_subjects');

        // Optionally, return true or false based on deletion success
        return $this->db->affected_rows() > 0;
    }

    public function update($subject_id, $subject)
    {
        $data = ['subject' => $subject];
        $this->db->where('id', $subject_id);
        return $this->db->update('tblappointly_appointments_subjects', $data);
    }
}
