<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="row main_row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">

<div class="container">
    <h2>CRM Links</h2>

    <div class="_buttons">
                    <a data-toggle="modal" href="<?= site_url('crm/create') ?>"
                        class="btn btn-primary pull-left display-block mleft10">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('leadevo_crm_new_link'); ?>
                    </a>
                    <div class="clearfix"></div>
                </div>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Link</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($links as $link): ?>
                <tr>
                    <td><?= $link['id']; ?></td>
                    <td><?= $link['name']; ?>
                    <div class="row-options">
                                            <a href="<?php echo site_url('crm/details/' . $link['id']); ?>" class="">
                                                    view
                                                </a> |
                                                <a href="<?php echo site_url('crm/edit/' . $link['id']); ?>" class="">
                                                    Edit
                                                </a> |
                                                <a href="<?php echo site_url('crm/delete/' . $link['id']); ?>"
                                                    class="text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this prospect?');">
                                                    Delete
                                                </a>
                                            </div>
                </td>
                    <td><?= $link['description']; ?></td>
                    <td><?= $link['links']; ?></td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
            </div>
            </div>