<link rel='stylesheet' href='<?php echo KANZU_MAKSPH_DIR; ?>assets/css/styles.css' type='text/css' />
<div class="membership-activate-account">
    <div class="account-container">
        <div class="header" style="border-bottom: 1px solid #ddd; padding: 20px 0; text-align: center;">
            <strong style="font-size: 20px;">{{$site_name}} - Membership Account</strong>
        </div>
        <div class="content" style="padding: 10px 0 40px;">
            <p>Hello,<br /> Thank you for creating a membership account!</p>
            <h4><em><span style="color: #333333; font-family: Arial, sans-serif;"><?php echo __('Please activate your account by clicking the link below', 'kanzucode'); ?></span></em></h4>
            <p><a class="btn" style="color: #fff; border-radius: 3px; text-decoration: none; background-color: #51b3ff; padding: 10px 20px; font-size: 14px; font-family: Arial, sans-serif;" href="{{$password_reset_link}}"><?php echo __('Activate account', 'kanzucode'); ?></a></p>
        </div>
        <div class="footer" style="border-top: 1px solid #ddd; padding: 20px 0; clear: both; text-align: center;"><small style="font-size: 11px;">{{$site_name}} - {{$site_description}}</small></div>
    </div>
</div>