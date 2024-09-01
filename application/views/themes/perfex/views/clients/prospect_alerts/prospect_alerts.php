<style>
    .alert-card {
        margin-bottom: 20px; 
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #F1F5F9;
    }

    .alert-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .alert-avatar {
        display: flex;
        align-items: center;
        position: relative;
        margin-right: 20px; 
    }

    .status {
        position: absolute;
        top: -5px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
        padding: 2px 5px;
        border-radius: 3px;
    }

    .status.active {
        color: green;
        border: 1px solid green;
    }

    .status.inactive {
        color: red;
        border: 1px solid red; 
    }

    .avatar-circle {
        width: 60px;
        height: 60px;
        background-color: #2563EB;
        border-radius: 50%;
        display: flex;
        margin-top: 25px;
        align-items: center;
        justify-content: center;
        margin-right: 20px; /* Ensure there's space between the avatar and the name */
    }

    .initials {
        font-size: 16px;
        font-weight: bold;
        color: #fff;
    }

    .alert-name {
        font-size: 16px;
        font-weight: bold;
        margin-left: 15px; /* Add margin to push the name away from the avatar circle */
    }

    .alert-main-details {
        display: flex;
        flex: 3;
        align-items: center;
        justify-content: space-between;
        padding-left: 30px; 
    }

    .alert-details {
        display: flex;
        flex-direction: column;
    }

    .category {
        text-align: center;
        flex: 1;
    }

    .contact-info {
        margin-top: 5px;
    }

    .alert-options {
        flex: 1;
        text-align: center;
    }
</style>

<div class="row main_row">
    
<div class="_buttons">
            <div class="row">
                <!-- Search Bar -->
                <div class="col-md-4">
                    <!-- Optionally add a button or functionality here -->
                </div>

                <!-- Filters -->
                <div class="col-md-8" style="display:flex;justify-content:end">
                    <form method="GET" action="<?php echo site_url('prospect_alerts'); ?>" style="margin-right: 10px;">
                        <div class="input-group" style="width:200px">
                            <input type="text" name="search" class="form-control"
                                placeholder="<?php echo _l('Search Prospect Alerts'); ?>"
                                value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <form method="GET" action="<?php echo site_url('prospect_alerts'); ?>">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <?php echo _l('Filter By'); ?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li><a
                                        href="<?php echo site_url('prospect_alerts?filter=all'); ?>"><?php echo _l('All'); ?></a>
                                </li>
                                <li><a
                                        href="<?php echo site_url('prospect_alerts?filter=active'); ?>"><?php echo _l('Active'); ?></a>
                                </li>
                                <li><a
                                        href="<?php echo site_url('prospect_alerts?filter=inactive'); ?>"><?php echo _l('Inactive'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <div class="clearfix"></div>
    <hr class="hr-panel-heading" />
    <div class="_buttons">
        <a href="<?php echo site_url('prospect_alerts/create'); ?>"
            class="tw-mb-3 mleft15 btn btn-primary pull-left display-block ">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            <?php echo _l('New Prospect Alert'); ?>
        </a>
    </div>
    <div class="col-md-12">
    <div class="panel_s">
        <div class="panel-body">
            <?php if (!empty($alerts)): ?>
                <?php foreach ($alerts as $alert): ?>
                    <!-- Separate Card for Each Alert -->
                    <div class="alert-card">
                        <div class="alert-item">
                            <div class="alert-avatar">
                                <span class="status <?php echo $alert['status'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $alert['status'] ? 'Active' : 'Inactive'; ?>
                                </span>
                                <div class="avatar-circle">
                                    <span class="initials"><?php echo strtoupper(substr($alert['name'], 0, 1)); ?></span>
                                </div>
                                <div class="alert-name"><?php echo htmlspecialchars($alert['name']); ?></div>
                            </div>
                            <div class="alert-main-details">
                                <div class="alert-details">
                                    <div class="contact-info">
                                        <span class="email"><i class="fa fa-envelope"></i> <?php echo htmlspecialchars($alert['email']); ?></span><br>
                                        <span class="phone"><i class="fa fa-phone"></i> <?php echo htmlspecialchars($alert['phone']); ?></span>
                                    </div>
                                </div>
                                <div class="category">
                                    <span><strong>Category:</strong></span>
                                    <span><?php echo htmlspecialchars($alert['prospect_category'] ?? ''); ?></span><br>
                                    <span><strong>Industry:</strong></span>
                                    <span><?php echo htmlspecialchars($alert['prospect_industry']?? ''); ?></span><br>
                                    <span><strong>Acquisition Channel:</strong></span>
                                    <span><?php echo htmlspecialchars($alert['acquisition_channel']?? ''); ?></span>
                                </div>
                            </div>
                            <div class="alert-options">
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fa fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="<?php echo site_url('prospect_alerts/edit/' . $alert['id']); ?>">Edit</a></li>
                                        <li><a href="<?php echo site_url('prospect_alerts/delete/' . $alert['id']); ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this prospect?');">Delete</a></li>
                                        <?php if ($alert['status'] == 0) { ?>
                                            <li><a href="<?php echo site_url('prospect_alerts/activate/' . $alert['id']); ?>">Activate</a></li>
                                        <?php } else if ($alert['status'] == 1) { ?>
                                            <li><a href="<?php echo site_url('prospect_alerts/deactivate/' . $alert['id']); ?>" class="text-danger">Deactivate</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><?php echo _l('No prospects found.'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
