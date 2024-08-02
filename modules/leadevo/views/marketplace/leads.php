<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .lead-title {
        font-size: 2rem;
        font-weight: 500;
        color: rgba(0, 0, 0, 0.87);
    }
    .lead-description {
        font-size: 1rem;
        color: rgba(0, 0, 0, 0.87);
        margin-top: 24px;
    }

    .lead_marketplace_video {
        border-radius: 20px;
    }
    .buttons {
        margin-top: 20px;
    }
    .buy_lead_btn, .learn_more_btn {
        cursor: pointer;
        font-weight: 500;
        font-size: 0.875rem;
        text-transform: uppercase;
        padding: 6px 16px;
        min-width: 40px;
        border-radius: 20px;
        margin-top: 10px;
    }
    .buy_lead_btn {
        background-color: rgb(255, 203, 3);
        color: rgb(0, 0, 0);
    }
    .learn_more_btn {
        border: 1px solid rgba(255, 203, 3, 0.5);
        color: rgb(0, 0, 0);
        background-color: rgb(255, 255, 255);
    }
    .learn_more_btn:hover, .buy_lead_btn:hover {
        box-shadow: rgba(255, 203, 3, 0.5) 0px 5px 20px;
    }
    .video-card {
    background-color: rgb(240, 240, 241);
    color: rgba(0, 0, 0, 0.87);
    box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
    border-radius: 20px;
    padding: 16px;
    display: flex;
    flex-direction: column;
}

    .video-container {
        position: relative;
    }
    .video-container video {
        width: 100%;
        height: auto;
        border-radius: 20px;
    }
    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(128, 128, 128, 0.5);
        border-radius: 20px;
    }
    .video-container button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 10px 20px;
        border: none;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        cursor: pointer;
        font-size: 2rem;
    }
    .video-container button:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }
    .filters  {
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

    .filter-group input, .filter-group select {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }


.fullscreenBtn {
        padding: 5px 10px !important;
        font-size: 1.2rem !important;
}



.video-card {
    background-color: rgb(240, 240, 241);
    color: rgba(0, 0, 0, 0.87);
    transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
    border-radius: 20px;
    padding: 6px;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
    align-self: start;
    position: sticky;
    top: 160px;
}




</style>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Marketplace Results Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <p class="lead-title"><?php echo _l("Meet Industry's Most Advanced Real Estate Lead Marketplace"); ?></p>
                                <p class="lead-description">Buy and sell real-time, high-intent, geo-targeted real estate leads through the industry's largest and most advanced lead marketplace in the US!</p>
                                <button class="btn buy_lead_btn"><?php echo _l('Buy Lead'); ?></button>
                                <button class="btn learn_more_btn"><?php echo _l('Learn More'); ?></button>
                            </div>
                            <div class="col-md-6 text-right">
    <div class="video-container">
        <video id="marketplaceVideo" class="lead_marketplace_video" src="<?php echo base_url('/modules/leadevo/assets/videos/reb_dashboard_lead_marketplace.mp4'); ?>"></video>
        <div class="video-overlay"></div>
        <button id="fullscreenBtn" onClick="enterFullscreen()"><i class="fa fa-play"></i></button>
    </div>
</div>
</div>

                        <!-- Filters Section -->
                        <div class="filters">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="industry"><?php echo _l('Industry'); ?></label>
                                        <select id="industry" name="industry" class="filter-input">
                                            <option value="" disabled selected></option>
                                            <option value="real_estate">Real Estate</option>
                                            <option value="mortgage">Mortgage</option>
                                            <option value="insurance">Insurance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="price_range"><?php echo _l('Price Range'); ?></label>
                                        <input type="text" id="price_range" name="price_range" class="filter-input">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="location"><?php echo _l('Location'); ?></label>
                                        <input type="text" id="location" name="location" class="filter-input">
                                    </div>
                                </div>
                            </div>
</div>
                        <!-- Prospects Table Section -->

                    
                        <div class="table-responsive">
    <table class="table table-bordered dt-table nowrap" style="width:100%">
        <thead>
            <tr>
                <th><?php echo _l('Metadata'); ?></th>
                <th><?php echo _l('Lead'); ?></th>
                <th><?php echo _l('Contact'); ?></th>
                <th><?php echo _l('Lead Type'); ?></th>
                <th><?php echo _l('Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prospects as $prospect) : ?>
                <tr>
                    <td>
                        <div>
                            <strong><?php echo _l('Prospect ID'); ?>:</strong> <?php echo $prospect->id; ?><br>
                            <strong><?php echo _l('Generated date'); ?>:</strong> <?php echo date('d/m/y \a\t H:i', strtotime($prospect->created_at)); ?><br>
                            <strong><?php echo _l('Industry'); ?>:</strong> 
                            <?php
                            $industry_name = 'Unknown';
                            foreach ($industries as $industry) {
                                if ($industry['id'] == $prospect->industry_id) {
                                    $industry_name = $industry['name'];
                                    break;
                                }
                            }
                            echo $industry_name;
                            ?>

                        </div>
                    </td>
                    <td>
                        <div>
                        <strong>Full name:</strong> <?php echo htmlspecialchars($prospect->first_name) . ' ' . htmlspecialchars(substr($prospect->last_name, 0, 1)) . '######'; ?><br>
                            <strong><?php echo _l('Zip code'); ?>:</strong> <?php echo 'N/A';  ?>
                        </div>
                    </td>
                    <td>
                        
                            <strong>Phone:</strong> <?php echo htmlspecialchars('+33 ####### ' . substr($prospect->phone, -2)); ?><br>
                            <strong>Email:</strong> <?php echo htmlspecialchars('m######' . substr($prospect->email, strpos($prospect->email, '@'))); ?>
                        </td>
                    <td>
                        <div>
                            <strong><?php echo _l('Source'); ?>:</strong> <?php echo 'N/A';  ?><br>
                            <strong><?php echo _l('Deal'); ?>:</strong> <?php echo 'N/A';  ?><br>
                            <strong><?php echo _l('Quality'); ?>:</strong> <?php echo 'N/A';  ?>
                        </div>
                    </td>
                    <td class="text-center">
                 
                        <button class="btn btn-primary">Add to cart</button>
                        <input type="checkbox" id="select<?php echo $prospect->id; ?>" />
                        <label for="select<?php echo $prospect->id; ?>">Select</label>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


</div>
                        </div>

</div>
<?php init_tail(); ?>
<script>
function enterFullscreen() {
    var video = document.getElementById('marketplaceVideo');
    if (video.requestFullscreen) {
        video.requestFullscreen();
    } else if (video.mozRequestFullScreen) { /* Firefox */
        video.mozRequestFullScreen();
    } else if (video.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
        video.webkitRequestFullscreen();
    } else if (video.msRequestFullscreen) { /* IE/Edge */
        video.msRequestFullscreen();
    }
}



</script>
