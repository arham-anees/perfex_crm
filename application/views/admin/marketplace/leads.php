<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php $is_deal_settings_applied = (bool) get_option('leadevo_deal_settings_status');
$days_to_discount = get_option('leadevo_deal_days_to_discount');
$max_sell_time = get_option('leadevo_deal_max_sell_times');
$discount_type = get_option('leadevo_deal_discount_type');
$discount_value = (int) (get_option('leadevo_deal_discount_amount') ?? 0);
?>
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

        background-color: #f1f5f9;
        color: rgba(0, 0, 0, 0.87);
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
        border-radius: 20px;
        overflow: hidden;
        padding: 16px;
        margin: 10px 0 0 40px;
    }

    .lead-card .prospect-content {
        display: flex;
        justify-content: space-between;
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
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-buttons {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    /* Switch Button */
    .switch-btn {
        display: inline-block;
        position: relative;
        cursor: pointer;
    }

    .switch-btn span {
        display: block;
        width: 42px;
        /* Adjusted width */
        height: 24px;
        /* Adjusted height */
        border-radius: 15px;
        background-color: #ddd;
        transition: background-color 0.3s;
        padding: 0 3px;
        position: relative;
    }

    .switch-btn span::before {
        content: '';
        display: block;
        width: 18px;
        /* Adjusted width */
        height: 18px;
        /* Adjusted height */
        border-radius: 50%;
        background-color: #fff;
        position: absolute;
        left: 3px;
        transition: left 0.3s;
        top: 3px;
    }

    .switch-btn input {
        display: none;
    }

    .switch-btn input:checked~span {
        background-color: #02bcf0;
    }

    .switch-btn input:checked~span::before {
        left: 19px;
        /* Adjusted position for smaller width */
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
        margin: -22px auto;
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
        padding: 10px 15px;
        border: none;
        margin-top: 0;
        border-radius: 5px;
        color: #000;
        cursor: pointer;
        font-weight: 500;
    }

    /* .button-container {

} */

    .buttonn-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        /* Space between buttons */
        justify-content: center;
        /* Center vertically */
        align-items: flex-end;
        /* Align to the right */
        margin-top: -102px;
        /* Add some margin if needed */
    }



    .lead-description.expanded {
        white-space: normal;
    }

    .save_discount_btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.875rem;
        margin: 4px;
    }

    .save_discount_btn {
        background-color: transparent;
        color: black;
    }

    .regular_price_btn {
        border: 1px solid rgba(255, 203, 3, 0.5);
        color: rgb(0, 0, 0);
        background-color: rgb(255, 255, 255);
    }

    .save_discount_btn:hover {
        box-shadow: #0284C7;
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

    .mtop-10 {
        margin-top: 10px;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Filters section -->
                        <form id="filterForm" action="" method="post">
                            <?php $csrf = $this->security->get_csrf_hash(); ?>
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                                value="<?= $csrf; ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="name"><?php echo _l('Name'); ?></label>
                                        <input type="text" id="name" name="name" class="filter-input">
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="filter-group">
                                        <label for="acquisition"><?php echo _l('acquisition_channel'); ?></label>
                                        <select id="acquisition" name="acquisition" class="filter-input">
                                            <option value="">Select Acquisition Channel</option>
                                            <?php foreach ($acquisitions as $acquisition): ?>
                                                <option value="<?php echo $acquisition->id; ?>">
                                                    <?php echo $acquisition->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="acquisition"><?php echo _l('Industries'); ?></label>
                                        <select id="industry_id" name="industry_id" class="filter-input">
                                            <option value="">Select Industries</option>
                                            <?php foreach ($industries as $industry): ?>
                                                <option value="<?php echo $industry['id']; ?>">
                                                    <?php echo $industry['name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="price_range_start"><?php echo _l('Price Range start'); ?></label>
                                        <input type="text" id="price_range_start" name="price_range_start"
                                            class="filter-input">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="price_range_end"><?php echo _l('Price Range end'); ?></label>
                                        <input type="text" id="price_range_end" name="price_range_end"
                                            class="filter-input">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="deal"><?php echo _l('deal'); ?></label>
                                        <select id="deal" name="deal" class="filter-input">
                                            <option value="">Select Deals</option>
                                            <option value="0">Exclusive Deal</option>
                                            <option value="1">Non Exclusive Deal</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="start_date"><?php echo _l('From'); ?></label>
                                        <input type="date" id="start_date" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="end_date"><?php echo _l('To'); ?></label>
                                        <input type="date" id="end_date" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="filter-group">
                                        <label for="quality"><?php echo _l('quality'); ?></label>
                                        <select id="quality" name="quality" class="filter-input">
                                            <option value="">Select verification method</option>
                                            <option value="4">Verified By Staff</option>
                                            <option value="3">Verified By SMS</option>
                                            <option value="2">Verified By WhatsApp</option>
                                            <option value="1">Verified By Coherence</option>
                                        </select>
                                    </div>
                                </div>


                            </div>
                            <div style="height: 40px; display: flex; justify-content: flex-end; gap: 10px;">
                                <input type="button" value="Clear Filters" class="btn btn-warning"
                                    onclick="resetForm();">
                                <input type="submit" value="Apply Filters" class="btn btn-info">
                            </div>
                        </form>
                        <!-- Lead Card Section -->
                        <div class="row">
                            <!-- Existing Card -->
                            <div class="ajaxappedn">
                                <?php foreach ($prospects as $prospect): ?>
                                    <div class="lead-card">
                                        <div class="prospect-content">
                                            <div class="prospect-name">
                                                <h3><?php echo isset($prospect['prospect_name']) ? htmlspecialchars($prospect['prospect_name']) : 'N/A'; ?>
                                                </h3>
                                            </div>
                                            <div class="title-favorite-container">
                                                <hr class="line">
                                                <?php
                                                $is_discounted = false;
                                                if ($is_deal_settings_applied == true) {
                                                    $dateString = $prospect['created_at'];
                                                    $givenDate = new DateTime($dateString);
                                                    $currentDate = new DateTime();
                                                    $interval = $currentDate->diff($givenDate);
                                                    $days = $interval->days;
                                                    if ($days >= $days_to_discount) {
                                                        $is_discounted = true;
                                                    }
                                                }
                                                ?>
                                                <span class="selling-price">
                                                    <i class='fas fa-tag'></i>
                                                    <strong><?php echo _l('leadevo_marketpalce_selling_price'); ?></strong>
                                                    <?php
                                                    $discounted_price = ((float) $prospect['desired_amount']) ?? ((float) $prospect['min_amount']) ?? 0;
                                                    if ($discount_type == 1) {
                                                        $discounted_price = $discounted_price - ($discounted_price * $discount_value) / 100;
                                                    } else {
                                                        $discounted_price = $discounted_price - ($discount_value ?? 0);
                                                    }
                                                    echo $discounted_price;
                                                    if ($is_discounted) {
                                                        echo '<span class="material-symbols-outlined" style="font-size:20px; color:black;">sell</span>';
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="prospect-content">
                                            <div class="lead-card-left">

                                                <ol>
                                                    <li><strong>Created at:</strong>
                                                        <?php echo isset($prospect['created_at']) ? htmlspecialchars(date('Y-m-d', strtotime($prospect['created_at']))) : '-'; ?>
                                                    </li>
                                                    <li><strong>Phone:</strong>
                                                        <?php echo isset($prospect['phone']) ? htmlspecialchars($prospect['phone']) : 'N/A'; ?>
                                                    </li>
                                                    <li><strong>Email:</strong>
                                                        <?php echo isset($prospect['email']) ? htmlspecialchars($prospect['email']) : 'N/A'; ?>
                                                    </li>
                                                    <li><strong>Quality:</strong>
                                                        <ul>
                                                            <?php
                                                            $allQualities = ['Coherence', 'WhatsApp', 'SMS', 'Staff'];
                                                            $verifiedQualities = isset($prospect['quality']) ? explode(' ', trim($prospect['quality'])) : [];

                                                            foreach ($allQualities as $quality) {

                                                                if (in_array($quality, $verifiedQualities)) {
                                                                    echo "<li style='list-style: none; margin-left:35px;'>" . htmlspecialchars($quality) . " <span style='color: green;'>&#10004;</span></li>"; // Green tick for verified qualities
                                                                } else {
                                                                    echo "<li style='list-style: none; margin-left:35px;'>" . htmlspecialchars($quality) . "</li>";
                                                                }
                                                            }
                                                            ?>
                                                        </ul>
                                                    </li>
                                                </ol>


                                            </div>
                                            <div class="lead-card-right">
                                                <div class="details">
                                                    <ol>
                                                        <p><b>Lead Details:</b></p>
                                                        <li><strong>Industry:</strong>
                                                            <?php echo isset($prospect['industry']) ? htmlspecialchars($prospect['industry']) : 'N/A'; ?>
                                                        </li>

                                                        <li><strong>Acquisition Channel:</strong>
                                                            <?php echo isset($prospect['acquisition_channel']) ? htmlspecialchars($prospect['acquisition_channel']) : 'N/A'; ?>
                                                        </li>
                                                        <li><strong>Desired Amount:</strong>
                                                            <?php echo isset($prospect['desired_amount']) ? htmlspecialchars($prospect['desired_amount']) : 'N/A'; ?>
                                                        </li>
                                                        <li><strong>Minimum Amount:</strong>
                                                            <?php echo isset($prospect['min_amount']) ? htmlspecialchars($prospect['min_amount']) : 'N/A'; ?>
                                                        </li>
                                                        <li><strong>Source:</strong>
                                                            <?php echo isset($prospect['source']) ? htmlspecialchars($prospect['source']) : 'N/A'; ?>
                                                        </li>
                                                        <li><strong>Deal:</strong>
                                                            <?php echo isset($prospect['deal']) ? ($prospect['deal'] == 0 ? 'Exclusive' : 'Non-exclusive') : 'N/A'; ?>
                                                            </span>
                                                    </ol>

                                                </div>
                                            </div>
                                            <div>
                                                <div class="auto-deliverable-container text-right float-right"
                                                    style="display:flex;align-items:center">
                                                    <label>Auto Deliverable</label>
                                                    <div class="auto-deliverable-container text-right float-right mleft10">
                                                        <label class="switch-btn">
                                                            <input type="checkbox"
                                                                id="auto-deliverable-checkbox-<?= $prospect['id'] ?>"
                                                                <?= isset($prospect['is_auto_deliverable']) && $prospect['is_auto_deliverable'] == 1 ? 'checked' : '' ?>
                                                                onchange="toggleAutoDeliverable(<?= $prospect['id'] ?>, this.checked ? 1 : 0)">
                                                            <span></span>
                                                        </label>
                                                    </div>

                                                </div>
                                                <div>
                                                    <a href="#" onclick="openSendCampaignModal(<?= $prospect['id'] ?>)">
                                                        Send via Campaign</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="send_via_campaign_modal" class="modal fade" tabindex="-1" role="dialog">
            <?php echo get_instance()->load->view('admin/leadevo/prospects/modals/send_via_campaign.php') ?>
        </div>
    </div>
</div>
</body>
<?php init_tail(); ?>
<script>
    if (Array.isArray(response)) {
        let container = $('.panel-body .ajaxappedn');
        container.empty(); // Clear the current content

        response.forEach(prospect => {
            let prospectHtml = `
                <div class="lead-card">
                    <div class="lead-card-left">
                        <h3>${prospect.prospect_name || 'N/A'}</h3>
                        <ol>
                            <li><strong>Created at:</strong> ${prospect.created_at ? new Date(prospect.created_at).toISOString().split('T')[0] : 'N/A'}</li>
                            <li><strong>Phone:</strong> ${prospect.phone || 'N/A'}</li>
                            <li><strong>Email:</strong> ${prospect.email || 'N/A'}</li>
                              <li><strong>Quality:</strong>
        <ul>
            <?php
            $allQualities = ['Coherence', 'WhatsApp', 'SMS', 'Staff'];

            $verifiedQualities = isset($prospect['quality']) ? explode(' ', trim($prospect['quality'])) : [];

            foreach ($allQualities as $quality) {
                if (in_array($quality, $verifiedQualities)) {
                    echo "<li style='list-style: none; margin-left:35px;'>" . htmlspecialchars($quality) . " <span style='color: green;'>&#10004;</span></li>"; // Green tick for verified qualities
                } else {
                    echo "<li style='list-style: none; margin-left:35px;'>" . htmlspecialchars($quality) . "</li>";
                }
            }
            ?>
        </ul>
    </li>
                        </ol>
                    </div>
                    <div class="lead-card-right">
                        <div class="title-favorite-container">
                            <hr class="line">
                            <span class="selling-price">
                                <i class='fas fa-tag'></i>
                                <strong>Selling Price:</strong> ${calculateDiscountedPrice(prospect)}
                            </span>
                        </div>
                        <div class="details">
                            <ol>
                                <p><b>Lead Details:</b></p>
                                <li><strong>Industry:</strong> ${prospect.industry || 'N/A'}</li>
                                <li><strong>Acquisition Channel:</strong> ${prospect.acquisition_channel || 'N/A'}</li>
                                <li><strong>Desired Amount:</strong> ${prospect.desired_amount || 'N/A'}</li>
                                <li><strong>Source:</strong> <?php echo isset($prospect['source']) ? htmlspecialchars($prospect['source']) : 'N/A'; ?></li>
                                <li><strong>Deal:</strong> <?php echo isset($prospect['deal']) ? ($prospect['deal'] == 0 ? 'Exclusive' : 'Non-exclusive') : 'N/A'; ?>
                            </ol>
                        </div>
                    </div>
                </div>`;
            container.append(prospectHtml); // Append the updated HTML
        });
    } else {
        console.error('Invalid response format');
    }


    function calculateDiscountedPrice(prospect) {
        let discounted_price = parseFloat(prospect.desired_amount) || 0;
        if (prospect.discount_type == 1) {
            discounted_price = discounted_price - (discounted_price * prospect.discount_value) / 100;
        } else {
            discounted_price = discounted_price - (prospect.discount_value || 0);
        }
        return discounted_price;
    }

    function enterFullscreen() {
        var video = document.getElementById('marketplaceVideo');
        if (video.requestFullscreen) {
            video.requestFullscreen();
        } else if (video.mozRequestFullScreen) {
            /* Firefox */
            video.mozRequestFullScreen();
        } else if (video.webkitRequestFullscreen) {
            /* Chrome, Safari and Opera */
            video.webkitRequestFullscreen();
        } else if (video.msRequestFullscreen) {
            /* IE/Edge */
            video.msRequestFullscreen();
        }
    }


    function showMoreDetails(button) {
        var details = button.closest('.details');
        var showMoreContent = details.querySelector('#show-more-content');
        var showLessButton = details.querySelector('#show-less');

        showMoreContent.style.display = 'block'; // Show additional details
        button.style.display = 'none'; // Hide "Show More" button
        showLessButton.style.display = 'inline'; // Show "Show Less" button
    }

    function showLessDetails(button) {
        var details = button.closest('.details');
        var showMoreContent = details.querySelector('#show-more-content');
        var showMoreButton = details.querySelector('#show-more');

        showMoreContent.style.display = 'none'; // Hide additional details
        button.style.display = 'none'; // Hide "Show Less" button
        showMoreButton.style.display = 'inline'; // Show "Show More" button
    }

    // Attach event listeners to buttons
    const buttons = document.querySelectorAll('.mainSwitch button');
    buttons.forEach(button => button.addEventListener('click', () => movePill(button)));

    function resetForm() {
        // Reset form fields
        document.getElementById('filterForm').reset();

        // Reload the page without any filters (remove query parameters)
        window.location.href = window.location.pathname;
    }

    function toggleAutoDeliverable(id, status) {
        // Send AJAX request to update the status
        $.ajax({
            url: '<?= admin_url("marketplace/mark_as_auto_deliverable") ?>',
            type: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function (response) {
                // Update the label dynamically based on the status
                let label = document.querySelector('label[for="auto-deliverable-checkbox-' + id + '"]');
                label.textContent = status === 1 ? 'On' : 'Off';
            },
            error: function (error) {
                console.error('Error updating status', error);
            }
        });
    }
</script>

<script>
    function openSendCampaignModal(id) {
        document.querySelector('#send_via_campaign_modal input[name=id]').value = id;
        $('#send_via_campaign_modal').modal('show');
    }
</script>