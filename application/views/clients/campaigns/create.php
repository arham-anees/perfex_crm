<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="padding: 20px; max-height:83vh;">
        <?php echo form_open('campaigns', ['id' => 'create-campaign-form']); ?>
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
            <div class="line"></div>
            <div class="wizard-circle" data-step="7">7</div>
        </div>
        <div class="gridcontainer1">
            <div class="grid-container">
                <div class="alt-text">Profile</div>
                <div class="alt-text1">Industry</div>
                <div class="alt-text2">Locations</div>
                <div class="alt-text3">Timing</div> &nbsp;&nbsp;&nbsp;
            </div>
            <div class="grid-container" style="gap:45px">
                <div class="alt-text4">Deal</div>
                <div class="alt-text5">Quality</div>
                <div class="alt-text6">Payment</div>
            </div>
        </div>

        <div class="wizard-step" data-step="1">
            <h3>Profile</h3>
            <div id="profile" class="payment-form">
                <label for="pname">Name</label>
                <input type="text" id="pname" name="campaing-name" placeholder="Enter name of campaign" required>

                <label for="desc">Description</label>
                <textarea type="text" id="desc" placeholder="Enter description for campaign"
                    class="form-control"></textarea>
            </div>
        </div>

        <!-- Industry -->
        <div class="wizard-step" data-step="2">
            <h3>Select your lead type of interest</h3>
            <div class="form-group text-left">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>">
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
        <div class="wizard-step" data-step="3">
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

        <div class="wizard-step" data-step="4">
            <h3>Timing</h3>
            <div id="campaign-time">

                <!-- Start Date and End Date -->
                <div class="date-selectors">
                    <div class="date-selector">
                        <label for="start-date">Start Date:</label>
                        <input type="date" id="start-date" name="start_date" class="form-control">
                    </div>
                    <div class="date-selector">
                        <label for="end-date">End Date:</label>
                        <input type="date" id="end-date" name="end_date" class="form-control">
                    </div>
                </div>

                <!-- Cap Options Button -->
                <div id="cap-options">
                    <button type="button" id="cap-button" class="btn btn-primary">Add a cap on the period</button>
                </div>

                <!-- Container for all cap inputs -->
                <div id="caps-container"></div>

                <!-- Submit Button for Caps -->
                <div id="cap-submit-container" style="display: none; margin-top: 20px;">
                    <button type="button" id="cap-submit-button" class="btn btn-success">Submit Cap</button>
                </div>

                <!-- Display Container for Submitted Caps -->
                <div id="submitted-caps-container" style="margin-top: 20px;">
                    <h4>Submitted Caps</h4>
                    <ul id="submitted-caps-list" style="list-style: none; padding-left: 0;"></ul>
                </div>
            </div>
        </div>

        <!-- Deals -->
        <!-- Deals  -->
        <div class="wizard-step" data-step="5">
            <h3>Deal</h3>

            <div class="radio-container">
                <input type="radio" id="option1" name="deal" value="1" checked>
                <label for="option1">Buy Exclusive Prospects</label>
                <span class="info-icon" data-tooltip="<?php echo get_information('exclusive'); ?>">
                    <i class="fa fa-info-circle" style="font-size:20px"></i>
                </span>
            </div>

            <div class="radio-container">
                <input type="radio" id="option2" name="deal" value="0">
                <label for="option2">Buy Non-Exclusive Prospects</label>
                <span class="info-icon" data-tooltip="<?php echo get_information('non_exclusive'); ?>">
                    <i class="fa fa-info-circle" style="font-size:20px"></i>
                </span>
            </div>
        </div>

        <!-- Quality -->

        <div class="wizard-step" data-step="6">
            <h3>Quality</h3>
            <label>
                <input type="checkbox" name="verification" value="staff">
                Verified by Staff
            </label><br />
            <label>
                <input type="checkbox" name="verification" value="sms">
                Verified by SMS
            </label><br />
            <label>
                <input type="checkbox" name="verification" value="whatsapp">
                Verified by WhatsApp
            </label><br />
            <label>
                <input type="checkbox" name="verification" value="coherence">
                Verified by Coherence
            </label><br />
        </div>


        <!-- Payment -->
        <div class="wizard-step" data-step="7">
            <h3>Payment</h3>
            <!-- <form id="payment-form" class="payment-form"> -->
            <!-- <label for="card-number">Card Number</label>
          <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" required>

          <label for="expiry-date">Expiry Date</label>
          <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>

          <label for="cvv">CVV</label>
          <input type="text" id="cvv" name="cvv" placeholder="123" required> -->
            <div id="payment-form" class="payment-form">
                <label for="budget">Budget</label>
                <input type="number" id="budget" name="budget" placeholder="Amount to be debited" required>
            </div>
            <!-- <button type="submit" class="btn btn-primary">Submit Payment</button> -->
            <!-- </form> -->
        </div>



        <div class="wizard-buttons">
            <button class="btn btn-secondary" id="backBtn"><i class="fas fa-angle-left" style="font-size:19px"></i>
                Back</button>
            <button class="btn btn-primary" id="nextBtn">Next</button>
        </div>
        <?php form_close(); ?>
    </div>
