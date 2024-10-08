<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\projects\Gantt;
use app\services\ValidatesContact;

class Cart extends ClientsController
{
    /**
     * @since  2.3.3
     */
    use ValidatesContact;

    public function __construct()
    {
        parent::__construct();

        hooks()->do_action('after_clients_area_init', $this);
        $this->load->model('leadevo/Campaigns_model');
        $this->load->model('leadevo/Prospects_model');
        $this->load->model('leadevo/Cart_model');
        $this->load->model('Client_invoices_model');
        if (!is_client_logged_in()) {
            redirect(site_url('authentication'));
        }
    }

    public function index()
    {
        $data['campaigns'] = $this->Campaigns_model->get_all();
        $data['prospects'] = $this->Prospects_model->get_all();
        // $this->load->view('clients/dashboard/dashboard', $data);
        // $data['is_home'] = true;
        // $this->load->model('reports_model');
        // $data['payments_years'] = $this->reports_model->get_distinct_customer_invoices_years();

        // $data['project_statuses'] = $this->projects_model->get_project_statuses();
        // $data['title']            = get_company_name(get_client_user_id());
        $this->data($data);
        $this->view('clients/dashboard/dashboard');
        $this->layout();
    }

    public function checkout()
    {
        $cart = $this->Cart_model->get_cart_prospects();
        // hooks()->do_action('after_prospect_purchased', ['client_id' => get_client_user_id(), 'prospects' => $cart]);
        $total = 0;
        $invoice_data = [
            'number' => ((int) $this->Client_invoices_model->get_max_invoice_number()) + 1,
            'clientid' => get_client_user_id(),
            'date' => date('Y-m-d'),
            'duedate' => date('Y-m-d', strtotime('+2 days')),
            'subtotal' => $total,
            'total_tax' => 0,
            'total' => $total,
            'adjustment' => 0,
            'hash' => app_generate_hash(),
            'project_id' => '',
            'billing_street' => '',
            'billing_city' => '',
            'billing_state' => '',
            'billing_zip' => '',
            'show_shipping_on_invoice' => 'on',
            'shipping_street' => '',
            'shipping_city' => '',
            'shipping_state' => '',
            'shipping_zip' => '',
            'tags' => 'LeadEvo Checkout',
            'discount_total' => '0',
            'task_id' => '',
            'expense_id' => '',
            'clientnote' => '',
            'terms' => '',
            'discount_percent' => '0',
            'allowed_payment_modes' => ['stripe'],
            'currency' => 1,
            'recurring' => 0,
            'discount_type' => 0,
            'repeat_every_custom' => 1,
            'repeat_type_custom' => 'day',
            'adminnote' => ''
        ];

        foreach ($cart as $prospect) {
            $prospect_obj = $this->Prospects_model->get_by_id($prospect['prospect_id']);
            $amount = $prospect['desired_amount'] ?? 0;
            $total += $amount;
            // Prepare the prospect item for the invoice
            $item = [
                'description' => $prospect_obj['first_name'] . ' ' . $prospect_obj['last_name'],
                'long_description' => $prospect_obj['id'] . ' ' . $prospect_obj['email'] . ' ' . $prospect_obj['phone'],
                'rate' => $amount,
                'unit' => 0,
                'order' => 0,
                'qty' => 1,  // Assuming each prospect is a single unit
            ];

            // Add the item to the invoice items array
            $invoice_data['newitems'][] = $item;
        }
        $invoice_data['subtotal'] = $total;
        $invoice_data['total'] = $total;

        if (hooks()->apply_filters('validate_invoice_number', true)) {
            $number = ltrim($invoice_data['number'], '0');
            if (
                total_rows('invoices', [
                    'YEAR(date)' => (int) date('Y', strtotime(to_sql_date($invoice_data['date']))),
                    'number' => $number,
                    'status !=' => Invoices_model::STATUS_DRAFT,
                ])
            ) {
                set_alert('warning', _l('invoice_number_exists'));

                redirect(site_url('invoices/invoice'));
            }
        }

        $id = $this->Client_invoices_model->add($invoice_data);
        // update cart and set invoice id to cart
        $this->Cart_model->add_invoice_to_cart($id, $cart);
        echo json_encode(['status' => 'success', 'data' => $id, 'link' => site_url('invoice/' . $id . '/' . $invoice_data['hash'])]);
    }
}
