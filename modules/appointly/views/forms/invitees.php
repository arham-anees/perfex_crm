<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
    .container {
            height: inherit;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div>
        
    </div>
    <div class="container">
        <h1><i class="fa-solid fa-circle-check"></i> You are scheduled for <?php echo $appointment['name']; ?></h1>
        <p>A calendar invitation has been sent to your email address.</p>
        <div class="event-details">
            <h2 style="color:black"><strong><?php echo $appointment['subject']; ?></strong></h2>
            <!-- <p style="color:black"><?php echo $appointment['description']; ?></p> -->
             <?php if(isset($appointment['attendee'])) { ?>
            <div><i class="fas fa-user"></i><?php echo $appointment['attendee']; ?></div>
            <?php }?>
            <!-- <div><i class="fas fa-calendar-alt"></i><?php echo $startTime; ?> - <?php echo $endTime; ?>, <?php echo $date; ?></div> -->
            <?php foreach ($appointment['dates'] as $date): ?>
                <div><i class="fas fa-calendar-alt"></i><?php echo $date; ?></div>
            <?php endforeach; ?>
            <!-- <div><i class="fas fa-globe"></i><?php echo $timezone; ?></div> -->
            <?php foreach ($hashes as $hash): ?>
                <a href="<?= site_url('/appointly/appointments_public/client_hash?hash='. $hash['hash'])?>" >Visit Appointment</a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
