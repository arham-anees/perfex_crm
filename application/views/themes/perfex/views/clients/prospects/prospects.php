<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
function displayStars($rating, $maxStars = 5)
{
    if ($rating == 0 || $rating == '') {
        echo '-';
        return;
    }
    for ($i = 1; $i <= $maxStars; $i++) {
        echo '<span class="star' . ($i <= $rating ? ' filled' : '') . '">&#9733;</span>';
    }
}
?>
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
                <div class="col-md-8" style="display:flex;justify-content:end">
                    <form method="GET" action="<?php echo site_url('prospects'); ?>" style="margin-right: 10px;">
                        <div class="input-group" style="width:200px">
                            <input type="text" name="search" class="form-control"
                                placeholder="<?php echo _l('Search Prospects'); ?>"
                                value="<?php echo isset($search) ? $search : ''; ?>">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <form method="GET" action="<?php echo site_url('prospects'); ?>">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <?php echo _l('Filter By'); ?> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                <li><a
                                        href="<?php echo site_url('prospects?filter=active'); ?>"><?php echo _l('Active Prospects'); ?></a>
                                </li>
                                <li><a
                                        href="<?php echo site_url('prospects?filter=inactive'); ?>"><?php echo _l('Inactive Prospects'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Prospect Table -->
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <?php if (!empty($prospects)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered dt-table nowrap" id="purchased-prospects">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('Name'); ?></th>
                                        <th><?php echo _l('Status'); ?></th>
                                        <th><?php echo _l('Stars'); ?></th>
                                        <th><?php echo _l('Type'); ?></th>
                                        <th><?php echo _l('Category'); ?></th>
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
                                                    </a> |
                                                    <a href="#" onclick="openRateModal(<?= $prospect['id'] ?>)">Rate</a>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($prospect['status'] ?? ''); ?></td>
                                            <td>
                                                    <div class="star-rating">
                                                        <?php


                                                        // Example usage
                                                        $userRating = $prospect['rating'] ?? 0; // This value could come from a database
                                                        displayStars($userRating);
                                                        ?>
                                                    </div>
                                                </td>
                                            <td><?php echo htmlspecialchars($prospect['type'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($prospect['category'] ?? ''); ?></td>
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

<div id="rating_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <!-- Modal Header -->
            <div class="modal-header d-flex">
                <div></div>
                <h4 class="modal-title w-100"><?php echo _l('leadevo_prospect_ratings_title'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <!-- Font Awesome Close Icon -->
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <?php echo form_open(site_url('prospects/rate'), ['id' => 'rate-prospect-form']); ?>
                <input type="hidden" name="id" />
                <div class="form-group">
                    <label for="nonexclusive_status" class="control-label clearfix">
                        <?= _l('leadevo_prospect_ratings_description'); ?>
                    </label>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_1stars" name="rating" value="1" ?>>
                        <label for="prospect_rating_1stars"><?= _l('leadevo_delivery_quality_1stars'); ?></label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_2stars" name="rating" value="2" ?>>
                        <label for="prospect_rating_2stars">
                            <?= _l('leadevo_delivery_quality_2stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_3stars" name="rating" value="3" ?>>
                        <label for="prospect_rating_3stars">
                            <?= _l('leadevo_delivery_quality_3stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_4stars" name="rating" value="4" ?>>
                        <label for="prospect_rating_4stars">
                            <?= _l('leadevo_delivery_quality_4stars'); ?>
                        </label>
                    </div>
                    <div class="radio radio-primary radio-inline">
                        <input type="radio" id="prospect_rating_5stars" name="rating" value="5" ?>>
                        <label for="prospect_rating_5stars">
                            <?= _l('leadevo_delivery_quality_5stars'); ?>
                        </label>
                    </div>
                </div>
                <!-- Submit Button -->
                <input type="submit" value="<?php echo _l('submit'); ?>" class="btn btn-primary" />

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('#purchased-prospects').DataTable();
    function openRateModal(id) {
        document.querySelector('#rating_modal input[name=id]').value = id;
        console.log(id);
        
        $('#rating_modal').modal('show');
    }
</script>