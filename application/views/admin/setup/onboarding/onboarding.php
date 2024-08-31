<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading" />

                        <form method="POST" action="<?php echo admin_url('leadevo/onboarding_manager/save'); ?>">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>

                            <?php for ($step = 1; $step <= 6; $step++): ?>
                                <div class="step-container">
                                    <h5>Step <?php echo $step; ?></h5>
                                    <div class="item-row">
                                        <input type="text" name="step<?php echo $step; ?>_step_title" class="form-control"
                                            placeholder="Enter Step Title" required
                                            value="<?php echo isset($onboarding_steps[$step - 1]['step_title']) ? $onboarding_steps[$step - 1]['step_title'] : ''; ?>">
                                        <textarea name="step<?php echo $step; ?>_step_content" class="form-control mt-2"
                                            rows="3" placeholder="Enter Step Content"
                                            required><?php echo isset($onboarding_steps[$step - 1]['step_content']) ? $onboarding_steps[$step - 1]['step_content'] : ''; ?></textarea>

                                        <!-- Dropdown for selecting content type (Video or Link) -->
                                        <select name="step<?php echo $step; ?>_type" class="form-control mt-2">
                                            <option value="video" <?php echo isset($onboarding_steps[$step - 1]['type']) && $onboarding_steps[$step - 1]['type'] == 'video' ? 'selected' : ''; ?>>Video
                                            </option>
                                            <option value="link" <?php echo isset($onboarding_steps[$step - 1]['type']) && $onboarding_steps[$step - 1]['type'] == 'link' ? 'selected' : ''; ?>>Link
                                            </option>
                                        </select>

                                        <input type="text" name="step<?php echo $step; ?>_content" class="form-control mt-2"
                                            placeholder="Enter Content or Link" required
                                            value="<?php echo isset($onboarding_steps[$step - 1]['content']) ? $onboarding_steps[$step - 1]['content'] : ''; ?>">
                                    </div>
                                    <hr>
                                </div>
                            <?php endfor; ?>

                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .step-container {
        margin-bottom: 20px;
    }

    .step-container h5 {
        margin-bottom: 15px;
        font-weight: bold;
        color: #333;
    }

    .item-row {
        margin-bottom: 20px;
    }

    .item-row input,
    .item-row textarea,
    .item-row select {
        margin-bottom: 10px;
        flex: 1;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 10px 20px;
        border-radius: 3px;
        cursor: pointer;
    }

    .btn-success:hover {
        background-color: #218838;
    }
</style>
<script>
    // No additional JavaScript needed since the dynamic item addition is removed.
</script>

<?php init_tail(); ?>