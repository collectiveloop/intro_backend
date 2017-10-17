<body>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $remember_send_email;?> <?php echo $name.' '.$last_name;?>,</p>
    <p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;">
      <a href="{{url('administration/remember/<?php echo $language.'/'.$remember_token;?>')}}"><?php echo $click_remember;?></a>.</p>

    <div><p style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px;"><?php echo $regards_email;?>.</p></div>
</body>
