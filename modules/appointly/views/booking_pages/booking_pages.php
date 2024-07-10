<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <table class="table table-statuses">
                            <thead>
                                <tr>
                                    <th><?php echo _l('Name'); ?></th>
                                    <th><?php echo _l('Description'); ?></th>
                                    <th><?php echo _l('Url'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($booking_pages)): ?>
                                    <?php foreach ($booking_pages as $page): ?>
                                        <tr>
                                            <td><?php echo $page['name']; ?></td>
                                            <td><?php echo $page['description']; ?></td>
                                            <td><?php echo $page['url']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="2"><?php echo _l('No booking pages found.'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
