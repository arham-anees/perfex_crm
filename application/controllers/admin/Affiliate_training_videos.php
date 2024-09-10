<?php defined('BASEPATH') or exit('No direct script access allowed');

class Affiliate_training_videos extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leadevo/Affiliate_training_videos_model');

    }

    public function index()
    {
        $data['videos'] = $this->Affiliate_training_videos_model->get_all();
        if (empty($data['videos'])) {
            set_alert('danger', 'No videos found or an error occurred while fetching videos.');
        }
        $this->load->view('admin/leadevo/affiliate-training-videos/affiliate_training_videos', $data);

    }

    public function create()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'url' => $this->input->post('url'),
                'video_order' => $this->input->post('video_order'),
            ];
            if (filter_var($data['url'], FILTER_VALIDATE_URL) === false) {
                set_alert('danger', 'Video URL is not valid.');
                redirect(admin_url('affiliate_training_videos/create'));
            }
            if ($this->Affiliate_training_videos_model->insert($data)) {
                set_alert('success', 'Video added successfully.');
            } else {
                set_alert('danger', 'Failed to add video.');
            }
            redirect(admin_url('affiliate_training_videos'));
        }

        $this->load->view('admin/leadevo/affiliate-training-videos/create');
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'url' => $this->input->post('url'),
                'video_order' => $this->input->post('video_order'),
            ];
            if (filter_var($data['url'], FILTER_VALIDATE_URL) === false) {
                set_alert('danger', 'Video URL is not valid.');
                redirect(admin_url('affiliate_training_videos/edit/' . $id));
            }
            if ($this->Affiliate_training_videos_model->update($id, $data)) {
                set_alert('success', 'Video information updated successfully.');
            } else {
                set_alert('danger', 'Failed to update video information.');
            }
            redirect(admin_url('affiliate_training_videos'));
        }

        $data['video'] = $this->Affiliate_training_videos_model->get($id);
        $this->load->view('admin/leadevo/affiliate-training-videos/edit', $data);
    }

    public function delete($id)
    {
        if ($this->Affiliate_training_videos_model->delete($id)) {
            set_alert('success', 'Video deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete video.');
        }
        redirect(admin_url('affiliate_training_videos'));
    }

    public function view($id)
    {
        $data['video'] = $this->Affiliate_training_videos_model->get($id);
        if (empty($data['video'])) {
            set_alert('danger', 'No video found or an error occurred while fetching video.');
            redirect(admin_url('affiliate_training_videos'));
        }
        $this->load->view('admin/leadevo/affiliate-training-videos/view', $data);
    }
}


