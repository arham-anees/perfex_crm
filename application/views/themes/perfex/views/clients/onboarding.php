<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="assets/css/onboarding.css" />
<div class="row">
    <div class="col-md-12 section-client-onboarding">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <h4 class="pull-left mleft10"><?php echo _l('Onboarding'); ?></h4>
                            <button type="button" class="btn btn-success pull-right display-block mleft10"
                                data-toggle="modal" data-target="#inviteFriendModal">
                                <i class="fa-solid fa-user-plus tw-mr-1"></i>
                                <?php echo _l('Invite Friend'); ?>
                            </button>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div class="row">
                                        <!-- Left Side: Onboarding Steps Content -->
                                        <div class="col-md-8">
                                            <div class="panel_s">
                                                <div class="panel-body">
                                                    <?php foreach ($steps as $step): ?>
                                                        <div class="step-container step-<?php echo $step['step_number']; ?>-content"
                                                            style="display: <?php echo ($completed_step + 1) == $step['step_number'] ? 'block' : 'none'; ?>">
                                                            <h5><?php echo $step['step_title']; ?></h5>
                                                            <div class="step-content">
                                                                <p><?php echo $step['step_content']; ?></p>
                                                                <?php if ($step['type'] == 'video' && !empty($step['content'])): ?>
                                                                    <video width="100%" controls>
                                                                        <source
                                                                            src="<?php echo htmlspecialchars($step['content']); ?>"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                <?php elseif ($step['type'] == 'link' && !empty($step['content'])): ?>
                                                                    <a href="<?php echo htmlspecialchars($step['content']); ?>"
                                                                        target="_blank"><?php echo $step['content']; ?></a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button id="complete-btn" class="btn btn-success btn-center" disabled
                                                    onclick="completeStep()">
                                                    <i class="fa-solid fa-check tw-mr-1"></i>
                                                    <?php echo _l('Complete and Continue'); ?>
                                                </button>
                                                <button id="finish-btn" class="btn btn-success btn-center"
                                                    style="display: none;" onclick="finishOnboarding()">
                                                    <i class="fa-solid fa-arrow-right tw-mr-1"></i>
                                                    <?php echo _l('Finish Onboarding'); ?>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Right Side: Onboarding Progress -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <p class="card-title"><?php echo _l('Onboarding Progress'); ?></p>
                                                    <div class="progress">
                                                        <div id="progress-bar" class="progress-bar" role="progressbar"
                                                            style="width: <?php echo ($completed_step / count($steps)) * 100; ?>%;"
                                                            aria-valuenow="<?php echo ($completed_step / count($steps)) * 100; ?>"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <?php echo round(($completed_step / count($steps)) * 100); ?>%
                                                        </div>
                                                    </div>
                                                    <p id="progress-text">
                                                        <?php echo $completed_step . '/' . count($steps); ?> actions
                                                        completed</p>
                                                    <ul id="progress-list" class="list-group">
                                                        <?php foreach ($steps as $step): ?>
                                                            <li class="list-group-item">
                                                                <i class="fa-solid fa-check tw-mr-1"></i>
                                                                <?php echo $step['step_title']; ?>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="inviteFriendModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <!-- Modal Header -->
            <div class="modal-header d-flex">
                <h4 class="modal-title w-100"><?php echo _l('Who do you want to invite?'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(site_url('invite'), ['id' => 'invite-friend-form']); ?>
                <div class="form-group">
                    <div class="input-container">
                        <div class="input-wrapper">
                            <input type="text" name="name" id="name" class="custom-input" required />
                            <label for="name" class="floating-label">Name</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-container">
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" class="custom-input" required />
                            <label for="email" class="floating-label">Email</label>
                        </div>
                    </div>
                </div>
                <input type="submit" value="<?php echo _l('Send Invitation'); ?>" class="btn btn-primary" />
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal for sign up alert -->
<div id="signupAlertModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="signupAlertModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="padding: 20px;">
            <div class="text-center d-flex">
                <p class="signup-title w-100" id="signupAlertModalLabel"><?php echo _l('Sign Up for Alerts'); ?></p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="text-center">
                <i class="fa-solid fa-envelope" style="color: rgb(255, 203, 3); font-size: 80px;"></i>
            </div>
            <div class="modal-body text-center">
                <p class="signup-text">
                    <?php echo _l('We\'ll never send marketing emails through these alerts. These email alerts are strictly to inform you about Ambassador Program latest updates.'); ?>
                </p>
            </div>
            <div class="text-center">
                <button onclick="completeStep()" type="button" class="signup-btn"
                    data-dismiss="modal"><?php echo _l('Sign Up and Continue'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var currentStep = parseInt('<?php echo $completed_step; ?>');

    document.addEventListener('DOMContentLoaded', function () {
        var completeBtn = document.getElementById('complete-btn');
        var finishBtn = document.getElementById('finish-btn');
        var progressBar = document.getElementById('progress-bar');
        var progressText = document.getElementById('progress-text');
        var progressList = document.getElementById('progress-list').querySelectorAll('.list-group-item i');
        var totalSteps = progressList.length;
        var progressPercentagePerStep = 100 / totalSteps;

        // Initialize tick styles
        for (let i = 0; i < totalSteps; i++) {
            progressList[i].classList.add('tick-inactive');
            if (i < currentStep) {
                progressList[i].classList.remove('tick-inactive');
                progressList[i].classList.add('tick-completed');
            }
        }

        var progressPercentage = currentStep * progressPercentagePerStep;
        progressBar.style.width = progressPercentage + '%';
        progressBar.setAttribute('aria-valuenow', progressPercentage);
        progressBar.textContent = Math.round(progressPercentage) + '%';

        // Handle video ended event
        var video = document.querySelector('.step-' + (currentStep + 1) + '-content video');
        if (video) {
            video.addEventListener('ended', function () {
                completeBtn.disabled = false;
                completeStep();
            });
        } else {
            completeBtn.disabled = false;
        }

        // Handle link click event
        var link = document.querySelector('.step-' + (currentStep + 1) + '-content a');
        if (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent the default link behavior to allow processing the step
                completeStep();

                // Optionally open the link in a new tab after processing
                window.open(link.href, '_blank');
            });
        }

        completeBtn.addEventListener('click', function () {
            if (currentStep < totalSteps) {
                completeStep();
            }
        });

        function completeStep() {
            $.post('<?php echo site_url('onboarding/update_step'); ?>', {
                csrf_token_name: '<?php echo $this->security->get_csrf_hash(); ?>',
                onboarding_step: currentStep + 1
            }, function () {
                currentStep++;
                if (currentStep < totalSteps) {
                    document.querySelector('.step-' + (currentStep) + '-content').style.display = 'none';
                    document.querySelector('.step-' + (currentStep + 1) + '-content').style.display = 'block';
                }
                updateProgressBar();
            });
        }

        function updateProgressBar() {
            var progressPercentage = currentStep * progressPercentagePerStep;
            progressBar.style.width = progressPercentage + '%';
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.textContent = Math.round(progressPercentage) + '%';
            progressText.textContent = currentStep + '/' + totalSteps + ' actions completed';

            if (currentStep >= totalSteps) {
                finishBtn.style.display = 'block';
                completeBtn.style.display = 'none';
                progressBar.style.width = '100%';
                progressBar.setAttribute('aria-valuenow', 100);
                progressBar.textContent = '100%';
                for (let i = 0; i < totalSteps; i++) {
                    progressList[i].classList.remove('tick-inactive');
                    progressList[i].classList.add('tick-completed');
                    progressList[i].style.color = 'yellow'; // Change tick color to yellow
                }
            }
        }
    });

    function finishOnboarding() {
        alert('Congratulations! You have completed all the steps.');
        window.location.href = '<?php echo site_url(); ?>';
    }


</script>