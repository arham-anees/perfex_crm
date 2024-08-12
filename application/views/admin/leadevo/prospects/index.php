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
                                            <th><?php echo _l('actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prospects as $prospect): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? ''); ?>
                                                    <div class="row-options">
                                                        <a href="#">View</a> |
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

                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['status'] ?? ''); ?></td>
                                                <td>
                                                    <div class="star-rating">
                                                        <?php


                                                        // Example usage
                                                        $userRating = $prospect['rating'] ?? 0; // This value could come from a database
                                                        displayStars($userRating);
                                                        ?>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($prospect['type'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['category'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['industry'] ?? ''); ?></td>
                                                <td>
                                                    <select name="confirm_status" class="form-control"
                                                        data-id="<?php echo $prospect['id']; ?>">
                                                        <option value="0" <?php echo ($prospect['confirm_status'] == 0) ? 'selected' : ''; ?>>Not Confirmed</option>
                                                        <option value="1" <?php echo ($prospect['confirm_status'] == 1) ? 'selected' : ''; ?>>Confirmed</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <a href="<?php echo admin_url('leadevo/client/prospect/view/' . $prospect['id']); ?>"
                                                        class="btn btn-default btn-icon">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('leadevo/client/prospect/edit/' . $prospect['id']); ?>"
                                                        class="btn btn-default btn-icon">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('leadevo/client/prospect/delete/' . $prospect['id']); ?>"
                                                        class="btn btn-danger btn-icon"
                                                        onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </td>
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

<div id="mark_sale_available_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <!-- Modal Header -->
            <div class="modal-header d-flex">
                <div></div>
                <h4 class="modal-title w-100"><?php echo _l('leadevo_sale_available_prospect'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <!-- Font Awesome Close Icon -->
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(admin_url('prospects/mark_as_available_sale'), ['id' => 'fake-prospect-form']); ?>
                <input type="hidden" name="id" />
                <p><?= _l('leadevo_sale_available_prospect_message') ?></p>

                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('leadevo_sale_available_prospect_button'); ?>"
                    class="btn btn-primary" />

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
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
</script>

</body>

</html>