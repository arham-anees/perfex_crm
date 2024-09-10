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
        margin-right: 20px;
        /* Ensure there's space between the avatar and the name */
    }

    .initials {
        font-size: 16px;
        font-weight: bold;
        color: #fff;
    }

    .alert-name {
        font-size: 16px;
        font-weight: bold;
        margin-left: 15px;
        /* Add margin to push the name away from the avatar circle */
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


    .filters {
        background-color: rgb(255, 255, 255);
        color: rgba(0, 0, 0, 0.87);
        box-shadow: rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.14) 0px 2px 2px 0px, rgba(0, 0, 0, 0.12) 0px 1px 5px 0px;
        position: sticky;
        z-index: 1;
        top: 5%;
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 20px;
        padding: 10px 16px 18px;
        margin: 20px 0;

    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .lead-card {
        display: flex;
        background-color: rgb(240, 240, 241);
        color: rgba(0, 0, 0, 0.87);
        transition: box-shadow 300ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 1px -1px, rgba(0, 0, 0, 0.14) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 1px 3px 0px;
        border-radius: 20px;
        overflow: hidden;
        padding: 16px;
        margin: 10px 0;
    }

    .fullscreenBtn {
        padding: 5px 10px !important;
        font-size: 1.2rem !important;
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
                <form id="filterForm" action="" method="post">
                    <?php $csrf = $this->security->get_csrf_hash(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="name"><?php echo _l('Name'); ?></label>
                                <input type="text" id="name" name="name" class="filter-input"
                                    value="<?= !empty($_POST['name']) ? $_POST['name'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="email"><?php echo _l('Email'); ?></label>
                                <input type="text" id="email" name="email" class="filter-input"
                                    value="<?= !empty($_POST['email']) ? $_POST['email'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="phone_no"><?php echo _l('Phone No'); ?></label>
                                <input type="text" id="phone_no" name="phone_no" class="filter-input"
                                    value="<?= !empty($_POST['phone_no']) ? $_POST['phone_no'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="acquisition"><?php echo _l('acquisition_channel'); ?></label>
                                <select id="acquisition" name="acquisition" class="filter-input">
                                    <option value="">Select Acquisition Channel</option>
                                    <?php foreach ($acquisition_channels as $acquisition): ?>
                                        <option value="<?php echo $acquisition->id; ?>"
                                            <?= $this->input->post('acquisition') == $acquisition->id ? 'selected' : '' ?>>
                                            <?php echo $acquisition->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="industry"><?php echo _l('Industry'); ?></label>
                                <select id="industry" name="industry" class="filter-input">
                                    <option value="">Select Industry</option>
                                    <?php foreach ($industries as $industrie): ?>
                                        <option value="<?php echo $industrie['name']; ?>"
                                            <?= $this->input->post('industry_name') == $industrie['name'] ? 'selected' : '' ?>>
                                            <?php echo $industrie['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="deal"><?php echo _l('Deal'); ?></label>
                                <select id="deal" name="deal" class="filter-input">
                                    <option value="">Select Deal</option>
                                    <option value="1" <?= $this->input->post('deal') == '1' ? 'selected' : '' ?>>Exclusive
                                    </option>
                                    <option value="0" <?= $this->input->post('deal') == '0' ? 'selected' : '' ?>>Non-Exclusive
                                    </option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="status"><?php echo _l('Status'); ?></label>
                                <select id="status" name="status" class="filter-input">
                                    <option value="">Select Status</option>
                                    <option value="1" <?= $this->input->post('status') == '1' ? 'selected' : '' ?>>Active
                                    </option>
                                    <option value="0" <?= $this->input->post('status') == '0' ? 'selected' : '' ?>>Inactive
                                    </option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-4">
                            <!-- <button class="btn regular_price_btn">
                        <div class="button-content">
                            <i class="fa fa-shopping-cart"></i>
                            <div class="text-container">
                                <span class="bold-text">$345-$563 Buy lead</span>
                                <span class="small-text">regular price</span>
                            </div>
                        </div>
                    </button> -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                value="<?php echo $this->security->get_csrf_hash(); ?>">
                        </div>


                        <div style="height:20px">
                            <input type="submit" value="Apply Filters" class="btn btn-info pull-right">
                        </div>
                </form>
                <hr class="hr-panel-heading" style="margin: 1.25rem 0rem;" />
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
                                            <span class="email"><i class="fa fa-envelope"></i>
                                                <?php echo htmlspecialchars($alert['email']); ?></span><br>
                                            <span class="phone"><i class="fa fa-phone"></i>
                                                <?php echo htmlspecialchars($alert['phone']); ?></span>
                                        </div>
                                    </div>
                                    <div class="category">
                                        <span><strong>Industry:</strong></span>
                                        <span><?php echo htmlspecialchars($alert['prospect_industry'] ?? ''); ?></span><br>
                                        <span><strong>Acquisition Channel:</strong></span>
                                        <span><?php echo htmlspecialchars($alert['acquisition_channel'] ?? ''); ?></span>
                                    </div>

                                </div>
                                <div class="alert-options">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a
                                                    href="<?php echo site_url('prospect_alerts/edit/' . $alert['id']); ?>">Edit</a>
                                            </li>
                                            <li><a href="<?php echo site_url('prospect_alerts/delete/' . $alert['id']); ?>"
                                                    class="text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this prospect?');">Delete</a>
                                            </li>
                                            <?php if ($alert['status'] == 0) { ?>
                                                <li><a
                                                        href="<?php echo site_url('prospect_alerts/activate/' . $alert['id']); ?>">Activate</a>
                                                </li>
                                            <?php } else if ($alert['status'] == 1) { ?>
                                                    <li><a href="<?php echo site_url('prospect_alerts/deactivate/' . $alert['id']); ?>"
                                                            class="text-danger">Deactivate</a></li>
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