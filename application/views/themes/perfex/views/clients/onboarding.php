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

<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <div class="_buttons">
                    <h4 class="pull-left mleft10"><?php echo _l('Onboarding'); ?></h4>
                    <a href="#" class="btn btn-success pull-right display-block mleft10">
                        <i class="fa-solid fa-user-plus tw-mr-1"></i>
                        <?php echo _l('Invite Friend'); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>
                <hr class="hr-panel-heading" />

                <!-- Create a row with 2 columns for video and progress card -->
                <div class="row">
                    <!-- Left Side: Video and Facebook Group Text -->
                    <div class="col-md-8">
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="videos">
                                    <?php if (!empty($videos)): ?>
                                        <?php foreach ($videos as $video): ?>
                                            <div id="video-section">
                                                <video id="welcome-video" width="100%" controls>
                                                    <source src="<?php echo htmlspecialchars($video['url']); ?>"
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
                                <div id="facebook-group-section" style="display: none;">
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
                                <div id="sign-up-alert-section" style="display: none;">
                                    <div class="card mt-4">
                                        <div class="card-body">
                                            <p> <a id="sign-up-alert" class="sign-up-alert" data-toggle="modal"
                                                    data-target="#signupAlertModal"><?php echo _l('Sign Up for Alerts'); ?></a>
                                                <?php echo _l("Sign up for our email alerts to stay informed about the latest updates, opportunities, and important information."); ?>
                                            </p>
                                        </div>
                                    </div>

                                </div>

                                <!-- Coming Soon text -->
                                <div class="videos">
                                    <?php if (!empty($videos)): ?>
                                        <?php foreach ($videos as $video): ?>
                                            <div>
                                                <video id="coming-soon-section" style="display: none; width: 100%;" width="100%"
                                                    controls>
                                                    <source src="<?php echo htmlspecialchars($video['url']); ?>"
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
                            <button id="complete-btn" class="btn btn-success btn-center" disabled>
                                <i class="fa-solid fa-check tw-mr-1"></i>
                                <?php echo _l('Complete and Continue'); ?>
                            </button>
                            <button id="continue-btn" class="btn btn-success btn-center" style="display: none;">
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
                                    <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" data-percent="0">0%
                                    </div>
                                </div>
                                <p id="progress-text" class="progress-text">0/6 actions completed</p>
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

                <!-- Modal for Sign Up Alert -->
                <div id="signupAlertModal" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="signupAlertModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 id="signupAlertModalLabel" class="modal-title">Sign Up for Alerts</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p class="signup-title"><?php echo _l('Complete your profile'); ?></p>
                                <p class="signup-text">
                                    <?php echo _l('Sign up for our email alerts to stay informed about the latest updates, opportunities, and important information.'); ?>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                    class="btn btn-primary signup-btn"><?php echo _l('Sign Up Now'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // JavaScript code to handle video watching and progress
                    document.addEventListener("DOMContentLoaded", function () {
                        const welcomeVideo = document.getElementById("welcome-video");
                        const completeBtn = document.getElementById("complete-btn");
                        const continueBtn = document.getElementById("continue-btn");
                        const progressBar = document.getElementById("progress-bar");
                        const progressText = document.getElementById("progress-text");
                        const progressList = document.getElementById("progress-list").children;

                        let progress = 0;

                        // When video ends, enable the complete button
                        welcomeVideo.addEventListener("ended", function () {
                            completeBtn.disabled = false;
                        });

                        // Handle complete button click
                        completeBtn.addEventListener("click", function () {
                            // Increase progress
                            progress += 17;
                            updateProgress();

                            // Show Facebook group section
                            document.getElementById("facebook-group-section").style.display = "block";

                            // Hide complete button, show continue button
                            completeBtn.style.display = "none";
                            continueBtn.style.display = "block";

                            // Disable continue button initially
                            continueBtn.disabled = true;

                            // Handle joining Facebook group
                            document.getElementById("join-facebook-group").addEventListener("click", function () {
                                progress += 17;
                                updateProgress();

                                document.getElementById("sign-up-alert-section").style.display = "block";
                                continueBtn.disabled = false;
                            });
                        });

                        // Handle continue button click
                        continueBtn.addEventListener("click", function () {
                            progress += 17;
                            updateProgress();

                            document.getElementById("sign-up-alert").addEventListener("click", function () {
                                progress += 17;
                                updateProgress();
                            });
                        });

                        function updateProgress() {
                            progressBar.style.width = progress + "%";
                            progressBar.innerHTML = progress + "%";
                            progressText.innerHTML = Math.round(progress / 17) + "/6 actions completed";

                            // Update progress list
                            for (let i = 0; i < Math.round(progress / 17); i++) {
                                progressList[i].firstElementChild.classList.add("tick-completed");
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<script>
    function update_step(step) {
        $.ajax({
            url: site_url + 'onboarding/update_step',
            type: 'POST',
            data: { 'onboarding_step': step }
        }).done((x) => { })
    }
</script>