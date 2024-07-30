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
    .filters {
        margin-top: 30px;
    }
    .filters h3 {
        margin-bottom: 20px;
    }
    .filter-group {
        margin-bottom: 15px;
    }
    .filter-group label {
        display: block;
        font-weight: bold;
    }
    .filter-group input, .filter-group select {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .prospects-table {
        margin-top: 30px;
        width: 100%;
    }
    .prospects-table th, .prospects-table td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
    }
    .prospects-table th {
        background-color: #f9f9f9;
    }
    .lead-card {
    display: flex;
    background-color: rgb(240, 240, 241);
    color: rgba(0, 0, 0, 0.87);
    transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
    border-radius: 20px;
    overflow: hidden;
    padding: 16px;
    max-height: 246.75px;
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
}
.lead-card img {
    transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    border: 1px solid rgba(0, 0, 0, 0.12);
    border-radius: 20px;
    width: 262px;
    height: 152px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.info-buttons {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.info-button {
    font-size: 0.8125rem;
    align-items: center;
    height: 24px;
    border: 1px solid rgb(189, 189, 189);
    background-color: rgb(255, 255, 255);
    color: rgba(0, 0, 0, 0.87);
    display: inline-flex;
  

    box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
    border-radius: 20px;
  
    padding: 16px;
  


}

.info-button .tooltip {
    display: none;
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 10px;
    border-radius: 4px;
    white-space: nowrap;
    z-index: 1;
    max-width: 200px;
    text-align: center;
    font-size: 12px;
}

.info-button:hover .tooltip {
    display: block;
}

.lead-card-right {
    padding-left: 20px;
    width: 100%;
}

.title-favorite-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lead-card-title {
    font-size: 0.9rem;
    font-weight: 500;
    color: rgba(0, 0, 0, 0.87);
}

.favorite-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: rgb(255, 203, 3);
    font-size: 1.5rem;
}

.lead-card-description {
    font-size: 0.9rem;
    color: rgba(0, 0, 0, 0.87);
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

ol {
    margin-top: 0;
    margin-bottom: 10px;
    list-style: inside !important;
}

.lead-description li {
    margin-bottom: 10px;

}

.lead-details-container {
    display: none;
    margin-top: 20px;
}

.toggle-details-btn {
    margin-top: 10px;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    background-color: rgb(255, 203, 3);
    color: #000;
    cursor: pointer;
    font-weight: 500;
}
.button-container {
    display: flex;
    flex-direction: column;
    gap: 10px; /* Space between buttons */
}

.toggle-details-btn:hover {
    background-color: rgba(255, 203, 3, 0.8);
}

.lead-description.expanded {
    white-space: normal;
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
                                    <button id="fullscreenBtn"><i class="fa fa-play"></i></button>
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

                        <!-- Prospects Table Section -->
                    
                        <!-- Lead Card Section -->
                        <div class="row">
                        <div class="col-md-10">
    <div class="lead-card">
        <div class="lead-card-left">
            <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>" alt="Lead Image">
            <div class="info-buttons">
                <button class="info-button" data-tooltip="More details about the lead">
                    Direct Lead <i class="fa fa-exclamation-circle"></i>
                </button>
            </div>
        </div>
        <div class="lead-card-right">
            <div class="title-favorite-container">
                <p class="lead-card-title">Real Estate Loan request for $60,000 in: St. Joseph County, IN</p>
                <button class="favorite-btn"><i class="fa fa-heart"></i></button>
          
            </div>
            <div class="lead-card-description">
                <p class="lead-card-description" id="leadDescription">
                    <ol>
                        <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                        <li><strong>Relation to property:</strong> Owner (non-real estate investor)</li>
                        <li><strong>Desired asking price:</strong> $60,000</li>
                        <li><strong>Listed with Realtor:</strong> No</li>
                        <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                        <li><strong>Bottom line asking price:</strong> $50,000</li>
                    </ol>
                </p>
                <button class="toggle-details-btn" onclick="toggleDetails()">Show More</button>
            </div>

                <div class="button-container">
        <button class="btn buy_lead_btn"><i class="fa fa-shopping-cart"></i> <?php echo _l('SAVE 20%'); ?><p>InstaClaim price</p></button>
        <button class="btn learn_more_btn"><i class="fa fa-shopping-cart"></i>$345-$563 Buy lead</button>
    </div>
                

        </div>
    </div>
</div>
                            <div class="col-md-2">
                                <div class="video-card">
                                <div class="video-container">
                                <video  id="marketplaceVideo" class="lead_marketplace_video" src="<?php echo base_url('/modules/leadevo/assets/videos/request_a_custom_lead_generation_1.mp4'); ?>"></video>
                                <div class="video-overlay"></div>
                                <button id="fullscreenBtn" class="fullscreenBtn"><i class="fa fa-play"></i></button>
                                </div>
                                <button class="btn buy_lead_btn"><?php echo _l('Learn More'); ?></button>
                                </div>
                            </div>
                        </div>

</div>
<?php init_tail(); ?>
<script>
    document.getElementById('fullscreenBtn').addEventListener('click', function() {
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
    });
    function toggleDetails() {
        var description = document.getElementById("leadDescription");
        var button = document.querySelector(".toggle-details-btn");

        if (description.style.maxHeight) {
            description.style.maxHeight = null;
            button.textContent = "Show More";
        } else {
            description.style.maxHeight = "150px";
            button.textContent = "Show Less";
        }
    }

</script>
