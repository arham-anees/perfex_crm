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
                                        <!-- Left Side: Video and Facebook Group Text -->
                                        <div class="col-md-8">
                                            <div class="panel_s">
                                                <div class="panel-body step-1">
                                                    <div class="videos step-1-content">

                                                        <div id="video-section">
                                                            <?php if (!empty($videos)): ?>
                                                                <?php foreach ($videos as $video): ?>
                                                                    <div id="video-section">
                                                                        <video id="welcome-video" width="100%" controls>
                                                                            <source
                                                                                src="<?php echo htmlspecialchars($video['url']); ?>"
                                                                                type="video/mp4">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <p>No videos available.</p>
                                                            <?php endif; ?>
                                                        </div>

                                                    </div>


                                                    <!-- Text and button for joining Facebook group -->
                                                    <div id="facebook-group-section" class="step-2-content">
                                                        <div class="card mt-4">
                                                            <div class="card-body">

                                                                <p><a id="join-facebook-group"
                                                                        class="facebook-group"><?php echo _l('Join our private Facebook group'); ?></a>
                                                                    <?php echo _l('group dedicated specifically for ambassadors. In the group, you\'ll get access to other successful ambassadors, learn the latest marketing strategies, get help from our support team, and stay informed on all the latest product updates.'); ?>
                                                                </p>

                                                                </a>
                                                                </p>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Text and button for signing up for alerts -->
                                                    <div id="sign-up-alert-section" class="step-3-content">
                                                        <div class="card mt-4">
                                                            <div class="card-body">
                                                                <p> <a id="sign-up-alert" class="sign-up-alert"
                                                                        data-toggle="modal"
                                                                        data-target="#signupAlertModal"><?php echo _l('Sign Up for Alerts'); ?></a>
                                                                    <?php echo _l("Sign up for our email alerts to stay informed about the latest updates, opportunities, and important information."); ?>
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!-- Coming Soon text -->
                                                    <div class="videos step-4-content" id="coming-soon-section">
                                                        <?php if (!empty($videos)): ?>
                                                            <?php foreach ($videos as $video): ?>
                                                                <div>
                                                                    <video id="coming-soon-" style=" width: 100%;" width="100%"
                                                                        controls>
                                                                        <source
                                                                            src="<?php echo htmlspecialchars($video['url']); ?>"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <p>No videos available.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="videos step-5-content" id="coming-soon-section">
                                                        <?php if (!empty($videos)): ?>
                                                            <?php foreach ($videos as $video): ?>
                                                                <div>
                                                                    <video id="coming-soon-section" style=" width: 100%;"
                                                                        width="100%" controls>
                                                                        <source
                                                                            src="<?php echo htmlspecialchars($video['url']); ?>"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <p>No videos available.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="videos step-6-content">
                                                        <?php if (!empty($videos)): ?>
                                                            <?php foreach ($videos as $video): ?>
                                                                <div>
                                                                    <video id="coming-soon-section" style=" width: 100%;"
                                                                        width="100%" controls>
                                                                        <source
                                                                            src="<?php echo htmlspecialchars($video['url']); ?>"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <p>No videos available.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <button id="complete-btn" class="btn btn-success btn-center" disabled
                                                    onclick="update_step(currentStep)">
                                                    <i class="fa-solid fa-check tw-mr-1"></i>
                                                    <?php echo _l('Complete and Continue'); ?>
                                                </button>
                                                <button id="continue-btn" class="btn btn-success btn-center"
                                                    style="display: none;" onclick="update_step(currentStep)">
                                                    <i class="fa-solid fa-arrow-right tw-mr-1"></i>
                                                    <?php echo _l('Continue'); ?>
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
                                                            style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                                            aria-valuemax="100" data-percent="0">0%</div>
                                                    </div>
                                                    <p id="progress-text" class="progress-text">0/6 actions completed
                                                    </p>
                                                    <ul id="progress-list" class="list-group">
                                                        <li class="list-group-item">
                                                            <i class="fa-solid fa-check tw-mr-1"></i>
                                                            <?php echo _l('Watch welcome message'); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="fa-solid fa-check tw-mr-1"></i>
                                                            <?php echo _l('Join Facebook group'); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="fa-solid fa-check tw-mr-1"></i>
                                                            <?php echo _l('Sign up for email alerts'); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="fa-solid fa-check tw-mr-1"></i>
                                                            <?php echo _l('Watch portal walkthrough'); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="fa-solid fa-check tw-mr-1"></i>
                                                            <?php echo _l('Understand the opportunity'); ?>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="fa-solid fa-check tw-mr-1"></i>
                                                            <?php echo _l('Review available products'); ?>
                                                        </li>
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
                    <!-- Font Awesome Close Icon -->
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(site_url('invite'), ['id' => 'invite-friend-form']); ?>

                <!-- Name Input Field -->
                <div class="form-group">
                    <div class="input-container">
                        <div class="input-wrapper">
                            <input type="text" name="name" id="name" class="custom-input" required />
                            <label for="name" class="floating-label">Name</label>
                        </div>
                    </div>
                </div>

                <!-- Email Input Field -->
                <div class="form-group">
                    <div class="input-container">
                        <div class="input-wrapper">
                            <input type="email" name="email" id="email" class="custom-input" required />
                            <label for="email" class="floating-label">Email</label>
                        </div>
                    </div>
                </div>


                <!-- Submit Button -->
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
                <i class="fa-solid fa-envelope" style="color: rgb(255, 203, 3);
    font-size: 80px;"></i>
            </div>
            <div class="modal-body text-center">
                <p class="signup-text">
                    <?php echo _l('<i class="fa fa-exclamation-circle tw-mr-1"></i> We\'ll never send marketing emails through these alerts. These email alerts are strictly to inform you about Ambassador Program latest updates.'); ?>
                </p>
            </div>
            <div class="text-center">
                <button onclick="update_step(currentStep)" type="button" class="signup-btn"
                    data-dismiss="modal"><?php echo _l('Sign Up and Continue'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>

    var currentStep = isNaN(parseInt('<?= $completed_step ?>')) ? 0 : parseInt('<?= $completed_step ?>');
    document.addEventListener('DOMContentLoaded', function () {
        var video = document.getElementById('welcome-video');
        var completeBtn = document.getElementById('complete-btn');
        var continueBtn = document.getElementById('continue-btn');
        var progressBar = document.getElementById('progress-bar');
        var progressText = document.getElementById('progress-text');
        var progressList = document.getElementById('progress-list').querySelectorAll('.list-group-item i');

        // var main = document.getElementsByClassName('step-0')[0];
        // main.classList.remove('step-0');
        // main.classList.add('step-' + currentStep - 1);
        // debugger;

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

        // complete previously completed steps
        if (currentStep >= totalSteps) {
            for (let i = 0; i < totalSteps; i++) {
                progressList[i].classList.remove('tick-inactive');
                progressList[i].classList.remove('tick-completed');
            }
            currentStep = 0;
        } else {
            for (let i = 0; i < currentStep; i++) {
                progressList[i].classList.remove('tick-inactive');
                progressList[i].classList.add('tick-completed');
            }
        }

        var progressPercentage = currentStep * progressPercentagePerStep;

        progressBar.style.width = progressPercentage + '%';
        progressBar.setAttribute('aria-valuenow', progressPercentage);
        progressBar.textContent = Math.round(progressPercentage) + '%';
        // Enable complete button when video ends
        video.addEventListener('ended', function () {
            completeBtn.disabled = false;
        });
        // currentStep++;
        onContinueClick();
        // Handle click for the complete button
        completeBtn.addEventListener('click', function () {
            // if (currentStep === 0 && !video.ended) {
            //     alert("Please watch the video before continuing.");
            //     return;
            // }
            currentStep++;
            onContinueClick();
        });

        function onContinueClick() {
            if (currentStep < totalSteps + 1) {
                let section = document.querySelector('.step-' + currentStep);
                if (section) {
                    section.classList.remove('step-' + currentStep);
                }
                if (section) {
                    section.classList.add('step-' + (currentStep + 1));
                }
                var progressPercentage = currentStep * progressPercentagePerStep;

                progressBar.style.width = progressPercentage + '%';
                progressBar.setAttribute('aria-valuenow', progressPercentage);
                progressBar.textContent = Math.round(progressPercentage) + '%';

                progressText.textContent = (currentStep) + ' / ' + (totalSteps) + ' actions completed';

                // Change tick color for completed actions
                for (let i = 0; i < 6; i++) {
                    if (i < currentStep) {
                        progressList[i].classList.remove('tick-inactive');
                        progressList[i].classList.add('tick-completed');
                    } else {
                        progressList[i].classList.remove('tick-completed');
                        progressList[i].classList.add('tick-inactive');
                    }
                }

                // Display the next section based on the current step
                if (currentStep === 1) { } else if (currentStep === 2) { } else if (currentStep === 3) {
                    completeBtn.style.display = 'none';
                    continueBtn.style.display = 'block';
                }

                // Disable complete button if all steps are completed
                console.log(currentStep, totalSteps);
                if (currentStep === totalSteps + 1) {
                    completeBtn.disabled = true;
                    completeBtn.textContent = 'Completed';

                } else {
                    completeBtn.disabled = true;
                }
            }
        }
        // function restartOnboarding() {
        //     location.reload();
        // }

        // Handle click for the continue button
        continueBtn.addEventListener('click', function () {
            currentStep++;
            onContinueClick();
        });
        // Handle invite friend form submission
        document.getElementById('invite-friend-form').addEventListener('submit', function (event) {
            event.preventDefault();
            fetch('<?php echo site_url('invite'); ?>', {
                method: 'POST',
                body: new FormData(this),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert_float('success', '<?php echo _l('Invitation sent!'); ?>');
                        $('#inviteFriendModal').modal('hide');
                    }
                    else {
                        alert_float('error', '<?php echo _l('Invitation sent!'); ?>');
                    }
                });
        });

        // Track completion of additional steps
        document.getElementById('join-facebook-group').addEventListener('click', function () {
            completeBtn.disabled = false;
        });

        document.getElementById('sign-up-alert').addEventListener('click', function () {
            completeBtn.disabled = false;
        });

        $('#signupAlertModal').on('hide.bs.modal', function () {
            progressList[2].classList.remove('tick-inactive');
            progressList[2].classList.add('tick-completed');
            currentStep++;
            var progressPercentage = currentStep * progressPercentagePerStep;

            progressBar.style.width = progressPercentage + '%';
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.textContent = Math.round(progressPercentage) + '%';

            progressText.textContent = currentStep + '/' + totalSteps + ' actions completed';

            document.getElementById('sign-up-alert-section').style.display = 'none';
            document.getElementById('video-section').style.display = 'none';
            document.getElementById('coming-soon-section').style.display = 'block';
            completeBtn.style.display = 'none';
            continueBtn.style.display = 'block';

        });
    });

    function update_step(step) {
        if (step < 6) {
            $.ajax({
                url: site_url + 'onboarding/update_step',
                type: 'POST',
                data: {
                    'csrf_token_name': '<?php echo $this->security->get_csrf_hash(); ?>',
                    'onboarding_step': ++step
                }
            }).done((x) => { });
        } else {
            window.location.reload();
            document.getElementById('continue-btn').disabled = true;
        }
    }
</script>