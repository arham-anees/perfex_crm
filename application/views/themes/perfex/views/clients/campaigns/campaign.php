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

  .gridcontainer1 {
    display: flex;
  }

  .grid-container {
    display: flex;
    grid-template-columns: auto auto auto auto;
    gap: 50px;
    margin-top: -2%;
    padding: 1px;
  }

  .grid-container>div {
    font-size: 12px;
  }

  .alt-text5 {
    margin-left: 13%;
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
    width:100%;
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
  justify-content: space-between; /* Add space between dropdowns */
  margin-top: 20px; /* Adjust margin as needed */
}

.time-selector {
  flex: 1; /* Allow dropdowns to grow equally */
  margin-right: 10px; /* Space between dropdowns */
}

.time-selector:last-child {
  margin-right: 0; /* Remove margin from the last dropdown */
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
              <table class="table dt-table scroll-responsive">
                <thead>
                  <tr>
                    <th><?php echo _l('Name'); ?></th>
                    <th><?php echo _l('Description'); ?></th>
                    <th><?php echo _l('Active'); ?></th>
                    <th><?php echo _l('Actions'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($campaigns as $campaign): ?>
                    <tr>
                      <td><?php echo $campaign->name; ?></td>
                      <td><?php echo $campaign->description; ?></td>
                      <td><?php echo $campaign->is_active ? 'Yes' : 'No'; ?></td>
                      <td>
                        <a href="<?php echo site_url('campaigns/campaign/' . $campaign->id); ?>"
                          class="btn btn-default btn-icon">
                          <i class="fa fa-eye"></i>
                        </a>
                        <a href="<?php echo site_url('campaigns/edit/' . $campaign->id); ?>"
                          class="btn btn-default btn-icon">
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a href="<?php echo site_url('campaigns/delete/' . $campaign->id);?>"
                          class="btn btn-danger btn-icon"
                          onclick="return confirm('Are you sure you want to delete this campaign ?');">
                          <i class="fa fa-remove"></i>
                        </a>
                      </td>
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
        <div class="line"></div>
        <div class="wizard-circle" data-step="5">5</div>
        <div class="line"></div>
        <div class="wizard-circle" data-step="6">6</div>
      </div>
      <div class="gridcontainer1">
        <div class="grid-container">
          <div class="alt-text1">Industry</div>
          <div class="alt-text2">Locations</div>
          <div class="alt-text3">Timing</div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <div class="grid-container">
          <div class="alt-text4">Deal</div>
          <div class="alt-text5">Quality</div>
          <div class="alt-text6">Payment</div>
        </div>
      </div>

      <br><br>

      <!-- Industry -->
      <div class="wizard-step" data-step="1">
        <h3>Select your lead type of interest</h3>
        <div class="form-group text-left">
          <label for="industry"><?php echo _l('Industry'); ?></label>
          <select name="industry" class="selectpicker" data-width="100%"
            data-none-selected-text="<?php echo _l('Select Industry'); ?>">
            <option value=""><?php echo _l('Select Industry'); ?></option>
            <?php foreach ($industries as $industry): ?>
              <option value="<?php echo $industry['id']; ?>"><?php echo $industry['name']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Locations -->
      <div class="wizard-step" data-step="2">
        <h3>Locations</h3>
        <div class="form-group text-left">
          <label for="countries"><?php echo _l('countries'); ?></label>
          <select name="countries" class="selectpicker" data-width="100%" id="countryDropdown" multiple
            data-none-selected-text="<?php echo _l('Select Country'); ?>">
            <option value=""><?php echo _l('Select Country'); ?></option>
            <?php foreach ($countries as $country): ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['short_name']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="selected-options" id="selected-options"></div>

      </div> 

      <!-- Timing -->

      <div class="wizard-step" data-step="3">
        <h3>Timing</h3>
        <div id="campaign-time">
          <div class="week">
            <div class="days">M</div>
            <div class="days">T</div>
            <div class="days">W</div>
            <div class="days">T</div>
            <div class="days">F</div>
            <div class="days">S</div>
            <div class="days">S</div>
          </div>

          <div class="time-selectors">
            <div class="time-selector">
              <label for="time-from">From:</label>
              <select id="time-from" name="time-from" class="selectpicker" data-width="100%">
                <!-- Add time slot options here -->
                <option value="08:00">08:00</option>
                <option value="09:00">09:00</option>
                <option value="10:00">10:00</option>
                <!-- More options as needed -->
              </select>
            </div>
            <div class="time-selector">
              <label for="time-to">To:</label>
              <select id="time-to" name="time-to" class="selectpicker" data-width="100%">
                <!-- Add time slot options here -->
                <option value="17:00">17:00</option>
                <option value="18:00">18:00</option>
                <option value="19:00">19:00</option>
                <!-- More options as needed -->
              </select>
            </div>
          </div>

        </div>

      </div>

      <!-- Deals -->
      <div class="wizard-step" data-step="4">
        <h3>Deal</h3>
        <div class="radio-container">
          <input type="radio" id="option1" name="options">
          <label for="option1">$75 (buy exclusively)</label>
          <span class="info-icon" data-tooltip="Defining if he wants to buy exclusively or non-exclusively prospects"><i
              class="fa fa-info-circle" style="font-size:20px"></i></span>
        </div>
        <div class="radio-container">
          <input type="radio" id="option2" name="options">
          <label for="option2">$35 (buy non-exclusively)</label>
          <span class="info-icon" data-tooltip="Defining if he wants to buy exclusively or non-exclusively prospects"><i
              class="fa fa-info-circle" style="font-size:20px"></i></span>
        </div>

      </div>

      <!-- Quality -->

      <div class="wizard-step" data-step="5">
        <h3>Quality</h3>
        <form class="form">
          <label>
            <input type="checkbox" name="verification" value="staff">
            Verified by Staff
          </label>
          <label>
            <input type="checkbox" name="verification" value="sms">
            Verified by SMS
          </label>
          <label>
            <input type="checkbox" name="verification" value="whatsapp">
            Verified by WhatsApp
          </label>
          <label>
            <input type="checkbox" name="verification" value="coherence">
            Verified by Coherence
          </label>
        </form>
      </div>


      <!-- Payment -->
      <div class="wizard-step" data-step="6">
        <h3>Payment</h3>
        <form id="payment-form" class="payment-form">
          <label for="card-number">Card Number</label>
          <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" required>

          <label for="expiry-date">Expiry Date</label>
          <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>

          <label for="cvv">CVV</label>
          <input type="text" id="cvv" name="cvv" placeholder="123" required>

          <label for="amount">Amount</label>
          <input type="number" id="amount" name="amount" placeholder="Amount to be debited" required>

          <!-- <button type="submit" class="btn btn-primary">Submit Payment</button> -->
        </form>
      </div>



      <div class="wizard-buttons">
        <button class="btn btn-secondary" id="backBtn"><i class="fas fa-angle-left" style="font-size:19px"></i>
          Back</button>
        <button class="btn btn-primary" id="nextBtn">Next</button>
      </div>
    </div>
  </div>
</div>
</div>
<?php init_tail(); ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 6;
    const steps = document.querySelectorAll('.wizard-step');
    const circles = document.querySelectorAll('.wizard-circle');
    const backBtn = document.getElementById('backBtn');
    const nextBtn = document.getElementById('nextBtn');
    const days = document.querySelectorAll('.days');


    function showStep(step) {
      steps.forEach((element, index) => {
        element.classList.toggle('active', index === step - 1);
      });
      circles.forEach((element, index) => {
        element.classList.toggle('active', index === step - 1);
        element.classList.toggle('completed', index < step - 1);
      });

      // Hide back button
      backBtn.style.display = step === 1 ? 'none' : 'inline-flex';

      // Show Finish Button at last page
      nextBtn.textContent = step === totalSteps ? 'Finish' : 'Next';
    }

    days.forEach(day => {
      day.addEventListener('click', function () {
        this.classList.toggle('active');
      });
    });

    nextBtn.addEventListener('click', function () {
      if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
      } else if (currentStep === totalSteps) {
        // Finish button action
        collectAndSendData();

      }
    });

    backBtn.addEventListener('click', function () {
      if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
      }
    });


    function collectAndSendData() {
    const formData = new FormData();

    // Collect Industry data
    formData.append('industry', document.querySelector('select[name="industry"]').value);

    // Collect Locations data
    const selectedCountries = Array.from(document.querySelectorAll('#countryDropdown option:checked')).map(option => option.value).join(',');
    formData.append('countries', selectedCountries);

    // Collect Timing data
    const selectedDays = Array.from(document.querySelectorAll('.days.active')).map(day => day.getAttribute('data-day')).join(',');
    formData.append('days', selectedDays);
    formData.append('time-from', document.getElementById('time-from').value);
    formData.append('time-to', document.getElementById('time-to').value);

    // Collect Deal data
    const selectedDeal = document.querySelector('input[name="options"]:checked');
    if (selectedDeal) {
      formData.append('deal', selectedDeal.id);
    }

    // Collect Quality data
    const selectedQualities = Array.from(document.querySelectorAll('input[name="verification"]:checked')).map(input => input.value).join(',');
    formData.append('quality', selectedQualities);

    // Collect Payment data
    formData.append('card-number', document.getElementById('card-number').value);
    formData.append('expiry-date', document.getElementById('expiry-date').value);
    formData.append('cvv', document.getElementById('cvv').value);
    formData.append('amount', document.getElementById('amount').value);

    // Log FormData entries
    for (let [key, value] of formData.entries()) {
      console.log(key, value);
    }
     console.log(formData);
    }

    showStep(currentStep);
  });
</script>
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

</script>



