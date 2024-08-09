<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>

<!DOCTYPE html>
<html lang="<?php echo e($locale); ?>" dir="<?php echo ($isRTL == 'true') ? 'rtl' : 'ltr' ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo isset($title) ? $title : get_option('companyname'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?= site_url() .'/assets/css/style.css'?>"/>


</head>

<body <?php echo admin_body_class(isset($bodyclass) ? $bodyclass : ''); ?>>
<?php hooks()->do_action('after_body_start'); ?>