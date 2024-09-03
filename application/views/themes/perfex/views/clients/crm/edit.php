<div class="container">
    <h2>Edit CRM Link</h2>

    <?php echo form_open('crm/edit/' . $link['id']); ?>
    
    <div class="form-group">
        <label for="links">Link:</label>
        <input type="text" class="form-control" id="links" name="links" value="<?php echo set_value('links', $link['links']); ?>" required>
        <?php echo form_error('links', '<small class="text-danger">', '</small>'); ?>
    </div>

    <button type="submit" class="btn btn-primary">Update Link</button>
    <a href="<?= site_url('crm'); ?>" class="btn btn-secondary">Cancel</a>

    <?php echo form_close(); ?>
</div>
