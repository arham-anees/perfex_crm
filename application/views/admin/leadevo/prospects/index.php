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
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php if (!empty($prospects)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-table nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Name'); ?></th>
                                            <th><?php echo _l('Status'); ?></th>
                                            <th><?php echo _l('stars'); ?></th>
                                            <th><?php echo _l('Type'); ?></th>
                                            <th><?php echo _l('Category'); ?></th>
                                            <th><?php echo _l('Acquisition Channels'); ?></th>
                                            <th><?php echo _l('Industry'); ?></th>
                                            <th><?php echo _l('Status'); ?></th>
                                            <!-- <th><?php echo _l('actions'); ?></th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prospects as $prospect): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? 'N/A'); ?>
                                                    <div class="row-options">
                                                        <a href="#" onclick="openViewModal(<?= $prospect['id'] ?>)">View</a> |
                                                        <a href="#" onclick="openRateModal(<?= $prospect['id'] ?>)">Rate</a> |


                                                        <a href="#" data-toggle="modal" data-target="#mark_prospect_fake">Upload
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
                                                <td><?php echo htmlspecialchars($prospect['status'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <div class="star-rating">
                                                        <?php


                                                        // Example usage
                                                        $userRating = $prospect['rating'] ?? 0; // This value could come from a database
                                                        displayStars($userRating);
                                                        ?>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['type'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['category'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['industry'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <select name="confirm_status" class="form-control"
                                                        data-id="<?php echo $prospect['id']; ?>">
                                                        <option value="0" <?php echo ($prospect['confirm_status'] == 0) ? 'selected' : ''; ?>>Not Confirmed</option>
                                                        <option value="1" <?php echo ($prospect['confirm_status'] == 1) ? 'selected' : ''; ?>>Confirmed</option>
                                                    </select>
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
<div class="modal fade" id="viewProspectModal" tabindex="-1" role="dialog" aria-labelledby="viewProspectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProspectModalLabel">Prospect Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Prospect data will be loaded here via AJAX -->
                <div id="prospectDetails">
                    <!-- Example Fields -->
                    <p><strong>First Name:</strong> <span id="prospectFirstName"></span></p>
                    <p><strong>Last Name:</strong> <span id="prospectLastName"></span></p>
                    <p><strong>Status:</strong> <span id="prospectStatus"></span></p>
                    <p><strong>Type:</strong> <span id="prospectType"></span></p>
                    <p><strong>Category:</strong> <span id="prospectCategory"></span></p>
                    <p><strong>Acquisition Channel:</strong> <span id="prospectAcquisitionChannel"></span></p>
                    <p><strong>Industry:</strong> <span id="prospectIndustry"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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

                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('leadevo_report_fake_prospect_button'); ?>"
                    class="btn btn-primary" />

                <?php echo form_close(); ?>
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
function openViewModal(prospectId) {
    $.ajax({
        url: '<?= admin_url('prospects/get_prospect_data') ?>',
        type: 'GET',
        data: { id: prospectId },
        success: function(response) {
            const data = JSON.parse(response);

            // Populate the modal with the first name, last name, and other details
            $('#prospectFirstName').text(data.first_name);
            $('#prospectLastName').text(data.last_name);
            $('#prospectStatus').text(data.status);
            $('#prospectType').text(data.type);
            $('#prospectCategory').text(data.category);
            $('#prospectAcquisitionChannel').text(data.acquisition_channel);
            $('#prospectIndustry').text(data.industry);

            // Show the modal
            $('#viewProspectModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Failed to fetch prospect data: ' + errorThrown);
        }
    });
}
</script>

</body>

</html>