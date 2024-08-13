<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row main_row">
    <div class="col-md-12">
        <!-- Search bar and filters -->
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                <!DOCTYPE html>

    <h1 class="mt-5">Statistics</h1>
    
    <!-- Campaigns Table -->
    <div class="mb-4">
        <h2>Campaigns</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($campaigns)): ?>
                    <?php foreach ($campaigns as $campaign): ?>
                        <tr>
                            <td><?= htmlspecialchars($campaign['id']); ?></td>
                            <td><?= htmlspecialchars($campaign['name']); ?></td>
                            <td><?= htmlspecialchars($campaign['status']); ?></td>
                            <td><?= htmlspecialchars($campaign['start_date']); ?></td>
                            <td><?= htmlspecialchars($campaign['end_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No campaigns found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Prospects Table -->
    <div>
        <h2>Prospects</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Acquisition Channel</th>
                    <th>Industry</th>
                    <th>Confirm Status</th>
                    <th>Is Fake</th>
                    <th>Is Available for Sale</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($prospects)): ?>
                    <?php foreach ($prospects as $prospect): ?>
                        <tr>
                            <td><?= htmlspecialchars($prospect['id']); ?></td>
                            <td><?= htmlspecialchars($prospect['prospect_name']); ?></td>
                            <td><?= htmlspecialchars($prospect['status']); ?></td>
                            <td><?= htmlspecialchars($prospect['type']); ?></td>
                            <td><?= htmlspecialchars($prospect['category']); ?></td>
                            <td><?= htmlspecialchars($prospect['acquisition_channel']); ?></td>
                            <td><?= htmlspecialchars($prospect['industry']); ?></td>
                            <td><?= htmlspecialchars($prospect['confirm_status']); ?></td>
                            <td><?= $prospect['is_fake'] ? 'Yes' : 'No'; ?></td>
                            <td><?= $prospect['is_available_sale'] ? 'Yes' : 'No'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No prospects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>




                </div>
            </div>
        </div>
    </div>
</div>