<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

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

    .buy_lead_btn,
    .learn_more_btn {
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

    .learn_more_btn:hover,
    .buy_lead_btn:hover {
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

    .filters {
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

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
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
        margin: 10px 0;
    }

    .fullscreenBtn {
        padding: 5px 10px !important;
        font-size: 1.2rem !important;
    }

    .sticky {

        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        align-self: start;
        position: sticky;
        top: 160px;
    }

    .sticky-container {
        display: flex;
        flex-direction: column;
        align-items: flex-end;


    }

    .image-container {
        margin-top: 20px;
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

    .verified {
        color: green;
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .details {
        margin: 0 auto;
        padding: 10px;
    }

    #show-more,
    #show-less {
        cursor: pointer;
        text-transform: uppercase;
        color: grey;
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
        color: #000;
        cursor: pointer;
        font-weight: 500;
    }

    /* .button-container {
    position: absolute;
    top: 52%;
    left: 72%;
    } */

    .button-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        /* Space between buttons */
        justify-content: center;
        /* Center vertically */
        align-items: flex-end;
        /* Align to the right */
        margin-top: -132px;
        /* Add some margin if needed */
    }



    .lead-description.expanded {
        white-space: normal;
    }

    .save_discount_btn,
    .regular_price_btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 2px 16px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.875rem;
        margin: 4px;
    }

    .save_discount_btn {
        background-color: rgb(255, 203, 3);
        color: rgb(0, 0, 0);
    }

    .regular_price_btn {
        border: 1px solid rgba(255, 203, 3, 0.5);
        color: rgb(0, 0, 0);
        background-color: rgb(255, 255, 255);
    }

    .save_discount_btn:hover,
    .regular_price_btn:hover {
        box-shadow: rgba(255, 203, 3, 0.5) 0px 5px 20px;
    }

    .button-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .text-container {
        display: flex;
        flex-direction: column;
        margin-left: 10px;
    }

    .bold-text {
        font-weight: bold;
    }

    .small-text {
        font-size: 0.75rem;
        margin-top: 2px;
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
                                <p class="lead-title">
                                    <?php echo _l("Meet Industry's Most Advanced Real Estate Lead Marketplace"); ?></p>
                                <p class="lead-description">Buy and sell real-time, high-intent, geo-targeted real
                                    estate leads through the industry's largest and most advanced lead marketplace in
                                    the US!</p>
                                <button class="btn buy_lead_btn"><?php echo _l('Buy Lead'); ?></button>
                                <button class="btn learn_more_btn"><?php echo _l('Learn More'); ?></button>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="video-container">
                                    <video id="marketplaceVideo" class="lead_marketplace_video"
                                        src="<?php echo base_url('/modules/leadevo/assets/videos/reb_dashboard_lead_marketplace.mp4'); ?>"></video>
                                    <div class="video-overlay"></div>
                                    <button id="fullscreenBtn" onClick="enterFullscreen()"><i
                                            class="fa fa-play"></i></button>
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


                        <!-- Lead Card Section -->
                        <div class="row">
                            <!-- Existing Card -->
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified <i class="fa fa-check-circle"> </i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>

                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Duplicate Card -->


                            <div class="col-md-2 sticky">
                                <div class="sticky-container">
                                    <div class="video-card">
                                        <div class="video-container">
                                            <video id="marketplaceVideo" class="lead_marketplace_video"
                                                src="<?php echo base_url('/modules/leadevo/assets/videos/request_a_custom_lead_generation_1.mp4'); ?>"></video>
                                            <div class="video-overlay"></div>
                                            <button id="fullscreenBtn" onClick="enterFullscreen()"><i
                                                    class="fa fa-play"></i></button>
                                        </div>
                                        <button class="btn buy_lead_btn"><?php echo _l('Learn More'); ?></button>
                                    </div>

                                    <div class="image-container">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/Chatbot.png'); ?>"
                                            alt="Sticky Image" class="sticky-image">
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified <i class="fa fa-check-circle"> </i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>

                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified <i class="fa fa-check-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>

                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified <i class="fa fa-check-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>

                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified <i class="fa fa-check-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>

                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified<i class="fa fa-check-circle"> </i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>

                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified <i class="fa fa-check-circle"> </i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>

                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="lead-card">
                                    <div class="lead-card-left">
                                        <img src="<?php echo base_url('/modules/leadevo/assets/images/property_tax_consultant.jpg'); ?>"
                                            alt="Lead Image">
                                        <div class="info-buttons">
                                            <button type="button" class="info-button" data-toggle="tooltip"
                                                data-placement="bottom"
                                                title="Direct Lead is an inbound lead that has been generated as a result of a user filling out a web form explicitly expressing interest in this service.">
                                                Direct Lead <i class="fa fa-exclamation-circle"></i>
                                            </button>
                                            <span class="verified" data-toggle="tooltip" data-placement="bottom"
                                                title="The phone number associated with this lead has been verified.">
                                                Phone Verified <i class="fa fa-check-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="lead-card-right">
                                        <div class="title-favorite-container">
                                            <p class="lead-card-title">Real Estate Loan request for $60,000 in: St.
                                                Joseph County, IN</p>
                                            <button class="favorite-btn"><i class="fa fa-heart"></i></button>
                                        </div>
                                        <div class="details">
                                            <ol>
                                                <li><strong>Lead submitted:</strong> 07/28/2024 02:35pm</li>
                                                <li><strong>Relation to property:</strong> Owner (non-real estate
                                                    investor)</li>
                                                <li><strong>Desired asking price:</strong> $60,000</li>
                                                <li><strong>Listed with Realtor:</strong> No</li>
                                                <li><strong>Sales agent preference:</strong> None</li>
                                                <li><strong>Loan preference:</strong> None</li>
                                                <button id="show-more" class="toggle-details-btn"
                                                    onClick="showMoreDetails(this)">Show More</button>
                                                <span id="show-more-content" style="display: none;">
                                                    <li><strong>Sale urgency:</strong> Within 1-3 months</li>
                                                    <li><strong>Bottom line asking price:</strong> $50,000</li>
                                                    <li><strong>Current loan balance:</strong> $40,000</li>
                                                    <li><strong>Loan purpose:</strong> Purchase</li>
                                                    <li><strong>Loan amount:</strong> $60,000</li>
                                                    <li><strong>Credit score:</strong> 750</li>
                                                </span>
                                                <small><strong><span id="show-less" style="display: none;"
                                                            onClick="showLessDetails(this)">Show
                                                            less</span></strong></small>
                                            </ol>
                                            <div class="button-container">
                                                <button class="btn save_discount_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">SAVE 20%</span>
                                                            <span class="small-text">InstaClaim price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                                <button class="btn regular_price_btn">
                                                    <div class="button-content">
                                                        <i class="fa fa-shopping-cart"></i>
                                                        <div class="text-container">
                                                            <span class="bold-text">$345-$563 Buy lead</span>
                                                            <span class="small-text">regular price</span>
                                                        </div>
                                                    </div>
                                                </button>
                                            </div>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

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


                function showMoreDetails(button) {
                    var details = button.closest('.details');
                    var showMoreContent = details.querySelector('#show-more-content');
                    var showLessButton = details.querySelector('#show-less');

                    showMoreContent.style.display = 'block';  // Show additional details
                    button.style.display = 'none';  // Hide "Show More" button
                    showLessButton.style.display = 'inline';  // Show "Show Less" button
                }

                function showLessDetails(button) {
                    var details = button.closest('.details');
                    var showMoreContent = details.querySelector('#show-more-content');
                    var showMoreButton = details.querySelector('#show-more');

                    showMoreContent.style.display = 'none';  // Hide additional details
                    button.style.display = 'none';  // Hide "Show Less" button
                    showMoreButton.style.display = 'inline';  // Show "Show More" button
                }


            </script>