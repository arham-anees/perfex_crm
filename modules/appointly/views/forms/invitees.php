<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        #wrapper{
            margin:30px
        }
        #content{
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-height: 80vh;
        }
        .container {
            height: inherit;
            text-align: center;
            margin-bottom: 20px;
        }
        .container h1 {
            color: black;
        }
        .event-details {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: white;
            display: inline-block;
            text-align: left;
            min-width:350px;
        }
        
        .event-details div {
            margin-bottom: 10px;
            color: #737373;
            font-size: 16px;
            font-weight: 600;
        }
        .event-details i {
            margin-right: 10px;
        }
        .fa-circle-check{
            color: #028164;
            font-size: 24px;
        }

        @media (max-width: 426px) {
            .event-details {
                min-width: unset;
            }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="content">
            <div class="container">
                <div style="min-height:80vh;">
                    <h1 style="display: flex;
                        justify-content: center;
                        align-items: center;"><i class="fa-solid fa-circle-check"></i><?php echo _l('booking_page_thank_scheduled') ?>
                    </h1>
                    <p><?= _l('booking_page_thanks_email_inviation') ?></p>
                    <div style="display:block;width:fit-content;margin:auto">
                        <div class="event-details">
                        <h2 style="color:black; margin-top:0;margin-bottom:10px"><strong><?php echo $appointment['name']; ?></strong></h2>
                        <!-- <p style="color:black"><?php echo $appointment['description']; ?></p> -->
                        <?php if(isset($appointment['subject'])) { ?>
                            <div><i class="fas fa-pen-alt"></i><?php echo $appointment['subject']; ?></div>
                        <?php }?>
                        <?php if(isset($appointment['attendee'])) { ?>
                            <div><i class="fas fa-user"></i><?php echo $appointment['attendee']; ?></div>
                        <?php }?>

                        <div><i class="fas fa-calendar-alt"></i><?php echo $hashDate; ?></div>
                        <!-- <?php foreach ($appointment['dates'] as $date): ?>
                            <div><i class="fas fa-calendar-alt"></i><?php echo $date; ?></div>
                        <?php endforeach; ?> -->

                        <!-- <div><i class="fas fa-globe"></i><?php echo $timezone; ?></div> -->
                    
                    </div>
                    <div style="display:block; text-align:left;margin-top:30px">
                        <?php foreach ($hashes as $hash): ?>
                            <a style="text-decoration:none" href="<?= site_url('/appointly/appointments_public/client_hash?hash='. $hash['hash'])?>" ><i class="fa-solid fa-arrow-right-long"></i> &nbsp;Visit Appointment</a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div style="text-align:left">Cookies Settings</div>
        </div>
    </div>
</body>
</html>