</div>


<script>

    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = 7;
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
            if (currentStep != 4 && currentStep != 5)
                document.getElementById('nextBtn').disabled = true;
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            } else if (currentStep === totalSteps) {
                // Finish button action
                $('#create-campaign-form').submit();

            }
        });

        backBtn.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
        $('#createCampaignModal').on('shown.bs.modal', function () {
            showStep(currentStep);
            document.getElementById('nextBtn').disabled = true;
        });

        $('#createCampaignModal input[name=campaing-name]').on('input', function (e) {
            document.getElementById('nextBtn').disabled = $(e.target).val().length == 0;
        })
        $('#createCampaignModal input[name=budget]').on('input', function (e) {
            document.getElementById('nextBtn').disabled = $(e.target).val().length == 0;
        })
        $('#createCampaignModal select[name=industry]').on('change', function (e) {
            document.getElementById('nextBtn').disabled = $(e.target).val().length == 0;
        })
        $('#createCampaignModal select[name=countries]').on('change', function (e) {
            document.getElementById('nextBtn').disabled = $(e.target).val().length == 0;
        })
        $('#createCampaignModal input[name=start_date]').on('change', function (e) {
            document.getElementById('nextBtn').disabled = $('#createCampaignModal input[name=start_date]').val().length == 0 && $('#createCampaignModal input[name=end_date]').val().length == 0;
        })
        $('#createCampaignModal input[name=end_date]').on('change', function (e) {
            document.getElementById('nextBtn').disabled = $('#createCampaignModal input[name=start_date]').val().length == 0 && $('#createCampaignModal input[name=end_date]').val().length == 0;
        })

        // document.getElementById('cap-checkbox').addEventListener('change', function () {
        //   var container = document.getElementById('max-prospects-container');
        //   if (this.checked) {
        //     container.style.display = 'block';
        //   } else {
        //     container.style.display = 'none';
        //   }
        // });

        // function collectAndSendData() {
        //   const formData = new FormData();

        //   // Collect Industry data
        //   formData.append('industry', document.querySelector('select[name="industry"]').value);

        //   // Collect Locations data
        //   const selectedCountries = Array.from(document.querySelectorAll('#countryDropdown option:checked')).map(option => option.value).join(',');
        //   formData.append('countries', selectedCountries);

        //   // Collect Timing data
        //   const selectedDays = Array.from(document.querySelectorAll('.days.active')).map(day => day.getAttribute('data-day')).join(',');
        //   formData.append('days', selectedDays);
        //   formData.append('time-from', document.getElementById('time-from').value);
        //   formData.append('time-to', document.getElementById('time-to').value);

        //   // Collect Deal data
        //   const selectedDeal = document.querySelector('input[name="options"]:checked');
        //   if (selectedDeal) {
        //     formData.append('deal', selectedDeal.id);
        //   }

        //   // Collect Quality data
        //   const selectedQualities = Array.from(document.querySelectorAll('input[name="verification"]:checked')).map(input => input.value).join(',');
        //   formData.append('quality', selectedQualities);

        //   // Collect Payment data
        //   // formData.append('card-number', document.getElementById('card-number').value);
        //   // formData.append('expiry-date', document.getElementById('expiry-date').value);
        //   // formData.append('cvv', document.getElementById('cvv').value);
        //   formData.append('Budget', document.getElementById('budget').value);

        //   // Log FormData entries
        //   for (let [key, value] of formData.entries()) {
        //     console.log(key, value);
        //   }
        //   console.log(formData);
        // }

        function collectAndSendData() {
            const formData = new FormData();


            // collect profile data-day
            const nameInput = document.getElementById('pname');
            const descInput = document.getElementById('desc');

            console.log('nameInput:', nameInput.value);
            console.log('descInput:', descInput.value);

            if (nameInput && descInput) {
                formData.append('name', nameInput.value);
                formData.append('description', descInput.value);
            } else {
                console.error('Required elements not found');
            }


            // Collect Industry data
            const industrySelect = document.querySelector('select[name="industry"]');
            if (industrySelect) {
                formData.append('industry_id', industrySelect.value);
            }

            // Collect Locations data
            const selectedCountries = Array.from(document.querySelectorAll('#countryDropdown option:checked')).map(option => option.value).join(',');
            formData.append('country_id', selectedCountries);

            // Collect Timing data
            // const selectedDays = Array.from(document.querySelectorAll('.days.active')).map(day => day.textContent).join(',');
            // const timeFromElement = document.getElementById('time-from');
            // const timeToElement = document.getElementById('time-to');
            // const timeFrom = timeFromElement ? timeFromElement.value : '';
            // const timeTo = timeToElement ? timeToElement.value : '';
            // const timings = JSON.stringify({ days: selectedDays.split(','), from: timeFrom, to: timeTo });
            // formData.append('timings', timings);
            formData.append('start_date', $('input[name=start_date]').val());
            formData.append('end_date', $('input[name=end_date]').val());
            var capValues = $('input[name="caps[]"]').map(function () {
                return $(this).val();
            }).get();

            formData.append('caps[]', capValues);

            // Collect Deal data
            formData.append('deal', $('input[name=deal]:checked').val());

            // Collect Quality data
            const selectedQualities = Array.from(document.querySelectorAll('input[name="verification"]:checked')).map(input => input.value).join(',');
            formData.append('verify_by_staff', selectedQualities.includes('staff') ? 1 : 0);
            formData.append('verify_by_sms', selectedQualities.includes('sms') ? 1 : 0);
            formData.append('verify_by_whatsapp', selectedQualities.includes('whatsapp') ? 1 : 0);
            formData.append('verify_by_coherence', selectedQualities.includes('coherence') ? 1 : 0);

            // Collect Budget data
            const budgetInput = document.getElementById('budget');
            formData.append('budget', budgetInput ? budgetInput.value : '');
            formData.append($('[data-step=2] input[type=hidden]')[0].name, $('[data-step=2] input[type=hidden]').val());

            // Log FormData entries
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            // Get CSRF token value
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            // Send FormData via AJAX
            fetch('<?php echo site_url('campaigns/create_campaign'); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optionally redirect or update the UI
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error, csrfToken);
                    alert('There was an error creating the campaign.');
                }).finally(() => {
                    isFormSubmitted = false;
                });
        }


        showStep(currentStep);

        var isFormSubmitted = false;
        $('#create-campaign-form').on('submit', function (event) {
            // Prevent the default form submission
            event.preventDefault();
            if (isFormSubmitted) return;
            isFormSubmitted = true;
            console.log('submitted')
            collectAndSendData();
        });

    });
