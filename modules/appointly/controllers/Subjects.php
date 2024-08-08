<?php defined('BASEPATH') or exit('No direct script access allowed');

class Subjects extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        // Load any necessary models, libraries, helpers, etc.
        $this->load->model('Appointments_subject_model');
        $this->load->model('Booking_page_model');
    }

    public function index()
    {
        // $data['subjects'] = $this.Appointments_subject_model->get_all();
        $data['booking_pages'] = $this->Booking_page_model->get_all();
        $this->load->view('subjects/index', $data);
    }

    public function delete()
    {
        // Check if the request method is POST
        // Retrieve POST data
        $subject_id = $this->input->post('subject_id');
        // Validate the input data
        if (empty($subject_id)) {
            // Redirect with an error if validation fails
            $this->session->set_flashdata('error', 'Invalid subject ID.');
            echo json_encode(array('status' => 'error', 'message' => 'Please provide subject ID'));
        }

        // Process the data (e.g., delete from the database)
        $deleted = $this->Appointments_subject_model->delete($subject_id);

        // Redirect with a success or error message
        if ($deleted) {
            echo json_encode(array('status' => 'success', 'message' => 'Subject deleted successfully'));
        } else {
            echo json_encode(array('status' => 'success', 'message' => 'Something went wrong while deleting the subject'));
        }


    }

    public function create()
    {

        // Retrieve POST data
        $subject = $this->input->post('subject');
        $booking_page_id = $this->input->post('booking_page_id');



        // Validate the input data
        if (empty($subject) || empty($booking_page_id)) {
            // Redirect with an error if validation fails
            $this->session->set_flashdata('error', 'Subject and Booking Page is required.');
            redirect('/admin/appointly/subjects');
        }


        // Process the data (e.g., insert into the database)
        $inserted = $this->Appointments_subject_model->create($subject, $booking_page_id);
        // Redirect with a success or error message
        if ($inserted) {
            $this->session->set_flashdata('success', 'Subject added successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to add subject.');
        }

        redirect('/admin/appointly/subjects');

    }

    public function update()
    {
        $subject_id = $this->input->post('subject_id');
        $subject = $this->input->post('subject');
        $booking_page_id = $this->input->post('booking_page_id');
        if (empty($subject_id) || empty($subject) || empty($subject)) {
            $this->session->set_flashdata('error', 'Subject ID, Booking page and Subject are required.');
            redirect('/admin/appointly/subjects');
        }
        $updated = $this->Appointments_subject_model->update($subject_id, $subject, $booking_page_id);
        if ($updated) {
            $this->session->set_flashdata('success', 'Subject updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update subject.');
        }
        redirect('/admin/appointly/subjects');
    }

    public function json()
    {
        $subjects = $this->Appointments_subject_model->get_all(); // Assuming this method fetches all subjects from the database
        $data = [];
        foreach ($subjects as $subject) {
            $row = [];
            $row[] = $subject['subject'];
            $row[] = '<form method="POST" action="' . base_url('admin/appointly/subjects/delete') . '">
                        <input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '">
                        <input type="hidden" name="subject_id" value="' . $subject['id'] . '">
                        <button type="submit" class="btn btn-danger btn-xs btn-delete-subject"><i class="fa fa-trash"></i></button>
                    </form>';
            $data[] = $row;
        }
        echo json_encode(['data' => $data]);
    }
}
