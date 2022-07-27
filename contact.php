<?php
include "core/init.php";
$name= ((isset($_POST['name']) && $_POST['name'] != '')?sanitize($_POST['name']):'');
$email= ((isset($_POST['email']) && $_POST['email'] != '')?sanitize($_POST['email']):'');
$message = ((isset($_POST['message']) && $_POST['message'] != '')?sanitize($_POST['message']):'');
$msg='';
//echo !extension_loaded('openssl')?"Not Available":"Available";
//for users
if(isset($_POST['submit'])){
   
    require 'PHPMailerAutoload.php';

    $mail = new PHPMailer;

    $mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = SMTP_USERS;                 // SMTP username
    $mail->Password = SMTP_PASS;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom(SMTP_USERS, 'Contact page');
    $mail->addAddress($email);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo(SMTP_USERS, 'Contact page');
    //$mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'Contact Page';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = $message;

        if (!$mail->send()) {
            echo '<p id="flash" style="text-align:center; font-weight:bolder; font-size:16px;" class="alert alert-danger alert-dismissible fade in alert-edit"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Sorry, something went wrong. Please try again later.</p>.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            $_SESSION['success_flash']= 'Message sent! Thanks for contacting us.';
            //header("Location: index.php");
        } 
}
    
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="pop">
            <span>✖</span>
            <?php
				echo $msg;
			?>
            <form id="contact" action="contact.php" method="post">
                <div class="row">
                    <div class="col-md-12">
                    <fieldset>
                        <input name="name" type="text" class="form-control" id="name" value="<?=$name;?>" placeholder="Your name..." required>
                    </fieldset>
                    </div>
                    <div class="col-md-12">
                    <fieldset>
                        <input name="email" type="email" class="form-control" id="email" value="<?=$email;?>" placeholder="Your email..." required>
                    </fieldset>
                    </div>
                    <div class="col-md-12">
                    <fieldset>
                        <textarea name="message" rows="6" class="form-control" id="message" placeholder="Your message..." required><?=$message;?></textarea>
                    </fieldset>
                    </div>
                    <div class="col-md-12">
                    <fieldset>
                        <button type="submit" name="submit" id="form-submit" class="btn">Send Message</button>
                    </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>