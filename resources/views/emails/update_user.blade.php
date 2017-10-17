<body>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $dear_email;?> <?php echo $name.' '.$last_name;?>,</p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $success_update_email;?>.</p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;">
      <?php echo $your_information_email;?>:
    </p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;">
        <?php echo $email_email;?>: <b><?php echo $email;?></b>
    </p>
    <?php
    if(isset($raw_password)){
    ?>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;">
        <?php echo $password_email;?>: <b><?php echo $raw_password;?></b>
    </p>
    <?php
    }
    ?>
    <?php
    if(isset($number_phones)){
    ?>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;">
        <?php echo $number_phones_email;?>: <b><?php echo $number_phones;?></b>
    </p>
    <?php
    }
    ?>
    <div><p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $regards_email;?>.</p></div>
</body>
