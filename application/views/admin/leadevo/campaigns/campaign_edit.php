<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo _l('Edit Campaign'); ?></h4>
                        <hr class="hr-panel-heading" />
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                        <?php endif; ?>
                        <?php echo form_open(admin_url('campaigns/edit/' . $campaign->id), ['id' => 'campaign-form']); ?>
                        <div class="form-group">
                            <label for="name"><?php echo _l('Name'); ?></label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="<?php echo $campaign->name??''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description"><?php echo _l('Description'); ?></label>
                            <textarea id="description" name="description" class="form-control"
                                required><?php echo $campaign->description??''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="start_date"><?php echo _l('Start Date'); ?></label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="<?php echo !empty($campaign->start_date) && strtotime($campaign->start_date) !== false 
                                    ? date('d M Y', strtotime($campaign->start_date)) 
                                    : 'N/A'; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date"><?php echo _l('End Date'); ?></label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="<?php echo!empty($campaign->end_date) && strtotime($campaign->end_date) !== false 
                                    ? date('d M Y', strtotime($campaign->end_date)) 
                                    : 'N/A'; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="industry_id"><?php echo _l('Industry'); ?></label>
                            <select name="industry_id" id="industry_id" class="form-control" required>
                                <?php foreach ($industries as $industry): ?>
                                    <option value="<?php echo $industry['id']; ?>"><?php echo $industry['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="budget"><?php echo _l('Budget'); ?></label>
                            <input type="number" id="budget" name="budget" class="form-control"
                                value="<?php echo $campaign->budget??''; ?>" required>
                        </div>
                        <div class="form-group">

                   
                </div>
                        <button type="submit" class="btn btn-primary"><?php echo _l('Save Changes'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('campaign-form').addEventListener('submit', function (event) {
        var startDate = new Date(document.getElementById('start_date').value);
        var endDate = new Date(document.getElementById('end_date').value);
        var currentDate = new Date();

        if (startDate < currentDate) {
            alert('Start date cannot be before the current date.');
            event.preventDefault();
        } else if (endDate < startDate) {
            alert('End date cannot be before the start date.');
            event.preventDefault();
        } else if (endDate < currentDate) {
            alert('End date cannot be before the current date.');
            event.preventDefault();
        }
    });
</script>

<?php init_tail(); ?>
</body>

</html>