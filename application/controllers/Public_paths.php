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
            $this->input->post('email'),
            $this->input->post('password', false),
            $this->input->post('remember'),
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
    // Read raw POST data
    $rawData = file_get_contents('php://input');

    // Decode JSON data
    $inputData = json_decode($rawData, true);

    // Debug raw and decoded data
    var_dump($rawData);        // Raw JSON data
    var_dump($inputData);      // Decoded JSON data

    // Extract values from the decoded JSON data
    $baseUrl = isset($inputData['baseUrl']) ? $inputData['baseUrl'] : null;
    $lead = isset($inputData['lead']) ? $inputData['lead'] : null;

    // Further debug
    var_dump($baseUrl, $lead);

    // Initialize cURL
    $ch = curl_init();

    // Set the cURL options
    $url = $baseUrl . "/leadevo_api/receive?lead=" . urlencode($lead);
    curl_setopt($ch, CURLOPT_URL, $url); // Set the URL with encoded lead data
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response instead of outputting it

    // Execute the request and get the response
    $response = curl_exec($ch);

    // Check for any cURL errors
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        echo $response; // Return the response from the external server
    }

    // Close the cURL session
    curl_close($ch);
}
}