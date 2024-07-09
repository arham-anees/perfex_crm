<?php defined('BASEPATH') or exit('No direct script access allowed');
// Means module is disabled
if (!function_exists('get_appointment_types')) {
    access_denied();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo hooks()->apply_filters('appointments_form_title', _l('appointment_create_new_appointment')); ?>
    </title>

    <?php app_external_form_header($form); ?>

    <link href="<?= module_dir_url('appointly', 'assets/css/appointments_external_form.css'); ?>" rel="stylesheet"
        type="text/css">
</head>

<body class="appointments-external-form" <?php if (is_rtl(true)) {
    echo ' dir="rtl"';
} ?>>
    <?php
    $clientUserData = $this->session->userdata();
    applyAdditionalCssStyles($clientUserData);
    ?>
    <div id="wrapper">
        <div id="content">
            <div class="container">

                <div id="response"></div>

                <?php echo form_open('appointly/appointments_public/create_external_appointment', ['id' => 'appointments-form']); ?>

                <input type="text" hidden name="rel_type" value="external">

                <div class="row main_wrapper">
                <div class=" <?= 'col-md-4'; ?>">
                    <div class="logo">
                    <img src="logo.png" alt="Pôle Démarches" style="width:100%;">
                    </div>
                    <hr>
                    <br>

                    <p>Pole Demarches</p>
                    <h3>Validation of Appointment by Video</h3>
                    <p><strong>Duration:</strong> 10 min</p>
                    <p>Online Conference Confirmation</p>
                    <p><strong>Cost:</strong> 89 EUR</p>
                    <p>2:20 - 3:20, Monday 1 July, 2024</p>
                    <p>Pacafic Time - USA and Canada</p>
                    <br>
                    <p><strong>Documents to Provide:</strong></p>
        <ul>
            <li>Identity Documents</li>
            <li>Proof of Residence</li>
            <li>Any document related to your situation</li>
        </ul>


               </div>

                    <div class="mbot20 <?= 'col-md-8'; ?>">

                        <div class="appointment-header"><?php hooks()->do_action('appointly_form_header'); ?></div>


                        <div>
                            <h4 ><?= _l('appointment_create_new_appointment'); ?></h4>
                        </div>

                        <br>
                        <div class="form-group">
                            <label for="name"><?= _l('appointment_name'); ?></label>
                            <input type="text" class="form-control"
                                value="<?= (isset($clientUserData['client_logged_in'])) ? get_contact_full_name($clientUserData['contact_user_id']) : ''; ?>"
                                name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label for="email"><?= _l('appointment_email'); ?></label>
                            <input type="email" class="form-control"
                                value="<?= (isset($clientUserData['client_logged_in'])) ? get_contact_detail($clientUserData['contact_user_id'], 'email') : ''; ?>"
                                name="email" id="email">
                        </div>

                        <?php echo render_textarea('description', 'Please share helpful material for meeting', '', ['rows' => 5]); ?>

                        <div class="form-group">
                        <label for="option1">What is purpose of appointment</label><br>
                        <input type="checkbox" name="option1" id="option1" value="Option 1">
                        <label class="grey-text" for="option1">Renewal of resident permit</label><br>
    
                        <input type="checkbox" name="option2" id="option2" value="Option 2">
                        <label class="grey-text" for="option2">Refusal of resident permit</label><br>
    
                        <input type="checkbox" name="option3" id="option3" value="Option 3">
                        <label class="grey-text" for="option3">Refugee</label><br>
    
                        <input type="checkbox" name="option4" id="option4" value="Option 4">
                        <label class="grey-text" for="option4">Family Reunion</label><br>
    
                        <input type="checkbox" name="option5" id="option5" value="Option 5">
                        <label class="grey-text" for="option5">Other</label><br>
                        </div>
                        

                        <?php $appointment_types = get_appointment_types();

                        if (count($appointment_types) > 0) { ?>
                            <div class="form-group appointment_type_holder">
                                <label for="appointment_select_type"
                                    class="control-label"><?= _l('appointments_type_heading'); ?></label>
                                <select class="form-control selectpicker" name="type_id" id="appointment_select_type">
                                    <option value=""><?= _l('dropdown_non_selected_tex'); ?></option>
                                    <?php foreach ($appointment_types as $app_type) { ?>
                                        <option class="form-control" data-color="<?= $app_type['color']; ?>"
                                            value="<?= $app_type['id']; ?>"><?= $app_type['type']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class=" clearfix mtop15"></div>
                        <?php } ?>



                        <div class="form-group">
                            <label for="phone"><?= _l('appointment_send_an_sms'); ?>
                                (Ex: <?= _l('appointment_your_phone_example'); ?>)</label>
                            <input type="text" class="form-control"
                                value="<?= (isset($clientUserData['client_logged_in'])) ? get_contact_detail($clientUserData['contact_user_id'], 'phonenumber') : ''; ?>"
                                name="phone" id="phone">
                        </div>

                        <label><?= _l('Payment Information'); ?></label>
                                        <br><br>
                                  <div class="border">  
                        <label><?= _l('Price'); ?></label><br>
                        <label class="grey-text"><?= _l('89 EUR'); ?></label>
                                        
                                <div class="form-group">
                                            
                                            <label
                                for="cardName"><?= _l('Name on Card'); ?></label>
                            <input type="text" class="form-control" value="" name="cardName" id="cardName">
                                    </div>
                            <div class="form-group">
                            <label
                                for="cardNumber"><?= _l('Card Number'); ?></label>
                            <input type="Number" class="form-control" value="" name="cardNumber" id="cardNumber">
                        </div>
                        <label class="grey-text"><?= _l('Your Payments are securely processed by stripe'); ?></label><br>
                        <label class="powered"><?= _l('Powered by stripe'); ?></label>
                        </div>

                    
                        <?php
                        $rel_cf_id = (isset($appointment) ? $appointment['apointment_id'] : false);
                        echo render_custom_fields('appointly', $rel_cf_id);
                        ?>
                        <?php if (
                            get_option('recaptcha_secret_key') != ''
                            && get_option('recaptcha_site_key') != ''
                            && get_option('appointly_appointments_recaptcha') == 1
                        ) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="g-recaptcha"
                                            data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                                        <div id="recaptcha_response_field" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="pull-right">
                            <button type="submit" id="form_submit"
                                class="btn btn-primary"><?php echo _l('appointment_submit'); ?></button>
                        </div>
                        <div class="clearfix mtop15"></div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <?php
    app_external_form_footer($form);
    ?>

    <?php if (isset($form)): ?>
        <script>
            app.locale = "<?= get_locale_key($form->language); ?>";
        </script>
    <?php endif; ?>

    <!-- Javascript functionality -->
    <?php require ('modules/appointly/assets/js/appointments_external_form.php'); ?>

    <!-- If callbacks is enabled load on appointments external form -->
    <?php if (get_option('callbacks_mode_enabled') == 1)
        require ('modules/appointly/views/forms/callbacks_form.php'); ?>

</body>

</html>