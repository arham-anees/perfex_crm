<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div class="panel_s">
                    <div class="panel-body">
                    <div class="_buttons">
                            <a href="<?php echo admin_url('affiliate_training_videos/create'); ?>" class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('Add Affiliate Training Video'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <?php if (!empty($videos)) : ?>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-table nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('Id'); ?></th>
                                            <th><?php echo _l('Name'); ?></th>
                                            <th><?php echo _l('URL'); ?></th>
                                            <th><?php echo _l('Description'); ?></th>
                                            <th><?php echo _l('Order'); ?></th>
                                            <th><?php echo _l('Actions'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($videos as $video) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($video['id']??'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($video['name']??'N/A'); ?></td>
                                                <td><a href="<?php echo htmlspecialchars($video['url']??'N/A'); ?>" target="_blank"><?php echo _l('Watch Video'); ?></a></td>
                                                <td><?php echo htmlspecialchars($video['description']??'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($video['video_order']??'N/A'); ?></td>
                                               
                                                <td>
                                                    <a href="<?php echo admin_url('affiliate_training_videos/view/' . $video['id']); ?>"
                                                    class="btn btn-default btn-icon">
                                                    <i class="fa fa-eye"></i>
                                                    <a href="<?php echo admin_url('affiliate_training_videos/edit/' . $video['id']); ?>" class="btn btn-default btn-icon">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('affiliate_training_videos/delete/' . $video['id']); ?>" class="btn btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this video?');">
                                                    <i class="fa fa-remove"></i>
                                                    </a>    
                                               
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p><?php echo _l('No videos found.'); ?></p>
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

