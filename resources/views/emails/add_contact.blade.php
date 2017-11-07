<body>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $dear_email;?> <?php echo $full_name;?>,</p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $first_name.' '.$last_name.' '.$content_invitation;?> </p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;">
      <a href="<?php echo url('invitations-link');?>"><?php echo $link;?></a>
      <div>
        <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $regards_email;?>.</p>
      </div>
    </p>
</body>
