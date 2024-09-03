<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row main_row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Start of panel body -->

                        <!-- Search bar and filters -->
                        <div class="_buttons">
                            <div class="row">
                                <!-- Search Bar -->
                                <div class="col-md-6">
                                    <form method="GET" action="<?php echo admin_url('leadevo/Prospect_sources'); ?>">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="<?php echo _l('Search Prospect Source'); ?>"
                                                value="<?php echo isset($search) ? $search : ''; ?>">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                                <!-- Filters
                                <div class="col-md-6 text-right">
                                    <form method="GET" action="<?php echo admin_url('leadevo/Prospect_sources'); ?>">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <?php echo _l('Filter By'); ?> <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                <li><a href="<?php echo admin_url('leadevo/Prospect_sources?filter=active'); ?>"><?php echo _l('Active Prospects'); ?></a></li>
                                                <li><a href="<?php echo admin_url('leadevo/Prospect_sources?filter=inactive'); ?>"><?php echo _l('Inactive Prospects'); ?></a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </div> -->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                        <div class="_buttons">
                            <a href="<?php echo admin_url('leadevo/Prospect_sources/add'); ?>"
                                class="tw-mb-3 mleft15 btn btn-primary pull-left display-block ">
                                <i class="fa-regular fa-plus tw-mr-1"></i>
                                <?php echo _l('New Prospect Source'); ?>
                            </a>
                        </div>
                        <!-- Prospect Table -->
                        <div class="col-md-12">
                            <div class="panel_s">
                                <div class="panel-body">
                                    <?php if (!empty($prospect_sources)): ?>
                                        <table class="table dt-table scroll-responsive">
                                            <thead>
                                                <tr>
                                                    <th><?php echo _l('Name'); ?></th>
                                                    <th><?php echo _l('Description'); ?></th>
                                                    <th><?php echo _l('Status'); ?></th>
                                                    <th class="text-right"><?php echo _l('Actions'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($prospect_sources as $source): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($source['name'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($source['description'] ?? ''); ?></td>
                                                        <td><?php echo $source['is_active']==1?'Active':'Inactive' ; ?></td>
                                                        <td class="text-right">
                                                            <a href="<?php echo admin_url('leadevo/Prospect_sources/edit/' . $source['id']); ?>"
                                                                class="btn btn-default btn-icon">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a href="<?php echo admin_url('leadevo/Prospect_sources/delete/' . $source['id']); ?>"
                                                                class="btn btn-danger btn-icon"
                                                                onclick="return confirm('Are you sure you want to delete this prospect source?');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p><?php echo _l('No prospects found.'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- End of panel body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>

</html>