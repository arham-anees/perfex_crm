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

<div class="row main_row">
  <div class="col-md-12">
    <div class="panel_s">
      <div class="panel-body">

        <div class="_buttons">
          <a data-toggle="modal" data-target="#createCampaignModal"
            class="btn btn-primary pull-left display-block mleft10">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            <?php echo _l('New Campaign'); ?>
          </a>
          <div class="clearfix"></div>
        </div>
        <form id="filterForm" action="" method="post">
            <?php $csrf = $this->security->get_csrf_hash(); ?>
            <div class="row">
              <div class="col-md-4">
                  <div class="filter-group">
                      <label for="budget_range_start"><?php echo _l('Budget Range start'); ?></label>
                      <input type="text" id="budget_range_start" name="budget_range_start" class="filter-input"  value="<?= !empty($_POST['budget_range_start']) ? $_POST['budget_range_start']:''?>">
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="budget_range_end"><?php echo _l('Budget Range end'); ?></label>
                        <input type="text" id="budget_range_end" name="budget_range_end" class="filter-input" value="<?= !empty($_POST['budget_range_end']) ? $_POST['budget_range_end']:''?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="start_date"><?php echo _l('From'); ?></label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value=" <?= !empty($_POST['start_date'])?date('d-m-Y', strtotime($_POST['start_date'])):''?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="end_date"><?php echo _l('To'); ?></label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value=" <?=!empty($this->input->post('end_date')) ? $this->input->post('end_date '):''?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="type"><?php echo _l('Status'); ?></label>
                        <select id="type" name="status" class="filter-input">
                          <option value="">Select Status</option>
                           <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status->name; ?>" <?=$this->input->post('status')==$status->name ?'selected':''?>><?php echo $status->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="deal"><?php echo _l('Deal'); ?></label>
                        <select id="deal" name="deal" class="filter-input">
                          <option value="">Select Deal</option>
                              <option value="1" <?=$this->input->post('deal')=='1' ?'selected':''?>>Exclusive
                              </option>
                                <option value="0" <?=$this->input->post('deal')=='0' ?'selected':''?>>Non-Exclusive
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
            </div>
        </form>
        <hr class="hr-panel-heading" />

        <?php if (!empty($campaigns)): ?>
          <table class="table dt-table scroll-responsive" id="campaign-list">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('leadevo_description'); ?></th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('budget'); ?></th>
                <th><?php echo _l('deal'); ?></th>
                <th><?php echo _l('Start Date'); ?></th>
                <th><?php echo _l('End Date'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($campaigns as $campaign): ?>
                <tr>
                  <td>
                    <?php echo $campaign->id ?? ''; ?>
                  </td>
                  <td>
                    <?php echo $campaign->name ?? '-'; ?>
                    <div class="row-options">
                      <a href="<?php echo site_url('campaigns/campaign/' . $campaign->id); ?>">View</a>
                      <?php if (isset($campaign->invoice_id)): ?> | <a
                          href="<?php echo site_url('invoice/' . $campaign->invoice_id . '/' . $campaign->invoice_hash); ?>">Invoice</a>
                      <?php endif; ?>
                      <?php
                      $current_date = date('Y-m-d');
                      $start_date = !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                        ? date('Y-m-d', strtotime($campaign->start_date))
                        : 'N/A';

                      if (($campaign->status_id == 3)): ?>
                        | <a href="<?php echo site_url('campaigns/edit/' . $campaign->id); ?>">Edit</a>
                      <?php endif; ?>

                      <?php if ($campaign->status_id == 3): ?>
                        | <a href="<?php echo site_url('campaigns/delete/' . $campaign->id); ?>" class="text-danger"
                          onclick="return confirm('Are you sure you want to delete this campaign ?');">Delete</a>

                      <?php endif; ?>
                    </div>
                  </td>
                  <td><?php echo $campaign->description ?? '-'; ?></td>
                  <td><?php echo $campaign->status_name ?? '-'; ?>
                    <?php if ($campaign->invoice_status == 1): ?>
                      <br><span class="text-danger" style="font-size:11px">Please pay the invoice</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $campaign->budget ?? '0'; ?></td>
                  <td><?php echo $campaign->deal == 1 ? 'Exclusive' : 'Non-exclusive'; ?></td>
                  <td><?php echo !empty($campaign->start_date) && strtotime($campaign->start_date) !== false
                    ? date('d M Y', strtotime($campaign->start_date))
                    : ''; ?></td>
                  <td><?php echo !empty($campaign->end_date) && strtotime($campaign->end_date) !== false
                    ? date('d M Y', strtotime($campaign->end_date))
                    : ''; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p><?php echo _l('no_campaigns_found'); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </div>


  <!-- Modal -->



  <div id="createCampaignModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <?php echo get_instance()->load->view('clients/campaigns/create.php') ?>
  </div>


  </body>

  </html>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const countriesSelect = document.querySelector('select[name="countries"]');
      const selectedOptionsContainer = document.getElementById('selected-options');

      countriesSelect.addEventListener('change', function () {
        updateSelectedOptions();
      });

      function updateSelectedOptions() {
        selectedOptionsContainer.innerHTML = '';

        Array.from(countriesSelect.selectedOptions).forEach(function (selectedOption) {
          const option = document.createElement('div');
          option.className = 'selected-option';
          option.innerText = selectedOption.text;

          const removeSpan = document.createElement('span');
          removeSpan.innerHTML = '&times;';
          removeSpan.style.cursor = 'pointer';
          removeSpan.addEventListener('click', function () {
            selectedOption.selected = false;
            updateSelectedOptions();
            // Refresh the select picker to reflect changes
            $(countriesSelect).selectpicker('refresh');
          });

          option.appendChild(removeSpan);
          selectedOptionsContainer.appendChild(option);
        });
      }

      // Initial call to populate selected options on page load
      updateSelectedOptions();
    });
  </script>

  <script>
    $('#campaign-list').DataTable()
  </script>