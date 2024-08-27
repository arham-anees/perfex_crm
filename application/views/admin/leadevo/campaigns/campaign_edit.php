<?php init_head(); ?>
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
    margin-top: 20px;
  }

  .wizard-buttons .btn {
    margin: 0;
  }

  #nextBtn {
    margin-left: auto;
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

</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="padding: 20px; max-height:83vh;">
        <!-- Form for editing campaign details -->
        <?php echo form_open(admin_url('campaigns/edit/' . $campaign->id), ['id' => 'edit-campaign-form']); ?>
        
        <!-- Wizard Navigation Circles -->
        <div class="wizard-nav">
            <div class="wizard-circle active" data-step="1">1</div>
            <div class="line"></div>
            <div class="wizard-circle" data-step="2">2</div>
            <div class="line"></div>
            <div class="wizard-circle" data-step="3">3</div>
        </div>

        <!-- Step 1: Campaign Name and Description -->
        <div class="wizard-step active" data-step="1">
            <h3>Profile</h3>
            <div id="profile" class="payment-form">
                <label for="pname">Name</label>
                <input type="text" id="pname" name="name" class="form-control" 
                       value="<?php echo $campaign->name ?? ''; ?>" required>

                <label for="desc">Description</label>
                <textarea id="desc" name="description" class="form-control" required><?php echo $campaign->description ?? ''; ?></textarea>
            </div>
        </div>

        <!-- Step 2: Campaign Dates -->
        <div class="wizard-step" data-step="2">
            <h3>Timing</h3>
            <div class="date-selectors">
                <div class="date-selector">
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="start_date" class="form-control" 
                           value="<?php echo !empty($campaign->start_date) && strtotime($campaign->start_date) !== false 
                                       ? date('Y-m-d', strtotime($campaign->start_date)) 
                                       : ''; ?>" required
                           min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="date-selector">
                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" name="end_date" class="form-control" 
                           value="<?php echo !empty($campaign->end_date) && strtotime($campaign->end_date) !== false 
                                       ? date('Y-m-d', strtotime($campaign->end_date)) 
                                       : ''; ?>" required
                           min="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
        </div>

        <!-- Step 3: Campaign Status and Budget -->
        <div class="wizard-step" data-step="3">
            <h3>Status and Budget</h3>
            <div class="form-group">
                <label for="status_id"><?php echo _l('Status'); ?></label>
                <select id="status_id" name="status_id" class="form-control" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?php echo $status['id']; ?>" <?php echo $status['id'] == $campaign->status_id ? 'selected' : ''; ?>>
                            <?php echo $status['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="budget">Budget</label>
                <input type="number" id="budget" name="budget" class="form-control" value="<?php echo $campaign->budget ?? ''; ?>" required>
            </div>
        </div>

        <!-- Step Navigation Buttons -->
        <div class="wizard-buttons">
            <button class="btn btn-secondary" id="backBtn" style="display:none;"><i class="fas fa-angle-left"></i> Back</button>
            <button class="btn btn-primary" id="nextBtn">Next</button>
            <button type="submit" class="btn btn-primary" id="submit-btn" style="display:none;">Save Changes</button>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = 1;
    const totalSteps = 3; // Update to match the actual number of steps
    const steps = document.querySelectorAll('.wizard-step');
    const circles = document.querySelectorAll('.wizard-circle');
    const backBtn = document.getElementById('backBtn');
    const nextBtn = document.getElementById('nextBtn');

    function showStep(step) {
        steps.forEach((element, index) => {
            element.classList.toggle('active', index === step - 1);
        });
        circles.forEach((element, index) => {
            element.classList.toggle('active', index === step - 1);
            element.classList.toggle('completed', index < step - 1);
        });

        backBtn.style.display = step === 1 ? 'none' : 'inline-flex';
        nextBtn.textContent = step === totalSteps ? 'Save Changes' : 'Next';
    }

    nextBtn.addEventListener('click', function () {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        } else if (currentStep === totalSteps) {
            document.getElementById('edit-campaign-form').submit();
        }
    });

    backBtn.addEventListener('click', function (event) {
        event.preventDefault();
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(currentStep);

    // Handle form submission
    document.getElementById('edit-campaign-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(event.target);

        fetch('<?php echo site_url('campaigns/edit/' . $campaign->id); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error creating the campaign.');
        });
    });
});


</script>

<?php init_tail(); ?>
</body>

</html>