<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-statuses tbody tr:hover .row-options {
        display: block;
        /* Show the options on hover */
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons">
                    <?php { ?>
                        <a href="<?php echo admin_url('appointly/booking_pages/create'); ?>" class="btn btn-primary pull-left display-block tw-mb-2 sm:tw-mb-4">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_booking'); ?>
                        </a>
                    <?php } ?>

                    <div class="clearfix"></div>

                </div>




                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($booking_pages)) : ?>
                            <table class="table dt-table table-statuses" data-order-col="0" data-order-type="asc">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Description'); ?></th>
                                        <th><?php echo _l('Url'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($booking_pages as $page) : ?>
                                        <tr>
                                            <td><?php echo $page['name']; ?>
                                                <div class="row-options" style="display: none;">
                                                    <!-- <a href="edit_url">Edit</a> | -->
                                                    <a href="<?= admin_url('appointly/booking_pages/booking_page/' . $page['url']) ?>">View</a>
                                                </div>
                                            </td>
                                            <td><?php echo $page['description']; ?></td>
                                            <td><?php echo $page['url']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p><?php echo _l('No booking pages found.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>















            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        $('.table-statuses tbody tr').hover(
            function() {
                $(this).find('.row-options').show();
            },
            function() {
                $(this).find('.row-options').hide();
            }
        );
    });
</script>
</body>

</html>