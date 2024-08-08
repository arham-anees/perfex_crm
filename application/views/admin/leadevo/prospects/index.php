<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

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
                                            <th><?php echo _l('Status Id'); ?></th>
                                            <th><?php echo _l('Type Id'); ?></th>
                                            <th><?php echo _l('Category Id'); ?></th>
                                            <th><?php echo _l('Acquisition Channels Id'); ?></th>
                                            <th><?php echo _l('Industry Id'); ?></th>
                                            <th><?php echo _l('Actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prospects as $prospect): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? ''); ?>
                                                    <div class="row-options">
                                                        <a href="#">View</a> |
                                                        <a href="#">Rate</a> |
                                                        <a href="#">Confirm</a> |
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
                                                <td><?php echo htmlspecialchars($prospect['type'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['category'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($prospect['industry'] ?? ''); ?></td>

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

<?php init_tail(); ?>



<script>
    function openModal(id) {
        document.querySelector('#mark_prospect_fake input[name=id]').value = id;
        $('#mark_prospect_fake').modal('show');
    }
    function openSaleModal(id) {
        document.querySelector('#mark_sale_available_modal input[name=id]').value = id;
        $('#mark_sale_available_modal').modal('show');
    }

</script>
</body>

</html>