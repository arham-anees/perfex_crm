<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <!-- Search bar and filters -->
                <div class="clearfix"></div>

                <div class="_buttons">
                    <div class="row">
                        <!-- Search Bar -->
                        <div class="col-md-4">
                            <!-- Optionally add a button or functionality here -->
                        </div>

                        <div class="col-md-8" style="display:flex;justify-content:end">
                            <form method="GET" action="<?php echo admin_url('prospects/fake'); ?>" style="margin-right: 10px;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <?php echo _l('Filter By Acquisition Channel'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li>
                                            <a href="<?php echo admin_url('prospects/fake'); ?>"
                                                class="<?php echo (isset($_GET['acquisition_channel_id']) && $_GET['acquisition_channel_id'] == 'all' ? 'active' : ''); ?>">
                                                <?php echo _l('all'); ?>
                                            </a>
                                        </li>
                                        <?php foreach ($acquisition_channels as $channel): ?>
                                            <li>
                                                <a href="<?php echo admin_url('prospects/fake?acquisition_channel_id=' . $channel->id); ?>"
                                                    class="<?php echo (isset($_GET['acquisition_channel_id']) && $_GET['acquisition_channel_id'] == $channel->id ? 'active' : ''); ?>">
                                                    <?php echo htmlspecialchars($channel->name); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </form>

                            <form method="GET" action="<?php echo admin_url('prospects/fake'); ?>" style="margin-right: 10px;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <?php echo _l('Filter By Industry'); ?> <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                        <li>
                                            <a href="<?php echo admin_url('prospects/fake'); ?>"
                                                class="<?php echo (isset($_GET['industry_id']) && $_GET['industry_id'] == 'all' ? 'active' : ''); ?>">
                                                <?php echo _l('all'); ?>
                                            </a>
                                        </li>
                                        <?php foreach ($industries as $industry): ?>
                                            <li>
                                                <a href="<?php echo admin_url('prospects/fake?industry_id=' . $industry['id']); ?>"
                                                    class="<?php echo (isset($_GET['industry_id']) && $_GET['industry_id'] == $industry['id'] ? 'active' : ''); ?>">
                                                    <?php echo htmlspecialchars($industry['name']); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>

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
                                                        <th><?php echo _l('Type'); ?></th>
                                                        <th><?php echo _l('Category'); ?></th>
                                                        <th><?php echo _l('Acquisition Channels'); ?></th>
                                                        <th><?php echo _l('Industry'); ?></th>
                                                        <th><?php echo _l('Fake Description'); ?></th>
                                                        <th><?php echo _l('Report Date'); ?></th>
                                                        <th><?php echo _l('Marked By'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($prospects as $prospect): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? 'N/A'); ?>
                                                                <div class="row-options">
                                                                    <?php if (!isset($prospect['is_fake']) || $prospect['is_fake'] == true) { ?>
                                                                        | <a href="<?php echo admin_url('prospects/remove_fake/' . $prospect['id']); ?>"
                                                                            class=""
                                                                            onclick="return confirm('Are you sure you want to remove from fake ?');">
                                                                            Remove from fake
                                                                        </a> <?php } ?>
                                                                </div>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($prospect['status'] ?? 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($prospect['type'] ?? 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($prospect['category'] ?? 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($prospect['industry'] ?? 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($prospect['fake_description'] ?? 'N/A'); ?></td>
                                                            <td><?php echo htmlspecialchars($prospect['fake_report_date'] ?? 'N/A'); ?></td>
                                                            <td><?php
                                                                echo get_staff_full_name($prospect['marked_by_admin_id']);
                                                                ?></td>
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

            <!-- Modal Structure -->
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
                                <textarea name="fake_description" id="fake_description" class="form-control" rows="4" required></textarea>
                            </div>

                            <!-- Submit Button -->
                            <input type="submit" value="<?php echo _l('leadevo_report_fake_prospect_button'); ?>"
                                class="btn btn-primary" />

                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Other Modals -->

<?php init_tail(); ?>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('select[name="confirm_status"]').on('change', function() {
            var status = $(this).val();
            var prospectId = $(this).data('id'); // Get the prospect ID from the data attribute

            $.ajax({
                url: '<?php echo admin_url("prospects/update_status"); ?>', // Replace with your URL
                type: 'POST',
                data: {
                    id: prospectId,
                    confirm_status: status
                },
                success: function(response) {
                    alert('Status updated successfully!');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error updating status: ' + errorThrown);
                }
            });
        });
    });

    function openModal(id) {
        document.querySelector('#mark_prospect_fake input[name=id]').value = id;
        $('#mark_prospect_fake').modal('show');
    }
</script>

<!-- Other JavaScript -->
</body>

</html>