</script>
<script>
    var capsArray = []; // Array to store the caps

    // Function to update the hidden input with the caps array
    function updateCapsData() {
        //document.getElementById('caps-data').value = JSON.stringify(capsArray);
    }

    // Function to render the submitted caps list
    function renderCapsList() {
        var list = document.getElementById('submitted-caps-list');
        list.innerHTML = ''; // Clear the list

        capsArray.forEach(function (cap, index) {
            var listItem = document.createElement('li');
            listItem.style.marginBottom = '10px';

            listItem.innerHTML = 'Cap Date and Time: ' + cap.dateTime + ', Cap Value: ' + cap.value +
                '<input type="hidden" value="' + cap.dateTime + ' ' + cap.value + '" name="caps[]"/> <button type="button" class="btn btn-danger btn-sm" onclick="removeCap(' + index + ')">Remove</button>';

            list.appendChild(listItem);
        });

        updateCapsData();
    }

    // Function to remove a cap
    function removeCap(index) {
        capsArray.splice(index, 1);
        renderCapsList();
    }

    // Add Cap button functionality
    document.getElementById('cap-button').addEventListener('click', function () {
        // Get the start and end dates
        var startDate = document.getElementById('start-date').value;
        var endDate = document.getElementById('end-date').value;

        // Validate that start and end dates are set
        if (!startDate || !endDate) {
            alert('Please set the start and end dates first.');
            return;
        }

        // Create a new div to hold the cap inputs
        var capDiv = document.createElement('div');
        capDiv.className = 'cap-inputs-container';
        capDiv.style.marginTop = '20px';

        // Add the datetime-local input
        var dateTimeLabel = document.createElement('label');
        dateTimeLabel.textContent = 'Cap Date and Time:';
        var dateTimeInput = document.createElement('input');
        dateTimeInput.type = 'datetime-local';
        dateTimeInput.name = 'cap-date-time[]';
        dateTimeInput.className = 'form-control';
        dateTimeInput.min = startDate + 'T00:00';
        dateTimeInput.max = endDate + 'T23:59';
        dateTimeInput.style.marginBottom = '10px';

        // Add the number input
        var integerLabel = document.createElement('label');
        integerLabel.textContent = 'Cap Value:';
        var integerInput = document.createElement('input');
        integerInput.type = 'number';
        integerInput.name = 'cap-integer[]';
        integerInput.className = 'form-control';
        integerInput.placeholder = 'Enter value';

        // Append inputs to the capDiv
        capDiv.appendChild(dateTimeLabel);
        capDiv.appendChild(dateTimeInput);
        capDiv.appendChild(integerLabel);
        capDiv.appendChild(integerInput);

        // Append the capDiv to the caps-container
        document.getElementById('caps-container').appendChild(capDiv);

        // Show the submit button for caps
        document.getElementById('cap-submit-container').style.display = 'block';
    });

    // Submit Cap button functionality
    document.getElementById('cap-submit-button').addEventListener('click', function () {
        // Get the last added cap input values
        var lastCapContainer = document.querySelector('#caps-container .cap-inputs-container:last-child');
        var dateTime = lastCapContainer.querySelector('input[name="cap-date-time[]"]').value;
        var value = lastCapContainer.querySelector('input[name="cap-integer[]"]').value;

        // Validate inputs
        if (!dateTime || !value) {
            alert('Please fill out both fields.');
            return;
        }

        // Add to caps array
        capsArray.push({ dateTime: dateTime, value: value });

        // Clear the last added cap inputs (optional)
        lastCapContainer.querySelector('input[name="cap-date-time[]"]').value = '';
        lastCapContainer.querySelector('input[name="cap-integer[]"]').value = '';

        // Render the list of submitted caps
        renderCapsList();
    });

    // Initialize by clicking the button once to create the first set of inputs
    // document.getElementById('cap-button').click();
</script>