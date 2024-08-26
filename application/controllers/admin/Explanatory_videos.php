<?php defined('BASEPATH') or exit('No direct script access allowed');

class Explanatory_videos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Explanatory_videos_model');

    }

    public function index()
    {
        $data['videos'] = $this->Explanatory_videos_model->get_all();
        if (empty($data['videos'])) {
            set_alert('danger', 'No videos found or an error occurred while fetching videos.');
        }
        $this->load->view('admin/leadevo/explanatory-videos/explanatory_videos', $data);

    }


    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'url' => $this->input->post('url')
            ];



            if ($this->Explanatory_videos_model->update($id, $data)) {
                set_alert('success', 'Video information updated successfully.');
            } else {
                set_alert('danger', 'Failed to update video information.');
            }
            redirect(admin_url('explanatory_videos'));
        }

        $data['video'] = $this->Explanatory_videos_model->get($id);
        $this->load->view('admin/leadevo/explanatory-videos/edit', $data);
    }


    public function view($id)
    {
        $data['video'] = $this->Explanatory_videos_model->get($id);
        if (empty($data['video'])) {
            set_alert('danger', 'No video found or an error occurred while fetching video.');
            redirect(admin_url('explanatory_videos'));
        }
        $this->load->view('admin/leadevo/explanatory-videos/view', $data);
    }
}

