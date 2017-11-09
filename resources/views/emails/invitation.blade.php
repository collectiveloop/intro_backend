<body>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $dear_email;?> <?php echo $full_name;?>,</p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $contact_name.' '.$success_invitation_email.$friend_email.','.$success_invitation_final_email;?> </p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;">
      <a href="<?php echo url('intros-link');?>"><?php echo $accept_invitation_link;?></a>
      <div>
        <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $regards_email;?>.</p>
      </div>
    </p>
</body>
