<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    html, body {
    height: 100%;
    margin: 0; 
}
   .container{
         margin-left: 16%;
         height: 100%;
          background-color: aliceblue;
          padding: 20px;
   }
</style>
<body>
    <div class="container">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('information_point/create'); ?>" class="btn btn-primary pull-left display-block mleft10">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('Information point'); ?>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <?php if (!empty($information_point)): ?>
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('id'); ?></th>
                                        <th><?php echo _l('info_key'); ?></th>
                                        <th><?php echo _l('info'); ?></th>
                                        <th><?php echo _l('Actions'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($information_point as $informationpoint): ?>
                                        <tr>
                                            <td><?php echo $informationpoint->id??'N/A'; ?></td>
                                            <td><?php echo $informationpoint->info_key??'N/A'; ?></td>
                                            <td><?php echo htmlspecialchars($informationpoint->info ??'N/A'); ?></td>
                                            <td>
                                                
                                                <a href="<?php echo admin_url('information_point/edit/' . $informationpoint->id); ?>"
                                                   class="btn btn-default btn-icon">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo admin_url('information_point/delete/' . $informationpoint->id); ?>"
                                                   class="btn btn-danger btn-icon"
                                                   onclick="return confirm('Are you sure you want to delete this Information point ?');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                        <?php else: ?>
                            <p><?php echo _l('No Information found.'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php init_tail(); ?>
</body>
</html>
