<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
function displayStars($rating, $maxStars = 5)
{
    if ($rating == 0 || $rating == '') {
        echo '-';
        return;
    }
    for ($i = 1; $i <= $maxStars; $i++) {
        echo '<span class="star' . ($i <= $rating ? ' filled' : '') . '">&#9733;</span>';
    }
}
?>

<style>
    #backBtn {
        background-color: transparent;
        border: none;
        display: flex;
        align-items: center;
    }

    #backBtn i {
        margin-right: 5px;
    }

    .wizard-step {
        display: none;
    }

    .wizard-step.active {
        display: block;
    }

    .wizard-nav,
    .week {
        display: flex;
        justify-content: space-around;
        margin-bottom: 10px;
    }

    .wizard-circle,
    .days {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ddd;
        line-height: 30px;
        text-align: center;
        font-weight: bold;
        cursor: pointer;
    }

    .wizard-circle.active,
    .wizard-circle.completed {
        background-color: #007bff;
        color: #fff;
    }

    .wizard-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* Aligns items vertically centered if needed */
    }

    .wizard-buttons .btn {
        margin: 0;
        /* Ensures no extra margin */
    }

    #nextBtn {
        margin-left: auto;
        /* Pushes the "Next" button to the right */
    }

    .line {
        height: 2px;
        background-color: #D3D3D3;
        flex-grow: 1;
        margin: 13px;
        position: relative;
        z-index: 0;
    }

    .gridcontainer1 {
        display: flex;
        justify-content: space-between;
    }

    .grid-container {
        display: flex;
        grid-template-columns: auto auto auto auto;
        gap: 115px;
        margin-top: -2%;
        padding: 1px;
    }

    .grid-container>div {
        font-size: 12px;
    }

    .alt-text3 {
        margin-right: 30px;
    }

    /* Fixed Height for Modal */
    .modal-content {
        max-height: 80vh;
        overflow-y: auto;
    }

    /* Ensure wizard content is visible */
    .wizard-step {
        min-height: 300px;
        /* Adjust as needed */
    }

    .star.filled {
        color: orange;
    }

    .filters {
        background-color: rgb(255, 255, 255);
        color: rgba(0, 0, 0, 0.87);
        box-shadow: rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.14) 0px 2px 2px 0px, rgba(0, 0, 0, 0.12) 0px 1px 5px 0px;
        position: sticky;
        z-index: 1;
        top: 5%;
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px;
        padding: 10px 16px 18px;
        margin: 20px 0;

    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .lead-card {
        display: flex;
        background-color: rgb(240, 240, 241);
        color: rgba(0, 0, 0, 0.87);
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
        border-radius: 20px;
        overflow: hidden;
        padding: 16px;
        margin: 10px 0;
    }

    .fullscreenBtn {
        padding: 5px 10px !important;
        font-size: 1.2rem !important;
    }

    ._buttons a {
        margin-left: 0px !important;
        /* padding: 0; */
        margin-bottom: 10px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel_s tw-mt-2 sm:tw-mt-4">
            <div class="panel-body">
                <form id="filterForm" action="" method="post">
                    <?php $csrf = $this->security->get_csrf_hash(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="price_range_start"><?php echo _l('Price Range start'); ?></label>
                                <input type="text" id="price_range_start" name="price_range_start" class="filter-input" value="<?= !empty($_POST['price_range_start']) ? $_POST['price_range_start'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="price_range_end"><?php echo _l('Price Range end'); ?></label>
                                <input type="text" id="price_range_end" name="price_range_end" class="filter-input" value="<?= !empty($_POST['price_range_end']) ? $_POST['price_range_end'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="start_date"><?php echo _l('From'); ?></label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value=" <?= !empty($_POST['start_date']) ? date('d-m-Y', strtotime($_POST['start_date'])) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="end_date"><?php echo _l('To'); ?></label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value=" <?= !empty($this->input->post('end_date')) ? $this->input->post('end_date ') : '' ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="type"><?php echo _l('Lead Source'); ?></label>
                                <select id="type" name="lead_source" class="filter-input">
                                    <option value="">Select Lead Source</option>
                                    <?php foreach ($sources as $source): ?>
                                        <option value="<?php echo $source['id']; ?>" <?= $this->input->post('lead_source') == $source['id'] ? 'selected' : '' ?>><?php echo $source['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="type"><?php echo _l('Status'); ?></label>
                                <select id="type" name="status" class="filter-input">
                                    <option value="">Select Status</option>
                                    <?php foreach ($status as $statu): ?>
                                        <option value="<?php echo $statu['id']; ?>" <?= $this->input->post('status') == $statu['id'] ? 'selected' : '' ?>><?php echo $statu['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for=" source"><?php echo _l('Source'); ?></label>
                                <select id=" source" name="source" class="filter-input">
                                    <option value="">Select Source</option>
                                    <option value="" <?= $this->input->post('source') != '1' ? 'selected' : '' ?>>Cart
                                    </option>
                                    <option value="1" <?= $this->input->post('source') == '1' ? 'selected' : '' ?>>Campaign
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="email"><?php echo _l('Email'); ?></label>
                                <input type="text" id="email" name="email" class="filter-input" value="<?= !empty($_POST['email']) ? $_POST['email'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="rating"><?php echo _l('Rating'); ?></label>
                                <select id="rating" name="rating" class="filter-input">
                                    <option value="">Select rating</option>
                                    <option value="0" <?= $this->input->post('rating') == '0' ? 'selected' : '' ?>>0
                                    </option>
                                    <option value="1" <?= $this->input->post('rating') == '1' ? 'selected' : '' ?>>1
                                    </option>
                                    <option value="2" <?= $this->input->post('rating') == '2' ? 'selected' : '' ?>>2
                                    </option>
                                    <option value="3" <?= $this->input->post('rating') == '3' ? 'selected' : '' ?>>3
                                    </option>
                                    <option value="4" <?= $this->input->post('rating') == '4' ? 'selected' : '' ?>>4
                                    </option>
                                    <option value="5" <?= $this->input->post('rating') == '5' ? 'selected' : '' ?>>5
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <!-- <button class="btn regular_price_btn">
                        <div class="button-content">
                            <i class="fa fa-shopping-cart"></i>
                            <div class="text-container">
                                <span class="bold-text">$345-$563 Buy lead</span>
                                <span class="small-text">regular price</span>
                            </div>
                        </div>
                    </button> -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                        </div>


                        <div style="height:20px">
                            <input type="submit" value="Apply Filters" class="btn btn-info pull-right">
                            <input type="button" value="Remove Filters" class="btn btn-warning pull-right" onclick="resetForm();">

                        </div>
                </form>
                <hr class="hr-panel-heading" />
                <a href="#" data-toggle="modal" data-target="#customers_bulk_action"
                    class="bulk-actions-btn table-btn hide"
                    data-table=".table-clients"><?php echo _l('bulk_actions'); ?></a>
                <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo _l('close'); ?></button>
                                <a href="#" class="btn btn-primary"
                                    onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $table_data = [];
                $_table_data = [

                    [
                        'name' => _l('the_number_sign'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-number'],
                    ],
                    [
                        'name' => _l('clients_list_company'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-company'],
                    ],
                    [
                        'name' => _l('contact_primary'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-primary-contact'],
                    ],
                    [
                        'name' => _l('company_primary_email'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-primary-contact-email'],
                    ],
                    [
                        'name' => _l('clients_list_phone'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                    ],
                    [
                        'name' => _l('clients_list_value'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                    ],
                    [
                        'name' => _l('clients_list_tags'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                    ],
                    [
                        'name' => _l('clients_list_status'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-active'],
                    ],

                    [
                        'name' => _l('clients_list_source'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-active'],
                    ],
                    [
                        'name' => _l('date_created'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-date-created'],
                    ],

                    [
                        'name' => _l('campaign_id'),
                        'th_attrs' => ['class' => 'toggleable', 'id' => 'th-date-created'],
                    ],
                ];
                foreach ($_table_data as $_t) {
                    array_push($table_data, $_t);
                }

                $custom_fields = get_custom_fields('customers', ['show_on_table' => 1]);

                foreach ($custom_fields as $field) {
                    array_push($table_data, [
                        'name' => $field['name'],
                        'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                    ]);
                }
                $table_data = hooks()->apply_filters('customers_table_columns', $table_data);
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered dt-table" id="clients">
                        <thead>
                            <tr>
                                <th><?= _l('id') ?></th>
                                <th><?= _l('name') ?></th>
                                <th><?= _l('leadevo_email') ?></th>
                                <th><?= _l('leadevo_phone') ?></th>
                                <th><?= _l('Stars'); ?></th>
                                <th><?= _l('leadevo_price') ?></th>
                                <th><?= _l('lead_import_source') ?></th>
                                <th><?= _l('invoice_dt_table_heading_status') ?></th>
                                <th><?= _l('lead_source') ?></th>
                                <th><?= _l('expense_dt_table_heading_date') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($table as $prospect): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($prospect->id ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->name ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->email ?? ''); ?>
                                        <div class="row-options"><a href="#"
                                                onclick="init_lead_purchased(<?= $prospect->id ?>);return false;">View</a>
                                            |
                                            <?php if (!$prospect->is_reported) {
                                                // Check report hours
                                                $givenDate = new DateTime($prospect->dateadded);
                                                $currentDate = new DateTime();
                                                $interval = $currentDate->diff($givenDate);

                                                $hours = $interval->days * 24 + $interval->h;
                                                $allowed_hours = get_option('leadevo_report_hours');
                                                if (empty($allowed_hours)) {
                                                    $allowed_hours = 0;
                                                } else {
                                                    $allowed_hours = intval($allowed_hours);
                                                }

                                                if ($hours > $allowed_hours) {
                                                    echo '<a style="cursor: not-allowed;" disabled>Report</a>';
                                                } else {
                                                    echo '<a data-toggle="modal" data-target="#reportProspectModal" class="text-danger" 
                                                 data-id="' . $prospect->id . '" 
                                                 data-name="' . htmlspecialchars($prospect->name ?? 'N/A') . '" 
                                                 data-status="' . htmlspecialchars($prospect->status ?? 'N/A') . '" 
                                                data-type="' . htmlspecialchars($prospect->type ?? 'N/A') . '" 
                                                 data-category="' . htmlspecialchars($prospect->category ?? 'N/A') . '" 
                                                  data-acquisition="' . htmlspecialchars($prospect->source_name ?? 'N/A') . '" 
                                                 data-amount="' . htmlspecialchars($prospect->desired_amount ?? 'N/A') . '" 
                                                 data-campaign="' . htmlspecialchars($prospect->campaign_id ?? 'N/A') . '" 
                                                 data-industry="' . htmlspecialchars($prospect->dateadded ?? 'N/A') . '">Report</a>';
                                                }
                                            } else {
                                                echo '<a style="cursor: not-allowed;" disabled>Report</a>';
                                            }

                                            ?>
                                            |
                                            <a onclick="openSendApiModal(<?= $prospect->id ?>)">Send via API</a> |
                                            <a onclick="openSendZapierModal(<?= $prospect->id ?>)">Send via
                                                Zapier</a> |
                                            <a href="#" onclick="openRateModal(<?= $prospect->id ?>)">Rate</a>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($prospect->phonenumber ?? 'N/A'); ?></td>
                                    <td>
                                        <div class="star-rating">
                                            <?php
                                            // Example usage
                                            $userRating = $prospect->rating ?? 0; // This value could come from a database
                                            displayStars($userRating);
                                            ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($prospect->purchase_price ?? ''); ?></td>
                                    <td><?php echo $prospect->campaign_id == null ? 'Cart' : 'Campaign' ?>

                                        <div class="row-options">
                                            <?php if ($prospect->campaign_id != null): ?><a
                                                    href="<?php echo site_url('campaigns/campaign/' . $prospect->campaign_id) ?>">View</a><?php endif; ?>
                                            <?php if ($prospect->campaign_id == null): ?><a
                                                    href="<?php echo site_url('invoice/' . $prospect->invoice_id . '/' . $prospect->invoice_hash) ?>">View
                                                    Invoice</a><?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($prospect->status_name ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->source_name ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($prospect->dateadded ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="reportProspectModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="padding: 20px; max-height:83vh;">
            <div class="wizard-nav">
                <div class="wizard-circle" data-step="1">1</div>
                <div class="line"></div>
                <div class="wizard-circle" data-step="2">2</div>
                <div class="line"></div>
                <div class="wizard-circle" data-step="3">3</div>
                <div class="line"></div>
                <div class="wizard-circle" data-step="4">4</div>

            </div>
            <div class="gridcontainer1">
                <div class="grid-container">
                    <div class="alt-text1">Reason</div>
                    <div class="alt-text2">Confirmation</div>
                </div>
                <div class="grid-container" style="gap: 5px;">
                    <div class="alt-text3">Issue evidence</div>
                    <div class="alt-text4">Report confirmation</div>
                </div>
            </div>

            <br><br>

            <!-- Reason of report  -->
            <div class="wizard-step" data-step="1">
                <h3>Select a reason for reporting prospect</h3>
                <div class="form-group text-left">
                    <input type="hidden" name="campaign_id" />
                    <label for="reason"><?php echo _l('Reasons'); ?></label>
                    <select id="reasonSelect" name="industry" class="selectpicker" data-width="100%"
                        data-none-selected-text="<?php echo _l('Select Reason'); ?>">
                        <option value=""><?php echo _l('Select Reason'); ?></option>
                        <?php foreach ($reasons as $reason): ?>
                            <option value="<?php echo $reason['id']; ?>"><?php echo $reason['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                </div>
            </div>


            <!-- prospect confirmation -->
            <div class="wizard-step" data-step="2">
                <h3>Prospect Details</h3>
                <div class="form-group text-left">
                    <label for="details"><?php echo _l('Details'); ?></label>
                    <div class="table-responsive">
                        <table class="table table-bordered dt-table nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?php echo _l('Name'); ?></th>
                                    <th><?php echo _l('Status'); ?></th>
                                    <th><?php echo _l('Type'); ?></th>
                                    <th><?php echo _l('Category'); ?></th>
                                    <th><?php echo _l('Acquisition Channels'); ?></th>
                                    <th><?php echo _l('Desired Amount'); ?></th>
                                    <th><?php echo _l('Industry'); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td id="prospect-name">Name</td>
                                    <td id="prospect-status">Status</td>
                                    <td id="prospect-type">Type</td>
                                    <td id="prospect-category">Category</td>
                                    <td id="prospect-acquisition">Acquisition Channels</td>
                                    <td id="prospect-amount">Desired Amount</td>
                                    <td id="prospect-industry">Industry</td>
                                </tr>

                            </tbody>
                        </table>

                        <div class="form-group text-center mt-4">
                            <button type="button" class="btn btn-success" id="confirm-details">I confirm that the
                                details are correct</button>
                            <button type="button" class="btn btn-danger" id="mistake-details">Oops, I made a
                                mistake</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Evidence -->

            <div class="wizard-step" data-step="3">
                <h3>Evidence</h3>
                <div class="form-group text-left">
                    <label for="evidence-upload"><?php echo _l('Upload Evidence (MP3)'); ?></label>
                    <input type="file" class="form-control-file" id="evidence-upload" accept=".mp3">
                </div>
                <div class="form-group text-center mt-4">
                    <button type="button" class="btn btn-success" id="confirm-evidence">I confirm that I uploaded the
                        evidence</button>
                    <button type="button" class="btn btn-danger" id="no-evidence">Oops, I don’t have any
                        evidence</button>
                </div>
            </div>

            <!-- Thank you page -->
            <div class="wizard-step" data-step="4">
                <h3>Thank You</h3>
                <div class="text-center mt-4">
                    <p>Your report has been successfully submitted.</p>
                    <p>An agent will review your request and handle it within the next 72 hours.</p>
                    <p>Thank you for your patience.</p>
                </div>
            </div>

            <div class="wizard-buttons">
                <button class="btn btn-secondary" id="backBtn"><i class="fas fa-angle-left" style="font-size:19px"></i>
                    Back</button>
                <button class="btn btn-primary" id="nextBtn">Next</button>
            </div>
        </div>
    </div>
</div>

<div id="sendApiProspectModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo get_instance()->load->view('clients/modals/send_prospect_api_modal.php') ?>
</div>

<div id="sendZapierProspectModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo get_instance()->load->view('clients/modals/send_prospect_zapier_modal.php') ?>
</div>

<div id="rating_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <!-- Modal Header -->
            <div class="modal-header d-flex">
                <div></div>
                <h4 class="modal-title w-100"><?php echo _l('leadevo_prospect_ratings_title'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <!-- Font Awesome Close Icon -->
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(site_url('prospects/rate'), ['id' => 'rate-prospect-form']); ?>
                <input type="hidden" name="id" />
                <div class="form-group">
                    <label for="nonexclusive_status" class="control-label clearfix">
                        <?= _l('leadevo_prospect_ratings_description'); ?>
                    </label>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_1stars" name="rating" value="1" ?>>
                        <label for="prospect_rating_1stars"><?= _l('leadevo_delivery_quality_1stars'); ?></label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_2stars" name="rating" value="2" ?>>
                        <label for="prospect_rating_2stars">
                            <?= _l('leadevo_delivery_quality_2stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_3stars" name="rating" value="3" ?>>
                        <label for="prospect_rating_3stars">
                            <?= _l('leadevo_delivery_quality_3stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_4stars" name="rating" value="4" ?>>
                        <label for="prospect_rating_4stars">
                            <?= _l('leadevo_delivery_quality_4stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_5stars" name="rating" value="5" ?>>
                        <label for="prospect_rating_5stars">
                            <?= _l('leadevo_delivery_quality_5stars'); ?>
                        </label>
                    </div>
                </div>
                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('submit'); ?>" class="btn btn-primary" />

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


<?php
$jsonData = json_encode($table); ?>
<script>
    var tableData = <?php echo $jsonData; ?> ?? [];
    setTimeout(() => {
        $('#clients').DataTable();
    }, 100);

    function customers_bulk_action(event) {
        var r = confirm(app.lang.confirm_action_prompt);
        if (r == false) {
            return false;
        } else {
            var mass_delete = $('#mass_delete').prop('checked');
            var ids = [];
            var data = {};
            if (mass_delete == false || typeof(mass_delete) == 'undefined') {
                data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
                if (data.groups.length == 0) {
                    data.groups = 'remove_all';
                }
            } else {
                data.mass_delete = true;
            }
            var rows = $('.table-clients').find('tbody tr');
            $.each(rows, function() {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') == true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            $(event).addClass('disabled');
            setTimeout(function() {
                $.post(site_url + 'clients/bulk_action', data).done(function() {
                    window.location.reload();
                });
            }, 50);
        }
    }


    // General helper function for $.get ajax requests
    function requestGet_purchased(uri, params) {
        params = typeof params == "undefined" ? {} : params;
        var options = {
            type: "GET",
            url: uri.indexOf(site_url) > -1 ? uri : site_url + uri,
        };
        return $.ajax($.extend({}, options, params));
    }

    // General helper function for $.get ajax requests with dataType JSON
    function requestGetJSON_purchased(uri, params) {
        params = typeof params == "undefined" ? {} : params;
        params.dataType = "json";
        return requestGet_purchased(uri, params);
    }

    function init_lead_modal_data_purchased(id, url, isEdit) {
        var requestURL =
            (typeof url != "undefined" ? url : "leads/lead/") +
            (typeof id != "undefined" ? id : "");

        if (isEdit === true) {
            var concat = "?";
            if (requestURL.indexOf("?") > -1) {
                concat += "&";
            }
            requestURL += concat + "edit=true";
        }

        requestGetJSON_purchased(requestURL)
            .done(function(response) {
                _lead_init_data(response, id);
            })
            .fail(function(data) {
                alert_float("danger", data.responseText);
            });
    }

    function init_lead_purchased(id, isEdit) {
        if ($("#task-modal").is(":visible")) {
            $("#task-modal").modal("hide");
        }
        // In case header error
        if (init_lead_modal_data_purchased(id, undefined, isEdit)) {
            $("#lead-modal").modal("show");
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentStep = 1;
        const totalSteps = 4;
        const steps = document.querySelectorAll('.wizard-step');
        const circles = document.querySelectorAll('.wizard-circle');
        const backBtn = document.getElementById('backBtn');
        const nextBtn = document.getElementById('nextBtn');
        const reasonSelect = document.getElementById('reasonSelect');
        let prospectId = null;

        $('a[data-toggle="modal"]').on('click', function() {
            // Retrieve data attributes
            prospectId = $(this).data('id');

            // Use the prospectId as needed
            console.log('Prospect ID:', prospectId);
        });



        // Get CSRF token from the hidden field
        var csrfName = $('#reportProspectModal input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
        var csrfHash = $('#reportProspectModal input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();


        // Functionality for the Next button (only on the first step)
        nextBtn.addEventListener('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            } else if (currentStep === totalSteps) {
                sendReportData();
            }
        });

        // Functionality for the Back button
        backBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Move to the next step when "I confirm that the details are correct" is clicked
        document.getElementById('confirm-details').addEventListener('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            }
        });

        // Handle the "Oops, I made a mistake" button click
        document.getElementById('mistake-details').addEventListener('click', function() {
            $('#reportProspectModal').modal('hide');
        });

        // Move to the next step when "I confirm that I uploaded the evidence" is clicked
        document.getElementById('confirm-evidence').addEventListener('click', function() {
            var fileInput = document.getElementById('evidence-upload');
            if (fileInput.files.length === 0) {
                alert('Please upload an MP3 file before confirming.');
            } else {
                evidenceUrl = URL.createObjectURL(fileInput.files[0]);
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });

        function showStep(step) {
            steps.forEach((element, index) => {
                element.classList.toggle('active', index === step - 1);
            });
            circles.forEach((element, index) => {
                element.classList.toggle('active', index === step - 1);
                element.classList.toggle('completed', index < step - 1);
            });

            backBtn.style.display = step === 1 ? 'none' : 'inline-flex';

            // Show the Next button only on the first step and the Finish button on the last step
            if (step === 1) {
                nextBtn.style.display = 'inline-flex';
                nextBtn.textContent = 'Next';
            } else if (step === totalSteps) {
                nextBtn.style.display = 'inline-flex';
                nextBtn.textContent = 'Report';
            } else {
                nextBtn.style.display = 'none';
            }
        }
        // Handle the "Oops, I don’t have any evidence" button click
        document.getElementById('no-evidence').addEventListener('click', function() {
            $('#reportProspectModal').modal('hide');
        });

        showStep(currentStep);


        function sendReportData() {
            const selectedReason = reasonSelect.value;
            if (!selectedReason) {
                alert('Please select a reason for reporting.');
                return;
            }
            const data = {
                reason: selectedReason,
                prospect_id: prospectId,
                evidence: evidenceUrl,
                campaign_id: $('#reportProspectModal input[name=campaign_id]').val()
            };
            data[csrfName] = csrfHash;


            $.ajax({
                url: 'submit_report', // Endpoint URL
                type: 'POST', // HTTP method
                data: data, // JSON data and appended CSRF token
                success: function(response) {
                    alert_float('success', 'Report submitted successfully!');
                    $('#reportProspectModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('There was an error submitting the report.');
                }
            });
        }

        $('#reportProspectModal').on('show.bs.modal', function(event) {
            currentStep = 1;
            showStep(currentStep);
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var name = button.data('name');
            var status = button.data('status');
            var type = button.data('type');
            var category = button.data('category');
            var acquisition = button.data('acquisition');
            var amount = button.data('amount');
            var industry = button.data('industry');
            var campaignId = button.data('campaign');

            // Update the table cells with the prospect's data
            $('#prospect-name').text(name);
            $('#prospect-status').text(status);
            $('#prospect-type').text(type);
            $('#prospect-category').text(category);
            $('#prospect-acquisition').text(acquisition);
            $('#prospect-amount').text(amount);
            $('#prospect-industry').text(industry);

            $('#reportProspectModal input[name=campaign_id]').val(campaignId);

            $('#reportProspectModal').data('prospect-id', id);

            // clear evidence
            $('#evidence-upload').val(null);
            $('#reasonSelect').val(null);
            $('#reportProspectModal .filter-option-inner-inner').text('Select Reason');

        });

    });
</script>
<script src="<?= site_url('assets/js/main_purchased.js') ?>"></script>

<script>
    $(document).ready(function() {
        $('#1reportProspectModal').on('show.bs.modal', function(event) {
            currentStep = 1;
            showStep(currentStep);
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var name = button.data('name');
            var status = button.data('status');
            var type = button.data('type');
            var category = button.data('category');
            var acquisition = button.data('acquisition');
            var amount = button.data('amount');
            var industry = button.data('industry');
            var campaignId = button.data('campaign');

            // Update the table cells with the prospect's data
            $('#prospect-name').text(name);
            $('#prospect-status').text(status);
            $('#prospect-type').text(type);
            $('#prospect-category').text(category);
            $('#prospect-acquisition').text(acquisition);
            $('#prospect-amount').text(amount);
            $('#prospect-industry').text(industry);

            $('#reportProspectModal input[name=campaign_id]').val(campaignId);

            $('#reportProspectModal').data('prospect-id', id);

            // clear evidence
            $('#evidence-upload').val(null);
            $('#reasonSelect').val(null);
            $('#reportProspectModal .filter-option-inner-inner').text('Select Reason');

        });
    });

    function openRateModal(id) {
        document.querySelector('#rating_modal input[name=id]').value = id;
        console.log(id);

        $('#rating_modal').modal('show');
    }
</script>
<script>
     function resetForm() {
        // Reset form fields
        document.getElementById('filterForm').reset();
        
        // Reload the page without any filters (remove query parameters)
        window.location.href = window.location.pathname;
    }
</script>