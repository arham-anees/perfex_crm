<?php defined('BASEPATH') or exit('No direct script access allowed');

class Subjects extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        // Load any necessary models, libraries, helpers, etc.
        $this->load->model('Appointments_subject_model');
    }

    public function index()
    {
        // $data['subjects'] = $this.Appointments_subject_model->get_all();
        $this->load->view('subjects/index');
    }

    public function delete()
    {
        // Check if the request method is POST
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Retrieve POST data
            $subject_id = $this->input->post('subject_id');

            // Validate the input data
            if (empty($subject_id)) {
                // Redirect with an error if validation fails
                $this->session->set_flashdata('error', 'Invalid subject ID.');
                redirect('..');
            }

            // Process the data (e.g., delete from the database)
            $deleted = $this->Appointments_subject_model->delete($subject_id);

            // Redirect with a success or error message
            if ($deleted) {
                $this->session->set_flashdata('success', 'Subject deleted successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to delete subject.');
            }

            redirect('/admin/appointly/subjects');
        } else {
            // Redirect with an error if the request method is not POST
            $this->session->set_flashdata('error', 'Invalid request method.');
            redirect('/admin/appointly/subjects');
        }
    }

    public function create()
    {
        // Check if the request method is POST
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Retrieve POST data
            $subject = $this->input->post('subject');

            // Validate the input data
            if (empty($subject)) {
                // Redirect with an error if validation fails
                $this->session->set_flashdata('error', 'Subject is required.');
                redirect('/admin/appointly/subjects');
            }

            // Process the data (e.g., insert into the database)
            $inserted = $this->Appointments_subject_model->create($subject);

            // Redirect with a success or error message
            if ($inserted) {
                $this->session->set_flashdata('success', 'Subject added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add subject.');
            }

            redirect('/admin/appointly/subjects');
        } else {
            // Redirect with an error if the request method is not POST
            $this->session->set_flashdata('error', 'Invalid request method.');
            redirect('/admin/appointly/subjects');
        }
    }

    public function update()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $subject_id = $this->input->post('subject_id');
            $subject = $this->input->post('subject');
            if (empty($subject_id) || empty($subject)) {
                $this->session->set_flashdata('error', 'Subject ID and Subject are required.');
                redirect('/admin/appointly/subjects');
            }
            $updated = $this->Appointments_subject_model->update($subject_id, $subject);
            if ($updated) {
                $this->session->set_flashdata('success', 'Subject updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update subject.');
            }
            redirect('/admin/appointly/subjects');
        } else {
            $this->session->set_flashdata('error', 'Invalid request method.');
            redirect('/admin/appointly/subjects');
        }
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
