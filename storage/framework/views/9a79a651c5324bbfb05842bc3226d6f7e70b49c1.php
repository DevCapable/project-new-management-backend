
Hello <?php echo e($emailData['name']); ?>

<br><br>
Welcome to
<b style="color: green"><?php echo e(env('APP_NAME')); ?></b>
<br>
Please click the below link to verify your email and activate your account!
<br><br>
<a href="http://127.0.0.1:8000/verify?code=<?php echo e($emailData['verification_code']); ?>">Click Here!</a>

<br><br>
Thank you!
<br>
still under dev




<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/email/registration/_client_reg_email.blade.php ENDPATH**/ ?>