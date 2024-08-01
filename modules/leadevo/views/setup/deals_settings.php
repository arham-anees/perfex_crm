<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $ratings =    ['0stars'=>get_option('delivery_settings_0stars'), 
                    '1stars'=>get_option('delivery_settings_1stars'), 
                    '2stars'=>get_option('delivery_settings_2stars'), 
                    '3stars'=>get_option('delivery_settings_3stars'), 
                    '4stars'=>get_option('delivery_settings_4stars'), 
                    '5stars'=>get_option('delivery_settings_5stars')]; 
$delivery_settings = get_option('delivery_settings');
$nonexclusive_status = 1;
$discount_type = 1;
?>

    <!-- Include CSRF Token -->
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
<div class="form-group">
    <label for="delivery_settings" class="control-label clearfix">
        <?= _l('leadevo_delivery_quality_apply'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_appointly_busy_times_enabled" name="nonexclusive_status" value="1" <?= ($nonexclusive_status == '1') ? ' checked' : '' ?>>
        <label for="y_opt_1_appointly_busy_times_enabled"><?= _l('settings_yes'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_appointly_busy_times_enabled" name="nonexclusive_status" value="0" <?= ($nonexclusive_status == '0') ? ' checked' : '' ?>>
        <label for="y_opt_2_appointly_busy_times_enabled">
            <?= _l('settings_no'); ?>
        </label>
    </div>
</div>
<?php echo render_input('max_sell_time',_l('leadevo_deals_max_sell_time'), $ratings['0stars'],'number') ?> 
<?php echo render_input('days_to_discount',_l('leadevo_deals_days_to_discount'), $ratings['1stars'],'number') ?> 
<?php echo render_input('discount_type',_l('leadevo_deals_discount_type'), $ratings['2stars'],'number') ?> 
<div class="form-group">
    <label for="delivery_settings" class="control-label clearfix">
        <?= _l('leadevo_deals_discount_type'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_appointly_busy_times_enabled" name="discount_type" value="1" <?= ($discount_type == '1') ? ' checked' : '' ?>>
        <label for="y_opt_1_appointly_busy_times_enabled"><?= _l('leadevo_exclusive_deal'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_appointly_busy_times_enabled" name="discount_type" value="0" <?= ($discount_type == '0') ? ' checked' : '' ?>>
        <label for="y_opt_2_appointly_busy_times_enabled">
            <?= _l('leadevo_nonexclusive_deal'); ?>
        </label>
    </div>
</div>
<?php echo render_input('discount_amount',_l('leadevo_deals_discount_amount'), $ratings['3stars'],'number') ?> 
                 
           
<script>

    document.addEventListener('DOMContentLoaded', function() {
        $('#settings-form').on('submit', function(event) {
            event.preventDefault(); // Prevent default form submission

            var sum = 0;
            var formData = $(this).serializeArray();
            var data = {};

            $.each(formData, function(index, field) {
                if(field.name!='csrf_token_name' && field.name !='delivery_settings'){
                    var value = parseFloat(field.value) || 0;
                    sum += value;
                    data[field.name] = value;
                }else{
                    data[field.name] = field.value;
                }
            });
            if (sum === 100) {
            // Assuming admin_url is defined and accessible
            $.ajax({
                url: admin_url + 'leadevo/settings?group=leadevo-settings',
                type: 'POST',
                data: data,
                success: function(response) {
                    // Handle success response
                    alert_float('success', '<?=_l('settings_updated')?>');
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                    alert_float('danger', 'Failed to save settings');
                }
            });
            } else {
                alert_float('danger','Sum must be 100');
            }
        });
    });

</script>


