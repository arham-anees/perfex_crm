<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
function displayStars($rating, $maxStars = 5)
{
    if ($rating == 0 || $rating == '') {
        echo '-';
        return;
    }
    for ($i = 1; $i <= $maxStars; $i++) {
        echo '<span class="star' . ($i <= $rating ? ' filled' : '') . '">&#9733;</span>';
    }
}
?>

<style>
    .star.filled {
        color: orange;
    }

    #activity-log {
        margin: 10px 0;
    }

    .prospect-log-item {
        font-size: 12px;
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

</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <!-- Search Bar -->
            <div class="col-md-4">
                <!-- Optionally add a button or functionality here -->
            </div>

           
        </div>
        <div class="row">

            <div class="col-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <form id="filterForm" action="" method="post">
                        <?php $csrf = $this->security->get_csrf_hash(); ?>


                        <div class="row">
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="type"><?php echo _l('Type'); ?></label>
                                    <select id="type" name="type" class="filter-input">
                                        <option value="">Select Type</option>
                                       <?php foreach ($types as $type): ?>
                                            <option value="<?php echo $type->name; ?>" <?=$this->input->post('type')==$type->name ?'selected':''?>><?php echo $type->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="industry"><?php echo _l('Industry'); ?></label>
                                    <select id="industry" name="industry" class="filter-input">
                                        <option value="">Select Industry</option>
                                        <?php foreach ($industries as $industrie): ?>
                                            <option value="<?php echo $industrie['name']; ?>" <?=$this->input->post('industry_name')==$industrie['name'] ?'selected':''?>><?php echo $industrie['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="acquisition"><?php echo _l('acquisition_channel'); ?></label>
                                    <select id="acquisition" name="acquisition" class="filter-input">
                                        <option value="">Select Acquisition Channel</option>
                                        <?php foreach ($acquisition_channels as $acquisition): ?>
                                            <option value="<?php echo $acquisition->id; ?>" <?=$this->input->post('acquisition')==$acquisition->id ?'selected':''?>><?php echo $acquisition->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="email_normalization"><?php echo _l('Email Normalization'); ?></label>
                                    <select id="email_normalization" name="email_normalization" class="filter-input">
                                        <option value="">Select Email Normalization</option>
                                       
                                            <option value="1" <?=$this->input->post('email_normalization')==1 ?'selected':''?>>Pending
                                            </option>
                                             <option value="2" <?=$this->input->post('email_normalization')==2 ?'selected':''?>>Failed
                                            </option>
                                             <option value="3" <?=$this->input->post('email_normalization')==3 ?'selected':''?>>Normalized
                                            </option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="phone_normalization"><?php echo _l('Phone Normalization'); ?></label>
                                    <select id="phone_normalization" name="phone_normalization" class="filter-input">
                                        <option value="">Select Phone Normalization</option>
                                       
                                            <option value="1" <?=$this->input->post('phone_normalization')==1 ?'selected':''?>>Pending
                                            </option>
                                             <option value="2" <?=$this->input->post('phone_normalization')==2 ?'selected':''?>>Failed
                                            </option>
                                             <option value="3" <?=$this->input->post('phone_normalization')==3 ?'selected':''?>>Normalized
                                            </option>
                                       
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="price_range_start"><?php echo _l('Price Range start'); ?></label>
                                    <input type="text" id="price_range_start" name="price_range_start" class="filter-input"  value="<?=!empty($this->input->post('price_range_start')) ? $this->input->post('price_range_start'):''?>">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                              
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="price_range_end"><?php echo _l('Price Range end'); ?></label>
                                    <input type="text" id="price_range_end" name="price_range_end" class="filter-input" value="<?=!empty($this->input->post('price_range_end')) ? $this->input->post('price_range_end'):''?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="start_date"><?php echo _l('From'); ?></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" value=" <?=!empty($this->input->post('start_date')) ? $this->input->post('start_date '):''?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="end_date"><?php echo _l('To'); ?></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" value=" <?=!empty($this->input->post('end_date')) ? $this->input->post('end_date '):''?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
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
                        <div style="height: 40px; display: flex; justify-content: flex-end; gap: 10px;">
                        <input type="button" value="Clear Filters" class="btn btn-warning" onclick="resetForm();">
                        <input type="submit" value="Apply Filters" class="btn btn-info">
                    </div>
                    </form>
                    <hr class="hr-panel-heading">
                        <?php if (!empty($prospects)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-table " style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Name'); ?></th>
                                            <th><?php echo _l('Status'); ?></th>
                                            <th><?php echo _l('Stars'); ?></th>
                                            <th><?php echo _l('Type'); ?></th>
                                            <th><?php echo _l('Acquisition Channels'); ?></th>
                                            <th><?php echo _l('Industry'); ?></th>
                                            <th><?php echo _l('Status'); ?></th>
                                            <th><?php echo _l('Phone Normalization'); ?></th>
                                            <th><?php echo _l('Attempted At'); ?></th>
                                            <th><?php echo _l('Email Normalization'); ?></th>
                                            <th><?php echo _l('Attempted At'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prospects as $prospect): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? '-'); ?>
                                                    <div class="row-options">
                                                        <a href="#" onclick="openViewModal(<?= $prospect['id'] ?>)">View</a> |
                                                        <a href="<?php echo admin_url('prospects/edit/' . $prospect['id']); ?>"
                                                            class="">
                                                            Edit
                                                        </a> |
                                                        <a href="<?php echo admin_url('prospects/delete/' . $prospect['id']); ?>"
                                                            class=""
                                                            onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                            Delete
                                                        </a>
                                                        <?php if (staff_can('rate', 'leadevo')): ?>
                                                            | <a href="#"
                                                                onclick="openRateModal(<?= $prospect['id'] ?>)">Rate</a><?php endif; ?>
                                                        | <a href="#" data-toggle="modal"
                                                            data-target="#upload_conversation_modal">Upload
                                                            Conversation</a>
                                                        <?php if (!isset($prospect['is_fake']) || $prospect['is_fake'] == false) { ?>
                                                            | <a href="#" onclick="openModal(<?= $prospect['id'] ?>)">Mark
                                                                Fake</a> <?php } ?>
                                                        <?php if (!isset($prospect['is_available_sale']) || $prospect['is_available_sale'] == false) { ?>
                                                            | <a href="#" onclick="openSaleModal(<?= $prospect['id'] ?>)">
                                                                Put to Sale</a>
                                                        <?php } ?>
                                                        <?php if (!isset($prospect['is_auto_deliverable']) || $prospect['is_auto_deliverable'] == false) { ?>
                                                            | <a href="#"
                                                                onclick="openAutoDeliverableModal(<?= $prospect['id'] ?>)">
                                                                Auto Deliverable</a>
                                                        <?php } ?>
                                                        <?php if (!isset($prospect['verified_staff']) || $prospect['verified_staff'] == false) { ?>
                                                            | <a href="#" class="make-call-button disabled" style="color:grey"
                                                                data-phone="<?= $prospect['phone'] ?>">
                                                                Verify by Call</a>
                                                        <?php } ?>
                                                        | <a href="#" onclick="openSendCampaignModal(<?= $prospect['id'] ?>)">
                                                            Send via Campaign</a>


                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['status'] ?? '-'); ?></td>
                                                <td>
                                                    <div class="star-rating">
                                                        <?php


                                                        // Example usage
                                                        $userRating = $prospect['rating'] ?? 0; // This value could come from a database
                                                        displayStars($userRating);
                                                        ?>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['type'] ?? '-'); ?></td>

                                                <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? '-'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['industry'] ?? '-'); ?></td>
                                                <td>
                                                    <select name="confirm_status" class="form-control"
                                                        data-id="<?php echo $prospect['id']; ?>">
                                                        <option value="0" <?php echo ($prospect['confirm_status'] == 0) ? 'selected' : ''; ?>>Not Confirmed</option>
                                                        <option value="1" <?php echo ($prospect['confirm_status'] == 1) ? 'selected' : ''; ?>>Confirmed</option>
                                                    </select>
                                                </td>

                                                <td><?php echo htmlspecialchars($prospect['phone_normalize_status'] ?? '-'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['phone_normalize_attempt'] ?? '-'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['email_normalize_status'] ?? '-'); ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['email_normalize_attempt'] ?? '-'); ?>
                                                </td>
                                                <!-- <td>
                                                    <a href="<?php echo admin_url('prospects/view/' . $prospect['id']); ?>"
                                                        class="btn btn-default btn-icon">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('prospects/edit/' . $prospect['id']); ?>"
                                                        class="btn btn-default btn-icon">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('prospects/delete/' . $prospect['id']); ?>"
                                                        class="btn btn-danger btn-icon"
                                                        onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td> -->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p><?php echo _l('No prospects found.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- View Prospect Modal -->
<div class="modal fade" id="viewProspectModal" tabindex="-1" role="dialog">
    <?php echo get_instance()->load->view('admin/leadevo/prospects/modals/view_prospect.php') ?>
</div>

<div id="mark_prospect_fake" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <!-- Modal Header -->
            <div class="modal-header d-flex">
                <h4 class="modal-title w-100"><?php echo _l('leadevo_report_fake_prospect'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <!-- Font Awesome Close Icon -->
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(admin_url('prospects/mark_as_fake'), ['id' => 'fake-prospect-form']); ?>
                <input type="hidden" name="id" />
                <p><?= _l('leadevo_report_fake_prospect_message') ?></p>

                <!-- Description Input -->
                <div class="form-group">
                    <label for="fake_description"><?= _l('leadevo_fake_description_label') ?></label>
                    <textarea name="fake_description" id="fake_description" class="form-control" rows="4" required
                        placeholder="<?= _l('leadevo_fake_prospect_description_placeholder') ?>"></textarea>
                </div>

                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('leadevo_report_fake_prospect_button'); ?>"
                    class="btn btn-primary" />

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<div id="upload_conversation_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <p>Please integrate AirCall API</p>
            </div>
        </div>
    </div>
</div>


<div id="mark_prospect_auto_deliverable" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <!-- Modal Header -->
            <div class="modal-header d-flex">
                <h4 class="modal-title w-100"><?php echo _l('leadevo_report_auto_deliverable_title'); ?></h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(admin_url('prospects/mark_as_auto_deliverable'), ['id' => 'fake-prospect-form']); ?>
                <input type="hidden" name="id" />
                <p><?= _l('leadevo_report_auto_deliverable_message') ?></p>

                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('submit'); ?>" class="btn btn-primary" />

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div id="mark_sale_available_modal" class="modal fade" tabindex="-1" role="dialog">
    <?php echo get_instance()->load->view('admin/leadevo/prospects/modals/put_to_sale.php') ?>
</div>
<div id="send_via_campaign_modal" class="modal fade" tabindex="-1" role="dialog">
    <?php echo get_instance()->load->view('admin/leadevo/prospects/modals/send_via_campaign.php') ?>
</div>

<div id="rating_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <!-- Modal Header -->
            <div class="modal-header d-flex">
                <div></div>
                <h4 class="modal-title w-100"><?php echo _l('leadevo_prospect_ratings_title'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <!-- Font Awesome Close Icon -->
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(admin_url('prospects/rate'), ['id' => 'rate-prospect-form']); ?>
                <input type="hidden" name="id" />
                <div class="form-group">
                    <label for="nonexclusive_status" class="control-label clearfix">
                        <?= _l('leadevo_prospect_ratings_description'); ?>
                    </label>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_1stars" name="rating" value="1" ?>>
                        <label for="prospect_rating_1stars"><?= _l('leadevo_delivery_quality_1stars'); ?></label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_2stars" name="rating" value="2" ?>>
                        <label for="prospect_rating_2stars">
                            <?= _l('leadevo_delivery_quality_2stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_3stars" name="rating" value="3" ?>>
                        <label for="prospect_rating_3stars">
                            <?= _l('leadevo_delivery_quality_3stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_4stars" name="rating" value="4" ?>>
                        <label for="prospect_rating_4stars">
                            <?= _l('leadevo_delivery_quality_4stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_5stars" name="rating" value="5" ?>>
                        <label for="prospect_rating_5stars">
                            <?= _l('leadevo_delivery_quality_5stars'); ?>
                        </label>
                    </div>
                </div>
                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('submit'); ?>" class="btn btn-primary" />

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div></div>
                <h5 class="modal-title" id="uploadModalLabel">Upload MP3 File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadForm">
                    <div class="form-group">
                        <label for="mp3File">Choose MP3 file</label>
                        <input type="file" class="form-control-file" id="mp3File" accept=".mp3">
                    </div>
                    <div id="error-message" class="text-danger"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="storeFile()">Upload</button>
            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>

<script>
    $(document).ready(function () {
        $('select[name="confirm_status"]').on('change', function () {
            var status = $(this).val();
            var prospectId = $(this).data('id'); // Get the prospect ID from the data attribute

            $.ajax({
                url: '<?php echo admin_url("prospects/update_status"); ?>', // Replace with your URL
                type: 'POST',
                data: {
                    id: prospectId,
                    confirm_status: status
                },
                success: function (response) {
                    alert('Status updated successfully!');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error updating status: ' + errorThrown);
                }
            });
        });
    });

</script>


<script>
    function openModal(id) {
        document.querySelector('#mark_prospect_fake input[name=id]').value = id;
        $('#mark_prospect_fake').modal('show');
    }
    function openSaleModal(id) {
        document.querySelector('#mark_sale_available_modal input[name=id]').value = id;
        $('#mark_sale_available_modal').modal('show');
    }
    function openAutoDeliverableModal(id) {
        document.querySelector('#mark_prospect_auto_deliverable input[name=id]').value = id;
        $('#mark_prospect_auto_deliverable').modal('show');
    }
    function openSendCampaignModal(id) {
        document.querySelector('#send_via_campaign_modal input[name=id]').value = id;
        $('#send_via_campaign_modal').modal('show');
    }
    function openViewModal(id) {
        document.querySelector('#viewProspectModal input[name=id]').value = id;
        $('#viewProspectModal').modal('show');
    }
    function openRateModal(id) {
        document.querySelector('#rating_modal input[name=id]').value = id;
        $('#rating_modal').modal('show');
    }

</script>


<script>
    let uploadedFile = null;

    function storeFile() {
        const fileInput = document.getElementById('mp3File');
        const file = fileInput.files[0];
        const errorMessage = document.getElementById('error-message');

        if (file && file.type === 'audio/mpeg') {
            uploadedFile = file;
            alert('File stored successfully!');
            $('#uploadModal').modal('hide');
        } else {
            errorMessage.textContent = 'Please upload a valid MP3 file.';
        }
    }

</script>

<script>
    let id = null;
    function uploadFile() {
        const fileInput = document.getElementById('mp3File');
        const file = fileInput.files[0];
        const errorMessage = document.getElementById('error-message');

        if (file && file.type === 'audio/mpeg') {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('id', id); // Include the prospect ID

            $.ajax({
                url: '<?php echo admin_url("leadevo/prospects/upload_mp3"); ?>', // Replace with your server upload URL
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('File uploaded successfully!');
                    $('#uploadModal').modal('hide');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage.textContent = 'Error uploading file: ' + errorThrown;
                }
            });
        } else {
            errorMessage.textContent = 'Please upload a valid MP3 file.';
        }
    }
    $('.make-call-button').on('click', function () {
        var phoneNumber = '+923311116993';// $(this).data('phone');

        $.ajax({
            url: 'make_call',  // Replace with the actual path
            method: 'POST',
            data: { phone: phoneNumber },
            success: function (response) {
                alert('Call initiated successfully.');
            },
            error: function () {
                alert('Failed to initiate call.');
            }
        });
    });


    
</script>
<script>
       function resetForm() {
        // Reset form fields
        document.getElementById('filterForm').reset();
        
        // Reload the page without any filters (remove query parameters)
        window.location.href = window.location.pathname;
    }
</script>


</body>

</html>