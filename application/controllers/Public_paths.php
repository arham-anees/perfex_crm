<?php defined('BASEPATH') or exit('No direct script access allowed');

class Public_paths extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // Cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }


        $this->load->model('Authentication_model');
    }

    public function index()
    {
        // Your code here
    }

    public function authenticate()
    {
        return $this->Authentication_model->login_third_party(
            $this->input->get('email'),
            $this->input->get('password', false),
            $this->input->get('remember'),
            false
        );
    }
    public function me()
    {
        return $this->Authentication_model->login_third_party(
            'imran@mail.com',
            'test',
            $this->input->post('remember'),
            false
        );
    }
    public function receive_zapier()
    {
        // Read the raw POST data
        $json_data = file_get_contents('php://input');

        // Decode the JSON data into an associative array
        $data = json_decode($json_data, true);

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            $response = array('status' => 'error', 'message' => 'Invalid JSON data.');
            echo json_encode($response);
            return;
        }

        // Extract data from the inputData object
        $baseUrl = $data['base_url'] ?? null;
        $lead = $data['lead'] ?? null;

        // Get query string parameters
        // $baseUrl = $this->input->post('baseUrl');
        // $lead = $this->input->post('lead');

        // Validate parameters
        if (empty($baseUrl) || empty($lead)) {
            $response = array('status' => 'error', 'message' => 'Missing required parameters.');
            echo json_encode($response);
            return;
        }

        // Construct the URL
        $url = $baseUrl . '/leadevo_api/receive?lead=' . urlencode($lead);

        // Fetch data from the URL
        $result = $this->send_get_request($url);

        // Return the result as JSON
        echo json_encode($result);
        // Read raw POST data
        // $rawData = file_get_contents('php://input');

        // // Decode JSON data
        // $inputData = json_decode($rawData, true);


        // // Extract values from the decoded JSON data
        // $baseUrl = isset($inputData['baseUrl']) ? $inputData['baseUrl'] : null;
        // $lead = isset($inputData['lead']) ? $inputData['lead'] : null;


        // // Initialize cURL
        // $ch = curl_init();

        // // Set the cURL options
        // $url = $baseUrl . "/leadevo_api/receive?lead=" . urlencode($lead);
        // curl_setopt($ch, CURLOPT_URL, $url); // Set the URL with encoded lead data
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it

        // // Execute the request and get the response
        // $response = curl_exec($ch);

        // // Check for any cURL errors
        // if (curl_errno($ch)) {
        //     echo json_encode( curl_error($ch));
        // } else {
        //     echo json_encode( $response); // Return the response from the external server
        // }

        // // Close the cURL session
        // curl_close($ch);
    }
    private function send_get_request($url)
    {
        // Using cURL to make the GET request
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $response = array('status' => 'error', 'message' => curl_error($ch));
        } else {
            $response = json_decode($response, true); // Decode JSON response
        }

        curl_close($ch);

        return $response;
    }
}