<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/industry_categories/create'); ?>"
                                class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Industry Category'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($categories)): ?>
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Description'); ?></th>
                                        <th><?php echo _l('leadevo_industry_category_min_price'); ?></th>
                                        <th><?php echo _l('leadevo_industry_category_min_market_price'); ?></th>
                                        <th><?php echo _l('actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><?php echo $category['name']; ?></td>
                                            <td><?php echo $category['description']; ?></td>
                                            <td><?php echo $category['min_price'] ?? '-'; ?></td>
                                            <td><?php echo $category['min_market_price'] ?? '-'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('leadevo/industry_categories/view/' . $category['id']); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/industry_categories/edit/' . $category['id']); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/industry_categories/delete/' . $category['id']); ?>"
                                                    class="btn btn-danger btn-icon"
                                                    onclick="return confirm('Are you sure you want to delete this industry category?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p><?php echo _l('No industry categories found.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>