<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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

  .days {
    background-color: #ffffff;
    border: 1px solid #d9d7d7;
    font-weight: 400;
  }

  .days.active {
    background-color: #007bff;
    color: #fff;
  }

  .line {
    height: 2px;
    background-color: #D3D3D3;
    flex-grow: 1;
    margin: 13px;
    position: relative;
    z-index: 0;
  }

  .cap-options {
    margin-top: 10px;
  }

  .gridcontainer1 {
    display: flex;
  }

  .grid-container {
    display: flex;
    grid-template-columns: auto auto auto auto;
    gap: 41px;
    margin-top: -2%;
    padding: 1px;
  }

  .grid-container>div {
    font-size: 12px;
  }

  .alt-text5 {
    margin-left: 6%;
  }

  .dropdown-menu {
    max-height: 150px;
    /* Set your desired max height */
    overflow-y: auto;
    /* Enable vertical scrolling */
  }

  .radio-container {
    margin: 20px 10px 10px 40px;
  }

  .selected-options {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
  }

  .selected-option {
    background-color: #e2e2e2;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 5px 10px;
    margin: 5px;
    display: flex;
    align-items: center;
  }

  .selected-option span {
    margin-left: 10px;
    cursor: pointer;
  }

  h3 {
    text-align: center;
  }

  .form {
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    padding: 20px;
  }

  .form label {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    font-size: 16px;
  }

  .form input[type="checkbox"] {
    margin: 0px 10px 0px 0px;
    width: 15px;
    height: 15px;
  }

  .form input[type="checkbox"]:hover {
    cursor: pointer;
    border: 1px solid #007bff;
  }


  /* Payment Form Styles */
  .payment-form input[type="text"],
  .payment-form input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }


  .payment-form button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .payment-form button[type="submit"]:hover {
    background-color: #0056b3;
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

  /* Style for the info icon */
  .info-icon {
    font-size: 16px;
    /* Adjust the size as needed */
    color: #007bff;
    /* Blue color for the info icon */
    margin-left: 8px;
    /* Space between text and icon */
    cursor: pointer;
    /* Change cursor to pointer to indicate it's interactive */
    position: relative;
    /* Necessary for positioning the tooltip */
  }

  /* Tooltip styling */
  .info-icon::after {
    content: attr(data-tooltip);
    /* Get the tooltip text from the data-tooltip attribute */
    font-size: 14px;
    position: absolute;
    top: 100%;
    /* Position above the info icon */
    left: 50%;
    transform: translateX(-50%);
    background-color: #ffffff;
    /* Background color of the tooltip box */
    color: #111111;
    /* Text color */
    padding: 8px;
    /* Space inside the box */
    border: 1px solid #ccc;
    /* Border around the box */
    border-radius: 4px;
    /* Rounded corners */
    opacity: 0;
    /* Initially hidden */
    visibility: hidden;
    /* Initially hidden */
    transition: opacity 0.3s, visibility 0.3s;
    z-index: 1000;
    /* Ensure tooltip is above other content */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    /* Optional: adds a shadow to the box */
    min-width: 200px;
    min-height: 100px;
    display: flex;
    flex-wrap: wrap;
  }

  /* Show tooltip on hover */
  .info-icon:hover::after {
    opacity: 1;
    visibility: visible;
  }

  .time-selectors {
    display: flex;
    justify-content: space-between;
    /* Add space between dropdowns */
    margin-top: 20px;
    /* Adjust margin as needed */
  }

  .time-selector {
    flex: 1;
    /* Allow dropdowns to grow equally */
    margin-right: 10px;
    /* Space between dropdowns */
  }

  .time-selector:last-child {
    margin-right: 0;
    /* Remove margin from the last dropdown */
  }

  .call-to-action {
    padding: 5px;
    border-radius: 50px;
    min-width: 20px;
    min-height: 20px;
  }

  .stat-change {
    color: #28a745;
  }

  .stat-percentage {
    color: #6c757d;
  }
</style>

<div class="row">
  <div class="col-md-12 section-client-dashboard">
    <div class="row main_row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <!-- start of panel body -->
            <div class="_buttons">
              <a data-toggle="modal" data-target="#createCampaignModal"
                class="btn btn-primary pull-left display-block mleft10">
                <i class="fa-regular fa-plus tw-mr-1"></i>
                <?php echo _l('leadevo_new_campaign'); ?>
              </a>
              <div class="clearfix"></div>
            </div>
            <hr class="hr-panel-heading" />
            <!-- create 2 cards in same row -->
            <div class="row">
              <div class="col-md-3">
                <div class="panel_s">
                  <div class="panel-body text-left">
                    <h5 class="no-margin tw-text-left tw-font-semibold font">
                      <?= _l('leadevo_client_dashboard_total_campaigns') ?>
                      <!-- <span class="pull-right call-to-action bg-primary">
                        <i class="fa fa-plus"></i>
                      </span> -->
                    </h5>
                    <h1 class="bold"><?php echo count($campaigns); ?></h1>

                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="panel_s">
                  <div class="panel-body text-left">
                    <h5 class="no-margin tw-text-left tw-font-semibold font">
                      <?= _l('leadevo_client_dashboard_total_prospects') ?>
                    </h5>
                    <h1 class="bold"><?php echo count($prospects); ?></h1>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="panel_s">
                  <div class="panel-body text-left">
                    <h5 class="no-margin tw-text-left tw-font-semibold font">
                      <?= _l('leadevo_client_dashboard_reported_prospects') ?>
                    </h5>
                    <h1 class="bold"><?php echo count($reported_prospects); ?></h1>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="panel_s">
                  <div class="panel-body text-left">
                    <h5 class="no-margin tw-text-left tw-font-semibold font">
                      <?= _l('leadevo_client_dashboard_onboarding_steps') ?>
                    </h5>
                    <h1 class="bold"><?= $onboarding_steps ?>/<?= $onboarding_total_steps ?></h1>
                  </div>
                </div>
              </div>

            </div>

            <!-- end of row with 2 columns for cards -->
            <div class="row">
              <?php
              // Ensure $dashboard_stats is not empty
              if (!empty($dashboard_stats)) {
                $card_count = 0; // Counter to handle columns in rows
                foreach ($dashboard_stats[0] as $key => $value) {
                  // Skip keys that are not part of the statistics to display
                  if (!in_array($key, ['prospect_amount', 'reported_today', 'delivered_today', 'prospect_avg_price'])) {
                    continue;
                  }

                  // Start a new row every 4 cards
                  if ($card_count % 4 == 0) {
                    if ($card_count > 0) {
                      echo '</div></div>'; // Close previous row if not the first one
                    }
                    echo '<div class="row"><div class="col-md-12">';
                  }
                  ?>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="panel_s">
                      <div class="panel-body text-left">
                        <h5 class="no-margin"><?php echo ucfirst(str_replace('_', ' ', $key)); ?></h5>
                        <h1 class="bold"><?php echo $value; ?></h1>
                        <?php if ($key == 'reported_today'):
                          ?>
                          <div>
                            <span class="stat-change"><?php
                            $currentTotal = $dashboard_stats[0]->reported_today;
                            $priorTotal = $dashboard_stats[0]->delivered_yesterday;
                            $change = $currentTotal - $priorTotal;
                            $percentageChange = $priorTotal > 0 ? ($change / $priorTotal) * 100 : 0;
                            echo ($change >= 0 ? '+' : '') . $change;
                            ?></span>
                            <span class="stat-percentage"><?php
                            echo '(' . ($percentageChange >= 0 ? '+' : '') . number_format($percentageChange, 1) . '%)';
                            ?></span>
                          </div>
                          <div class="stat-period">vs Yesterday</div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <?php
                  $card_count++;
                }
                // Close the last row if necessary
                if ($card_count > 0) {
                  echo '</div></div>';
                }
              }
              ?>
            </div>
            <!-- End of panel body -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="createCampaignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo get_instance()->load->view('clients/campaigns/create.php') ?>
  </div>