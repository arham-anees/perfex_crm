<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
    .card {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-family: Rubik, sans-serif;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 500;
        margin-bottom: 15px;
    }

    .card-text {
        font-size: 1rem;
        margin-bottom: 20px;
    }

    .btn-center {
        display: block;
        width: auto;
        max-width: 200px;
        margin: 20px auto;
    }

    .list-group-item {
        border: none;
        padding: 10px 0;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    .list-group-item i {
        border-radius: 50%;
        padding: 5px;
        margin-right: 10px;
        transition: background-color 0.3s ease;
    }

    .tick-completed {
        color: #ffffff;
        background-color: rgb(255, 203, 3);
    }

    .tick-inactive {
        color: #ffffff;
        background-color: #e0e0e0;
    }

    .progress {
        height: 20px;
        margin-bottom: 15px;
    }

    .progress-bar {
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 20px;
        color: #ffffff;
        width: 100%;
        transition: transform 0.4s linear 0s;
        transform-origin: left center;
        background-color: rgb(255, 203, 3);
    }

    .progress-bar[data-percent="0.00"],
    .progress-bar[data-percent="0"] {
        margin-left: 0 !important;
    }

    .progress-text {
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        color: rgba(0, 0, 0, 0.6);
    }

    .facebook-group,
    .sign-up-alert {
        border-bottom: 1px solid rgb(255, 203, 3);
        color: rgb(129, 133, 146);
        cursor: pointer;

    }

    .facebook-group:hover,
    .sign-up-alert:hover {
        background-color: rgb(255, 203, 3);
    }

    .signup-title {
        color: rgba(0, 0, 0, 0.87);
        font-family: Rubik;
        font-size: 1.5rem;
        text-align: center;
        font-weight: 500;
    }

    .signup-text {
        color: rgb(1, 67, 97);
        background-color: rgb(229, 246, 253);
        padding: 8px;
        border-radius: 4px;
    }

    .signup-btn {
        background-color: rgb(255, 203, 3);
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 4px;
        border: 0px;
        cursor: pointer;
        font-size: 15px;
    }

    /* General Styles for Form Group */
    .form-group {
        margin-bottom: 1.5rem;
    }

    /* Styles for Input Container */
    .input-container {
        position: relative;
    }

    /* Input Wrapper Styles */
    .input-wrapper {
        position: relative;
    }

    /* Label Styles */
    .floating-label {
        position: absolute;
        top: 50%;
        left: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        transform: translateY(-50%);
        pointer-events: none;
        background-color: #fff;
        padding: 0 0.25rem;
    }

    /* Input Field Styles */
    .custom-input {
        padding: 1rem 0.75rem;
        border: 1px solid #ccc;
        border-radius: 0.25rem;
        font-size: 1rem;
        width: 75%;
        box-sizing: border-box;
        background-color: #fff;
        transition: border-color 0.3s ease;
    }

    /* Focus State Styles */
    .custom-input:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }

    /* Floating Label Active Styles */
    .custom-input:focus+.floating-label,
    .custom-input:not(:placeholder-shown)+.floating-label {
        top: 0;
        left: 5rem;
        font-size: 0.75rem;
        color: #007bff;
        transform: translateY(-50%);
    }

    /* Placeholder Text Adjustment */
    .custom-input::placeholder {
        color: transparent;
        /* Hide placeholder text */
    }

    .step-1-content,
    .step-2-content,
    .step-3-content,
    .step-4-content,
    .step-5-content,
    .step-6-content {
        display: none;
    }

    .step-1 .step-1-content {
        display: block;
    }

    .step-2 .step-2-content {
        display: block;
    }

    .step-3 .step-3-content {
        display: block;
    }

    .step-4 .step-4-content {
        display: block;
    }

    .step-5 .step-5-content {
        display: block;
    }

    .step-6 .step-6-content {
        display: block;
    }

    .step-2 {}

    .step-3 {}

    .step-4 {}

    .step-5 {}

    .step-6 {}



    /* Responsive Design for smaller screens */
    @media (max-width: 768px) {

        .col-md-8,
        .col-md-4 {
            width: 100%;
        }

        .card {
            padding: 15px;
        }

        .btn-center {
            width: 100%;
            max-width: none;
        }
    }
</style>
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
                                                <div class="panel-body step-0">
                                                    <div class="videos step-1-content">
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
                                                    <div class="videos step-4-content">
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
                                                    <div class="videos step-5-content">
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
                <?php echo form_open(admin_url('leadevo/invite'), ['id' => 'invite-friend-form']); ?>

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
    var currentStep = isNaN('<?= $completed_step ?>') ? 0 : '<?= $completed_step ?>';
    currentStep = parseInt(currentStep);
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
        }
        // complete previously completed steps
        for (let i = 0; i < currentStep; i++) {
            progressList[i].classList.remove('tick-inactive');
            progressList[i].classList.add('tick-completed');
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

            if (currentStep < totalSteps) {
                var main = document.getElementsByClassName('step-0')[0];
                if (main) {
                    main.classList.remove('step-0');
                } else {

                    main = document.getElementsByClassName('step-' + (currentStep))[0];
                    main.classList.remove('step-' + (currentStep));
                }
                main.classList.add('step-' + (currentStep + 1));
                var progressPercentage = currentStep * progressPercentagePerStep;

                progressBar.style.width = progressPercentage + '%';
                progressBar.setAttribute('aria-valuenow', progressPercentage);
                progressBar.textContent = Math.round(progressPercentage) + '%';

                progressText.textContent = currentStep + '/' + totalSteps + ' actions completed';

                // Change tick color for completed actions
                for (let i = 0; i < currentStep; i++) {
                    progressList[i].classList.remove('tick-inactive');
                    progressList[i].classList.add('tick-completed');
                }

                // Display the next section based on the current step
                if (currentStep === 1) { } else if (currentStep === 2) { } else if (currentStep === 3) {
                    completeBtn.style.display = 'none';
                    continueBtn.style.display = 'block';
                }

                // Disable complete button if all steps are completed
                console.log(currentStep, totalSteps);
                if (currentStep === totalSteps) {
                    completeBtn.disabled = true;
                    completeBtn.textContent = 'Completed';
                } else {
                    completeBtn.disabled = true;
                }
            }
        }

        // Handle click for the continue button
        continueBtn.addEventListener('click', function () {
            currentStep++;
            onContinueClick();
        });

        // Handle invite friend form submission
        document.getElementById('invite-friend-form').addEventListener('submit', function (event) {
            event.preventDefault();
            fetch('<?php echo site_url('clients/invite'); ?>', {
                method: 'POST',
                body: new FormData(this),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('<?php echo _l('Invitation sent!'); ?>');
                        $('#inviteFriendModal').modal('hide');
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
        $.ajax({
            url: site_url + 'onboarding/update_step',
            type: 'POST',
            data: {
                'onboarding_step': ++step
            }
        }).done((x) => { })
    }
</script>