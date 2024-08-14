<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header d-flex">
            <div></div>
            <h4 class="modal-title w-100"><?php echo _l('leadevo_sale_available_prospect'); ?></h4>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
            <?php echo form_open('#', ['id' => 'sale-prospect-form']); ?>
            <input type="hidden" name="id" />

            <div class="form-group">
                <label for="" class="control-label clearfix"><?= _l('deal') ?>
                </label>
                <div class="radio radio-primary">
                    <input type="radio" name="deal" id="deal_non_exclusive" value="0" checked>
                    <label for="deal_non_exclusive"><?php echo _l('leadevo_nonexclusive_deal'); ?><span
                            class="info-icon" data-tooltip="<?php echo get_information('exclusive'); ?>">
                            <i class="fa fa-info-circle" style="font-size:15px"></i>
                        </span></label>
                </div>

                <div class="radio radio-primary">
                    <input type="radio" name="deal" id="deal_exclusive" value="1">
                    <label for="deal_exclusive"><?php echo _l('leadevo_exclusive_deal'); ?><span class="info-icon"
                            data-tooltip="<?php echo get_information('non_exclusive'); ?>">
                            <i class="fa fa-info-circle" style="font-size:15px"></i>
                        </span></label>
                </div>
            </div>

            <div class="form-group">
                <label for="desired_amount"><?php echo _l('Desired Amount'); ?></label>
                <input type="text" class="form-control" name="desired_amount" id="desired_amount" required>
            </div>

            <div class="form-group">
                <label for="min_amount"><?php echo _l('Minimum Amount'); ?></label>
                <input type="text" class="form-control" name="min_amount" id="min_amount" required>
            </div>

            <!-- Submit Button -->
            <input type="button" value="<?php echo _l('cancel'); ?>" class="btn btn-danger" data-dismiss="modal"
                aria-label="Close" />
            <input type="submit" value="<?php echo _l('submit'); ?>" class="btn btn-primary pull-right" />

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('sale-prospect-form').addEventListener('submit', function (event) {
            event.preventDefault();
            putProspectToSale();
            // Prevent the default form submission if needed
        });

        function putProspectToSale() {
            // get data 
            let id = $('#mark_sale_available_modal input[name=id]').val();
            let deal = $('#mark_sale_available_modal input[name=deal]').val();
            let desired_amount = $('#mark_sale_available_modal input[name=desired_amount]').val();
            let min_amount = $('#mark_sale_available_modal input[name=min_amount]').val();
            console.log(id, deal, desired_amount, min_amount, min_amount > desired_amount);
            if (isNaN(desired_amount) || isNaN(min_amount)) {
                alert_float('danger', 'Please enter valid numeric values');
                return false;
            }
            min_amount = parseFloat(min_amount);
            desired_amount = parseFloat(desired_amount);
            if (min_amount > desired_amount) {
                alert_float('danger', 'Please enter valid desired and minimum price. Desired amount must be greater than or equal to minimum amount');
                return false;
            }
            $.ajax({
                url: admin_url + 'prospects/mark_as_available_sale',
                data: {
                    id, deal, desired_amount, min_amount
                },
                type: 'POST',
                success: (res) => {
                    try {
                        res = JSON.parse(res);
                        if (res.status == 'success') {
                            alert_float('success', res.message);
                            $('#mark_sale_available_modal').modal('hide');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                        else {
                            alert_float('danger', 'Failed to put the prospect to marketplace');
                        }
                    }
                    catch (e) {
                        alert_float('danger', 'Failed to put the prospect to marketplace');

                    }
                },
                error: (err) => {
                    alert_float('danger', 'Failed to put the prospect to marketplace');
                }
            })
            return false;
        }
    });
</script>