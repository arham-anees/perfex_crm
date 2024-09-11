<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style type="text/css">
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
    <div class="col-md-12">
        <!-- Search bar and filters -->
        <div class="clearfix"></div>

        <div class="_buttons">
            <div class="row">
                <!-- Search Bar -->
                <div class="col-md-4">
                    <a href="<?php echo site_url('prospects/create'); ?>"
                        class="tw-mb-3 mleft15 btn btn-primary pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('New Prospect'); ?>
                    </a>
                </div>

                <!-- Filters -->
                
            </div>
        </div>

        <!-- Prospect Table -->
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <form id="filterForm" action="" method="post">
                        <?php $csrf = $this->security->get_csrf_hash(); ?>


                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="acquisition"><?php echo _l('acquisition_channel'); ?></label>
                                    <select id="acquisition" name="acquisition" class="filter-input">
                                        <option value="">Select Acquisition Channel</option>
                                        <?php foreach ($acquisitions as $acquisition): ?>
                                            <option value="<?php echo $acquisition->id; ?>" <?=$this->input->post('acquisition')==$acquisition->id ?'selected':''?>><?php echo $acquisition->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="price_range_start"><?php echo _l('Price Range start'); ?></label>
                                    <input type="text" id="price_range_start" name="price_range_start" class="filter-input"  value="<?=!empty($this->input->post('price_range_start')) ? $this->input->post('price_range_start'):''?>">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="price_range_end"><?php echo _l('Price Range end'); ?></label>
                                    <input type="text" id="price_range_end" name="price_range_end" class="filter-input" value="<?=!empty($this->input->post('price_range_end')) ? $this->input->post('price_range_end'):''?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="start_date"><?php echo _l('From'); ?></label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" value=" <?=!empty($this->input->post('start_date')) ? $this->input->post('start_date '):''?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="end_date"><?php echo _l('To'); ?></label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" value=" <?=!empty($this->input->post('end_date')) ? $this->input->post('end_date '):''?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="type"><?php echo _l('Type'); ?></label>
                                    <select id="type" name="type" class="filter-input">
                                        <option value="">Select Type</option>
                                       <?php foreach ($types as $type): ?>
                                            <option value="<?php echo $type->name; ?>" <?=$this->input->post('type')==$type->name ?'selected':''?>><?php echo $type->name; ?>
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
                                            <option value="<?php echo $industrie['name']; ?>" <?=$this->input->post('industry_name')==$industrie['name'] ?'selected':''?>><?php echo $industrie['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
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

                        </div>
                        <div style="height: 40px; display: flex; justify-content: flex-end; gap: 10px;">
                        <input type="button" value="Clear Filters" class="btn btn-warning" onclick="resetForm();">
                        <input type="submit" value="Apply Filters" class="btn btn-info">
                    </div>


                    </form>
                    <hr class="hr-panel-heading">
                    <?php if (!empty($prospects)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered dt-table nowrap" id="purchased-prospects">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        
                                        <th><?php echo _l('Type'); ?></th>
                                        <th><?php echo _l('Acquisition Channels'); ?></th>
                                        <th><?php echo _l('Desired Amount'); ?></th>
                                        <th><?php echo _l('Industry'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($prospects as $prospect): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($prospect['prospect_name'] ?? ''); ?>
                                                <div class="row-options">
                                                    <a href="<?php echo site_url('prospects/prospect/' . $prospect['id']); ?>"
                                                        class="">
                                                        View
                                                    </a> |
                                                    <a href="<?php echo site_url('prospects/edit/' . $prospect['id']); ?>"
                                                        class="">
                                                        Edit
                                                    </a> |
                                                    <a href="<?php echo site_url('prospects/delete/' . $prospect['id']); ?>"
                                                        class=""
                                                        onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                        Delete
                                                    </a>
                                                </div>
                                            </td>
                                          
                                            
                                            <td><?php echo htmlspecialchars($prospect['type'] ?? ''); ?></td>
                                            
                                            <td><?php echo htmlspecialchars($prospect['acquisition_channel'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect['desired_amount'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect['industry'] ?? ''); ?></td>
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



<script>
  

    function resetForm() {
        // Reset form fields
        document.getElementById('filterForm').reset();
        
        // Reload the page without any filters (remove query parameters)
        window.location.href = window.location.pathname;
    }

</script>