<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $ratings =    ['0stars'=>get_option('delivery_settings_0stars'), 
                    '1stars'=>get_option('delivery_settings_1stars'), 
                    '2stars'=>get_option('delivery_settings_2stars'), 
                    '3stars'=>get_option('delivery_settings_3stars'), 
                    '4stars'=>get_option('delivery_settings_4stars'), 
                    '5stars'=>get_option('delivery_settings_5stars')]; 
$delivery_settings = get_option('delivery_settings')
?>

    <!-- Include CSRF Token -->
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
<div class="form-group">
    <label for="delivery_settings" class="control-label clearfix">
        <?= _l('leadevo_delivery_quality_apply'); ?>
    </label>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_1_appointly_busy_times_enabled" name="delivery_settings" value="1" <?= ($delivery_settings == '1') ? ' checked' : '' ?>>
        <label for="y_opt_1_appointly_busy_times_enabled"><?= _l('settings_yes'); ?></label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="y_opt_2_appointly_busy_times_enabled" name="delivery_settings" value="0" <?= ($delivery_settings == '0') ? ' checked' : '' ?>>
        <label for="y_opt_2_appointly_busy_times_enabled">
            <?= _l('settings_no'); ?>
        </label>
    </div>
</div>
<?php echo render_input('delivery_settings_0stars',_l('leadevo_delivery_quality_0stars'), $ratings['0stars'],'number') ?> 
<?php echo render_input('delivery_settings_1stars',_l('leadevo_delivery_quality_1stars'), $ratings['1stars'],'number') ?> 
<?php echo render_input('delivery_settings_2stars',_l('leadevo_delivery_quality_2stars'), $ratings['2stars'],'number') ?> 
<?php echo render_input('delivery_settings_3stars',_l('leadevo_delivery_quality_3stars'), $ratings['3stars'],'number') ?> 
<?php echo render_input('delivery_settings_4stars',_l('leadevo_delivery_quality_4stars'), $ratings['4stars'],'number') ?> 
<?php echo render_input('delivery_settings_5stars',_l('leadevo_delivery_quality_5stars'), $ratings['5stars'],'number') ?> 
                 
           
<script>

    document.addEventListener('DOMContentLoaded', function() {
           // Function to toggle star fields visibility
        function toggleStarFields() {
            if ($('#y_opt_2_appointly_busy_times_enabled').is(':checked')) {
                // Hide star fields when "No" is selected
                $('[app-field-wrapper^="delivery_settings_"]').hide();
            } else {
                // Show star fields when "Yes" is selected
                $('[app-field-wrapper^="delivery_settings_"]').show();
            }
        }

        // Initially call the function to set correct visibility on page load
        toggleStarFields();

        // Attach change event to radio buttons
        $('input[name="delivery_settings"]').on('change', function() {
            toggleStarFields();
        });
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


