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
        <hr class="hr-panel-heading" />

        <?php if (!empty($campaigns)): ?>
          <table class="table dt-table scroll-responsive" id="campaign-list">
            <thead>
              <tr>
                <th><?php echo _l('name'); ?></th>
                <th><?php echo _l('description'); ?></th>
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
                    <?php echo $campaign->name; ?>
                    <div class="row-options">
                      <a href="<?php echo site_url('campaigns/campaign/' . $campaign->id); ?>">View</a> |
                      <a href="<?php echo site_url('campaigns/edit/' . $campaign->id); ?>">Edit</a> |
                      <a href="<?php echo site_url('campaigns/delete/' . $campaign->id); ?>" class="text-danger"
                        onclick="return confirm('Are you sure you want to delete this campaign ?');">Delete</a>
                    </div>
                  </td>
                  <td><?php echo $campaign->description; ?></td>
                  <td><?php echo $campaign->status_name; ?></td>
                  <td><?php echo $campaign->budget; ?></td>
                  <td><?php echo $campaign->deal == 1 ? 'Exclusive' : 'Non-exclusive'; ?></td>
                  <td><?php echo date('Y-m-d', strtotime($campaign->start_date)); ?></td>
                  <td><?php echo date('Y-m-d', strtotime($campaign->end_date)); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p><?php echo _l('No campaigns found.'); ?></p>
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