<?php defined('BASEPATH') or exit('No direct script access allowed');

class Explanatory_videos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Explanatory_videos_model');
    }

    public function index()
    {
        $data['videos'] = $this->Explanatory_videos_model->get_all();
        $this->load->view('leadevo/explanatory-videos/explanatory_videos', $data);
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'key' => $this->input->post('key'),
                'url' => $this->input->post('url')
            ];

            if ($this->Explanatory_videos_model->update($id, $data)) {
                set_alert('success', 'Video information updated successfully.');
            } else {
                set_alert('danger', 'Failed to update video information.');
            }
            redirect(admin_url('leadevo/explanatory_videos'));
        }

        $data['video'] = $this->Explanatory_videos_model->get($id);
        $this->load->view('leadevo/explanatory-videos/edit', $data);
    }

    public function delete($id)
    {
        if ($this->Explanatory_videos_model->delete($id)) {
            set_alert('success', 'Video deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete video.');
        }
        redirect(admin_url('leadevo/explanatory_videos'));
    }

    public function view($id)
    {
        $data['video'] = $this->Explanatory_videos_model->get($id);
        $this->load->view('leadevo/explanatory-videos/view', $data);
    }
}
