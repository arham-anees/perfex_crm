<div class="container">
    <h2>CRM Links</h2>

    <a href="<?= site_url('crm/create'); ?>" class="btn btn-primary">Add New Link</a>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Link</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($links as $link): ?>
                <tr>
                    <td><?= $link['id']; ?></td>
                    <td><?= $link['links']; ?>
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
                                            </div></td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
