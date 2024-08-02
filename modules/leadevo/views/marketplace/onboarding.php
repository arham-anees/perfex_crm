<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

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
.facebook-group , .sign-up-alert{
    border-bottom: 1px solid rgb(255, 203, 3);
    color: rgb(129, 133, 146);
    cursor: pointer;

}
.facebook-group:hover , .sign-up-alert:hover {
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
    padding : 8px;
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

<div id="wrapper">
    <div class="content">
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
                                        <div id="video-section">
                                            <video id="welcome-video" width="100%" controls>
                                                <source src="<?php echo base_url('/modules/leadevo/assets/videos/onboarding_welcome.mp4'); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            
                                        </div>
                                    

                                        <!-- Text and button for joining Facebook group -->
                                        <div id="facebook-group-section" style="display: none;">
                                            <div class="card mt-4">
                                                <div class="card-body">
                                               
        <p><a id="join-facebook-group" class="facebook-group" ><?php echo _l('Join our private Facebook group'); ?></a> <?php echo _l('group dedicated specifically for ambassadors. In the group, you\'ll get access to other successful ambassadors, learn the latest marketing strategies, get help from our support team, and stay informed on all the latest product updates.'); ?></p>
             
        </a>
    </p>
 
    </div>
    </div>
                                        </div>

                                        <!-- Text and button for signing up for alerts -->
                                        <div id="sign-up-alert-section" style="display: none;">
                                            <div class="card mt-4">
                                                <div class="card-body">
                                                    <p> <a id="sign-up-alert" class="sign-up-alert"  data-toggle="modal" data-target="#signupAlertModal"  ><?php echo _l('Sign Up for Alerts'); ?></a>
                                                    <?php echo _l("Sign up for our email alerts to stay informed about the latest updates, opportunities, and important information."); ?></p>
                                                </div>
                                            </div>
                                        
                                        </div>

                                        <!-- Coming Soon text -->
                                        <div id="coming-soon-section" style="display: none; ">
                                            <div class="card mt-4" style="height: 400px;">
                                                <div class="card-body d-flex flex-column justify-content-center " style="height: 100%;align-items:center; justify-content:center ">
                                                    <p style="font-size: 40px;"><?php echo _l("Video Coming Soon..."); ?></p>
                                           
                                                </div>
                                            </div>
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
                                            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
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
                        <!-- End of row with video and progress card -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for sign up alert -->
<div id="signupAlertModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="signupAlertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style ="padding: 20px;">
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
                <button type="button" class="signup-btn" data-dismiss="modal"><?php echo _l('Sign Up and Continue'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var video = document.getElementById('welcome-video');
    var completeBtn = document.getElementById('complete-btn');
    var continueBtn = document.getElementById('continue-btn');
    var progressBar = document.getElementById('progress-bar');
    var progressText = document.getElementById('progress-text');
    var progressList = document.getElementById('progress-list').querySelectorAll('.list-group-item i');

    var currentStep = 0;
    var totalSteps = progressList.length;
    var progressPercentagePerStep = 100 / totalSteps;

    // Initialize tick styles
    for (let i = 0; i < totalSteps; i++) {
        progressList[i].classList.add('tick-inactive');
    }

    // Enable complete button when video ends
    video.addEventListener('ended', function() {
        completeBtn.disabled = false;
    });

    // Handle click for the complete button
    completeBtn.addEventListener('click', function() {
        // if (currentStep === 0 && !video.ended) {
        //     alert("Please watch the video before continuing.");
        //     return;
        // }

        if (currentStep < totalSteps) {
            currentStep++;
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
            if (currentStep === 1) {
                document.getElementById('facebook-group-section').style.display = 'block';
            } else if (currentStep === 2) {
                document.getElementById('facebook-group-section').style.display = 'none';
                document.getElementById('sign-up-alert-section').style.display = 'block';
            } else if (currentStep === 3) {
                document.getElementById('sign-up-alert-section').style.display = 'none';
                document.getElementById('video-section').style.display = 'none';
                document.getElementById('coming-soon-section').style.display = 'block';

                completeBtn.style.display = 'none';
                continueBtn.style.display = 'block';
            }

            // Disable complete button if all steps are completed
            if (currentStep === totalSteps) {
                completeBtn.disabled = true;
                completeBtn.textContent = 'Completed';
            } else {
                completeBtn.disabled = true;
            }
        }
    });

    // Handle click for the continue button
    continueBtn.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
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
            if (currentStep === 1) {
                document.getElementById('facebook-group-section').style.display = 'block';
            } else if (currentStep === 2) {
                document.getElementById('facebook-group-section').style.display = 'none';
                document.getElementById('sign-up-alert-section').style.display = 'block';
            } else if (currentStep === 3) {
                document.getElementById('sign-up-alert-section').style.display = 'none';
                document.getElementById('video-section').style.display = 'none';
                document.getElementById('coming-soon-section').style.display = 'block';
                completeBtn.style.display = 'none';
                continueBtn.style.display = 'none';
            }
        }
    });

    // Track completion of additional steps
    document.getElementById('join-facebook-group').addEventListener('click', function() {
        completeBtn.disabled = false;
    });

    document.getElementById('sign-up-alert').addEventListener('click', function() {
        completeBtn.disabled = false;
    });

    $('#signupAlertModal').on('hide.bs.modal', function() {
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

</script>

<?php init_tail(); ?>