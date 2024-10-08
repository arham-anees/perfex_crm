<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/industries/create'); ?>"
                                class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Industry'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <?php if (!empty($industries)): ?>
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Description'); ?></th>
                                        <th><?php echo _l('Category'); ?></th> <!-- Added Category Column -->
                                        <th><?php echo _l('Status'); ?></th> <!-- Added Category Column -->
                                        <th><?php echo _l('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($industries as $industry): ?>
                                        <tr>
                                            <td><?php echo $industry['name']; ?></td>
                                            <td><?php echo $industry['description']; ?></td>
                                            <td>
                    <?php
                    // Find the category name based on the category_id
                    $categoryName = '';
                    foreach ($categories as $category) {
                        if ($category['id'] == $industry['category_id']) {
                            $categoryName = $category['name'];
                            break;
                        }
                    }
                    echo $categoryName;
                    ?>
                                            </td>
                                            <td><?php echo $industry['is_active']==1?'Active':'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('leadevo/industries/view/' . $industry['id']); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/industries/edit/' . $industry['id']); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('leadevo/industries/delete/' . $industry['id']); ?>"
                                                    class="btn btn-danger btn-icon"
                                                    onclick="return confirm('Are you sure you want to delete this industry?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p><?php echo _l('No industries found.'); ?></p>
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