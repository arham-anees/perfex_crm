<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">
                <h4 class="no-margin"><?php echo _l('Edit Prospect'); ?></h4>
                <hr class="hr-panel-heading" />
                <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                <?php echo form_open('prospects/edit/' . $prospect->id, array('id' => 'prospect-form')); ?>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name"><?php echo _l('First Name'); ?></label>
                            <input type="text" class="form-control" name="first_name" id="first_name"
                                value="<?php echo $prospect->first_name ?? 'N/A'; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="last_name"><?php echo _l('Last Name'); ?></label>
                            <input type="text" class="form-control" name="last_name" id="last_name"
                                value="<?php echo $prospect->last_name ?? 'N/A'; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone"><?php echo _l('Phone'); ?></label>
                            <input type="text" class="form-control" name="phone" id="phone"
                                value="<?php echo $prospect->phone ?? 'N/A'; ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email"><?php echo _l('Email'); ?></label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="<?php echo $prospect->email ?? 'N/A'; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="source_id"><?php echo _l('leadevo_prospect_source'); ?></label>
                            <select name="source_id" id="source_id" class="form-control" required>
                                <?php foreach ($sources as $source): ?>
                                    <option value="<?php echo $source['id']; ?>" <?php echo $source['id'] == $prospect->source_id ? 'selected' : ''; ?>>
                                        <?php echo $source['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type_id"><?php echo _l('Type'); ?></label>
                            <select name="type_id" id="type_id" class="form-control" required>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?php echo $type->id; ?>" <?php echo $type->id == $prospect->type_id ? 'selected' : ''; ?>>
                                        <?php echo $type->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="acquisition_channel_id"><?php echo _l('Acquisition Channel'); ?></label>
                            <select name="acquisition_channel_id" id="acquisition_channel_id" class="form-control"
                                required>
                                <?php foreach ($acquisition_channels as $channel): ?>
                                    <option value="<?php echo $channel->id; ?>" <?php echo $channel->id == $prospect->acquisition_channel_id ? 'selected' : ''; ?>>
                                        <?php echo $channel->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="industry_id"><?php echo _l('Industry'); ?></label>
                            <select name="industry_id" id="industry_id" class="form-control" required>
                                <?php foreach ($industries as $industry): ?>
                                    <option value="<?php echo $industry['id']; ?>" <?php echo $industry['id'] == $prospect->industry_id ? 'selected' : ''; ?>>
                                        <?php echo $industry['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="desired_amount"><?php echo _l('Desired Amount'); ?></label>
                            <input type="text" class="form-control" name="desired_amount" id="desired_amount"
                                value="<?php echo $prospect->desired_amount ?? 'N/A'; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="min_amount"><?php echo _l('Minimum Amount'); ?></label>
                            <input type="text" class="form-control" name="min_amount" id="min_amount"
                                value="<?php echo $prospect->min_amount ?? 'N/A'; ?>" required>
                        </div>
                    </div>
                </div> -->

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo _l('Update Prospect'); ?></button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>