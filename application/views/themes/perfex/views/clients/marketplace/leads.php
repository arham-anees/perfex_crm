<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $is_deal_settings_applied = (bool) get_option('leadevo_deal_settings_status');
$days_to_discount = get_option('leadevo_deal_days_to_discount');
$max_sell_time = get_option('leadevo_deal_max_sell_times');
$discount_type = get_option('leadevo_deal_discount_type');
$discount_value = get_option('leadevo_deal_discount_amount');
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
<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <!-- Marketplace Results Section -->
                <!-- <div class="row">
                    <div class="col-md-6">
                        <p class="lead-title">
                            <?php echo _l("Meet Industry's Most Advanced Real Estate Lead Marketplace"); ?>
                        </p>
                        <p class="lead-description">Buy and sell real-time, high-intent, geo-targeted real estate leads
                            through the industry's largest and most advanced lead marketplace in the US!</p>
                        <button class="btn buy_lead_btn"><?php echo _l('Buy Lead'); ?></button>
                        <button class="btn learn_more_btn"><?php echo _l('Learn More'); ?></button>
                    </div>
                </div> -->
                <form id="filterForm" action="marketplace/index" method="post">
                    <?php $csrf = $this->security->get_csrf_hash(); ?>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="zip"><?php echo _l('Zip Codes'); ?></label>
                                <input type="number" id="zip_code" name="zip" class="filter-input">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="acquisition"><?php echo _l('acquisition_channel'); ?></label>
                                <select id="acquisition" name="acquisition" class="filter-input">
                                    <?php foreach ($acquisitions as $acquisition): ?>
                                        <option value="<?php echo $acquisition->id; ?>"><?php echo $acquisition->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="price_range_start"><?php echo _l('Price Range start'); ?></label>
                                <input type="text" id="price_range_start" name="price_range_start" class="filter-input">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="price_range_end"><?php echo _l('Price Range end'); ?></label>
                                <input type="text" id="price_range_end" name="price_range_end" class="filter-input">
                            </div>
                        </div>
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
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="deal"><?php echo _l('deal'); ?></label>
                                <select id="deal" name="deal" class="filter-input">
                                    <option value="" disabled selected>Select Deals</option>
                                    <option value="0">Exclusive Deal</option>
                                    <option value="1">Non Exclusive Deal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="quality"><?php echo _l('quality'); ?></label>
                                <select id="quality" name="quality" class="filter-input">
                                    <option value="" disabled selected>Select verification method</option>
                                    <option value="4">Verified By Staff</option>
                                    <option value="3">Verified By SMS</option>
                                    <option value="2">Verified By WhatsApp</option>
                                    <option value="1">Verified By Coherence</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <!-- <button class="btn regular_price_btn">
                                <div class="button-content">
                                    <i class="fa fa-shopping-cart"></i>
                                    <div class="text-container">
                                        <span class="bold-text">$345-$563 Buy lead</span>
                                        <span class="small-text">regular price</span>
                                    </div>
                                </div>
                            </button> -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                        </div>

                    </div>
                    <div style="height:20px">
                        <input type="submit" value="Apply Filters" class="btn btn-info pull-right">
                    </div>
                </form>

                <hr />
                <div class="table-responsive">
                    <table class="table table-bordered dt-table nowrap" `id="prospectsTable" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?php echo _l('Metadata'); ?></th>
                                <th><?php echo _l('Lead'); ?></th>
                                <th><?php echo _l('Contact'); ?></th>
                                <th><?php echo _l('Lead Type'); ?></th>
                                <th><?php echo _l('Actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prospects as $prospect): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            id="select<?php echo isset($prospect['id']) ? $prospect['id'] : ''; ?>" />
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo _l('Prospect ID'); ?>:</strong>
                                            <?php echo isset($prospect['id']) ? $prospect['id'] : '-'; ?><br>
                                            <strong><?php echo _l('date'); ?>:</strong>
                                            <?php echo isset($prospect['created_at'])
                                                ? htmlspecialchars(date('Y-m-d', strtotime($prospect['created_at']))) : '-'; ?><br />
                                            <strong><?php echo _l('Industry'); ?>:</strong>
                                            <?php echo isset($prospect['industry']) ? htmlspecialchars($prospect['industry']) : 'Unknown'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Full name:</strong>
                                            <?php echo isset($prospect['prospect_name']) ? htmlspecialchars($prospect['prospect_name']) : 'N/A'; ?><br>
                                            <strong><?php echo _l('Zip code'); ?>:</strong>
                                            <?php echo isset($prospect['zip_code']) ? htmlspecialchars($prospect['zip_code']) : 'N/A'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>Phone:</strong>
                                        <?php echo isset($prospect['phone']) ? htmlspecialchars($prospect['phone']) : 'N/A'; ?><br>
                                        <strong>Email:</strong>
                                        <?php echo isset($prospect['email']) ? htmlspecialchars($prospect['email']) : 'N/A'; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo _l('Source'); ?>:</strong>
                                            <?php echo isset($prospect['source']) ? htmlspecialchars($prospect['source']) : 'N/A'; ?><br>


                                            <div style="display:flex">
                                                <strong><?php echo _l('Quality'); ?> : </strong>
                                                <div>
                                                    <?php echo ($prospect['verified_staff']) == 1 ? 'Verified by Staff' : ''; ?><br />
                                                    <?php echo ($prospect['verified_whatsapp']) == 1 ? 'Verified by WhatsApp' : ''; ?><br />
                                                    <?php echo ($prospect['verified_sms']) == 1 ? 'Verified by SMS' : ''; ?><br />
                                                </div>
                                            </div>
                                            <?php if ($prospect['share_audio_before_purchase'] == 1) { ?>
                                                <strong><?php echo _l('deal_audio'); ?>:</strong>
                                                <?php if (isset($prospect['verified_staff_audio']) && !empty($prospect['verified_staff_audio'])): ?>
                                                    <audio controls>
                                                        <source
                                                            src="<?php echo htmlspecialchars($prospect['verified_staff_audio']); ?>"
                                                            type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                <?php else: ?>
                                                    <p>No audio available</p>
                                                <?php endif; ?>
                                            <?php } ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $is_discounted = false;
                                        if ($is_deal_settings_applied == true) {
                                            $dateString = $prospect['created_at'];

                                            // Create DateTime objects
                                            $givenDate = new DateTime($dateString);
                                            $currentDate = new DateTime(); // This will use the current date and time
                                    
                                            // Calculate the difference
                                            $interval = $currentDate->diff($givenDate);

                                            // Extract total hours from the interval
                                            $days = $interval->days;
                                            if ($days >= $days_to_discount) {
                                                $is_discounted = true;
                                            }
                                        }
                                        ?>
                                        <div class="align-items-center mbot5" style="display:flex">
                                            <strong><?php echo _l('leadevo_marketpalce_selling_price'); ?>:</strong>
                                            <span class="align-items-center" style="display:flex"><?php
                                            $discounted_price = $prospect['desired_amount'] ?? 0;
                                            if ($discount_type == 1) {
                                                //find percentage
                                                $discounted_price = $discounted_price - ($discounted_price * $discount_value) / 100;
                                            } else {
                                                $discounted_price = $discounted_price - ($discount_value ?? 0);
                                            }
                                            echo $discounted_price;
                                            if ($is_discounted) {
                                                echo '<span class="material-symbols-outlined" style="font-size:20px; color:orange;">sell</span>'
                                                    ?><br>
                                                <?php } ?></span>
                                        </div>
                                        <?php echo form_open(('dashboard/add_to_cart'), ['id' => 'add-to-cart-form']); ?>
                                        <input type="hidden" name="prospect_id" value="<?= $prospect['id'] ?>)" />
                                        <input type="hidden" name="price" value="<?= $discounted_price ?>" />

                                        <!-- Submit Button -->
                                        <input type="submit" class="btn btn-primary" <?= isset($prospect['is_in_cart']) && $prospect['is_in_cart'] == true ? 'disabled' : '' ?> value="Add to cart" />
                                        <?php echo form_close(); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>


                    </table>
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



<!-- Include jQuery (ensure it's included before your script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#filterForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting via the browser

            // Get CSRF token from the hidden field
            var csrfName = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').attr('name');
            var csrfHash = $('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val();

            $.ajax({
                url: $(this).attr('action'), // Use the form's action attribute
                type: $(this).attr('method'), // Use the form's method attribute
                data: $(this).serialize() + '&' + csrfName + '=' + csrfHash, // Serialize the form data and append CSRF token
                dataType: 'json', // Expect JSON response
                success: function (response) {
                    // Check if the response is an array
                    if (Array.isArray(response)) {
                        // Update the table with the filtered data
                        let tableBody = $('#prospectsTable tbody');
                        tableBody.empty(); // Clear the current table body

                        // Append new rows to the table body
                        response.forEach(prospect => {
                            let row = `<tr>
                            <td>
                                <div>
                                    <strong><?php echo _l('Prospect ID'); ?>:</strong> ${prospect.id || 'N/A'}<br>
                                    <strong><?php echo _l('Generated date'); ?>:</strong> N/A<br>
                                    <strong><?php echo _l('Industry'); ?>:</strong> ${prospect.industry || 'Unknown'}
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>Full name:</strong> ${prospect.prospect_name || 'N/A'}<br>
                                    <strong><?php echo _l('Zip code'); ?>:</strong> ${prospect.zip_code || 'N/A'}
                                </div>
                            </td>
                            <td>
                                <strong>Phone:</strong> ${prospect.phone || 'N/A'}<br>
                                <strong>Email:</strong> ${prospect.email || 'N/A'}
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo _l('Source'); ?>:</strong> ${prospect.source || 'Unknown'}<br>
                                    <strong><?php echo _l('Deal'); ?>:</strong> ${prospect.deal || 'N/A'}<br>
                                    <strong><?php echo _l('Quality'); ?>:</strong> ${prospect.quality || 'N/A'}
                                       <strong><?php echo _l('Quality'); ?>:</strong> ${prospect.quality || 'N/A'}
                                </div>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" id="select${prospect.id || ''}" />
                                <label for="select${prospect.id || ''}">Select</label>
                            </td>
                        </tr>`;
                            tableBody.append(row);
                        });
                    } else {
                        console.error('Invalid response format');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    });
    function addToCart(id) {
        $.ajax({
            url: site_url + 'dashboard/add_to_cart',
            type: 'POST',
            data: { 'prospect_id': id },
            success: function (response) {
                // reload
                document.location.reload();
            },
            error: function (xhr, status, error) {
                alert_float('danger', error?.message ?? 'Something went wrong. Please try again later')
            }
        })
    }
</script>