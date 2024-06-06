<link rel='stylesheet' href='<?php echo NJHM_DIR; ?>assets/css/styles.css' type='text/css' />
<form class="membership-activate-account-form email-form">
    <div class="activated-account-container">
        <div class="header" style="border-bottom: 1px solid #ddd; padding: 20px 0; text-align: center;">
            <strong style="font-size: 20px;">{{$site_name}} - Membership Account</strong>
        </div>
        <div class="content" style="padding: 10px 20px 40px;">
            <br />
            <p>Hello, <br /> Thank you for activating your membership account! </p>
            <p><strong>Username:</strong> {{$username}} <br /></p>
            <p><strong>Email:</strong> {{$email}} <br /></p>
            <p><a class="btn" style="color: #fff; border-radius: 3px; text-decoration: none; background-color: #51b3ff; padding: 10px 20px; font-size: 14px; font-family: Arial, sans-serif;" href="{{$login_url}}"><?php echo __('Click here to login', 'kanzucode'); ?></a>
            </p>
            <p style='font-weight:500;'>Having problems accessing your account!<br /> Please reach out on: {{$admin_email}}</p>
        </div>
        <div class="footer" style="border-top: 1px solid #ddd; padding: 20px 0; clear: both; text-align: center;"><small style="font-size: 11px;">{{$site_name}} - {{$site_description}}</small></div>
    </div>
</form>