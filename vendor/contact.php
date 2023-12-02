<html>
  <head>
<?php
// Google reCaptcha secret key
$secretKey  = "6Lcs0yMpAAAAAISqhpWGEu7aFYDLErXNlYF-oqSy";

$statusMsg = '';

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
 require 'autoload.php';

if(isset($_POST['submit'])){

    if(isset($_POST['captcha-response']) && !empty($_POST['captcha-response'])){
    
        // Get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['captcha-response']);
        $responseData = json_decode($verifyResponse);
        if($responseData->success){
            //Contact form submission code goes here ...  
            $statusMsg = 'Your contact request have submitted successfully.';
            
//Email Verification Starts HERE
function tep_validate_ip_address($ip_address) {
    if (function_exists('filter_var') && defined('FILTER_VALIDATE_IP')) {
      return filter_var($ip_address, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4));
    }
    if (preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/', $ip_address)) {
      $parts = explode('.', $ip_address);
      foreach ($parts as $ip_parts) {
        if ( (intval($ip_parts) > 255) || (intval($ip_parts) < 0) ) {
          return false; // number is not within 0-255
        }
      }
      return true;
    }
    return false;
  }
  function tep_get_ip_address() {
    $ip_address = null;
    $ip_addresses = array();
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      foreach ( array_reverse(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) as $x_ip ) {
        $x_ip = trim($x_ip);
        if (tep_validate_ip_address($x_ip)) {
          $ip_addresses[] = $x_ip;
        }
      }
    }
    if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip_addresses[] = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
      $ip_addresses[] = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }
    if (isset($_SERVER['HTTP_PROXY_USER']) && !empty($_SERVER['HTTP_PROXY_USER'])) {
      $ip_addresses[] = $_SERVER['HTTP_PROXY_USER'];
    }
    $ip_addresses[] = $_SERVER['REMOTE_ADDR'];
    foreach ( $ip_addresses as $ip ) {
      if (!empty($ip) && tep_validate_ip_address($ip)) {
        $ip_address = $ip;
        break;
      }
    }
    return $ip_address;
  }  
  function tep_validate_email($idemail) {
  define('ENTRY_EMAIL_ADDRESS_CHECK', 'true');
    $idemail = trim($idemail);
    if ( strlen($idemail) > 255 ) {
      $valid_address = false;
    } elseif ( function_exists('filter_var') && defined('FILTER_VALIDATE_EMAIL') ) {
     $valid_address = (bool)filter_var($idemail, FILTER_VALIDATE_EMAIL);
    } else {
      if ( substr_count( $idemail, '@' ) > 1 ) {
        $valid_address = false;
      }
      if ( preg_match("/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i", $idemail) ) {
        $valid_address = true;
      } else {
        $valid_address = false;
      }
    }
    if ($valid_address && ENTRY_EMAIL_ADDRESS_CHECK == 'true') {
      $domain = explode('@', $idemail);
      if ( !checkdnsrr($domain[1], "MX") && !checkdnsrr($domain[1], "A") ) {
        $valid_address = false;
      }
    }
    return $valid_address;
  } 





if(isset($_POST['idemail'])) {  
// require 'mail/PHPMailerAutoload.php';
  // require 'autoload.php';
 $mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.thetestingstudio.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'info@thetestingstudio.com';                 // SMTP username
$mail->Password = 'e#0%2dNef.Wz';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->From = 'info@thetestingstudio.com';
$mail->FromName = 'Github Portfolio Website Inquiry';
$mail->addAddress('pranilbamne@gmail.com', 'Github Portfolio Website Inquiry');     // Add a recipient Email Address

$mail->ClearReplyTos();
$mail->addReplyTo($_POST['idemail'], $_POST['idname']);
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Github Portfolio Website Inquiry';
$idnames = $_POST['idname'];
$idemails = $_POST['idemail'];
$idphones = $_POST['idphone']; 
$idselect = $_POST['idsubject'];
$idmessage = $_POST['idmessage'];


$mail->Body = 'Hello,<br/><br/>
We have received a new inquiry with following details:<br/><br/>
Name: '.$idnames.'<br/>
Email: '.$idemails.'<br/>
Phone: '.$idphones.'<br/>
Subject: '.$idsubject.'<br/>
Message: '.$idmessage.'<br/><br/>
Thank You';
//$success_sent_msg='<div style="text-align: center; "><strong>&nbsp;</strong>
                            //<p align="center;">
                                //<strong>Your message has been successfully sent to us<br></strong> and we will reply at the earliest.
                            //</p>
                            //<p align="center;">A copy of your message has been sent to your email.</p>
                            //<p align="center"><strong>Thank you for contacting us.</strong></p> </div>';

if($mail->send()) {
    $sent_mail = true;
?>
    
<!-- place your own success html below -->

     <script type="text/javascript">
      alert("Thank You! Your Enquiry Has Been Submitted Successfully. We Will Get Back To You Soon.");window.location.href='https://pranil007.github.io/pranilbamane//index.html';
    </script> 

            <!-- <script type="text/javascript">
      // alert("Thank You! Your Enquiry Has Been Submitted Successfully. Brochure Download will start automatically.");
      

      window.location.href='thank-you.html';
     </script> -->

<?php
} else {
    $sent_mail = false;
?>
<!-- place your own success html below -->
    <script type="text/javascript">
      alert("Sorry! There was some problem submitting your inquiry. Please try again.");window.location.href='https://pranil007.github.io/pranilbamane//index.html';
    </script>
 
            <?php
        }
     }   
    }    
        else{
            $statusMsg = 'Robot verification failed, please try again.';
            ?>

    <script type="text/javascript">
      alert("Robot verification failed, please try again.");window.location.href='https://pranil007.github.io/pranilbamane/index.html';
    </script>
    
<?php
        }
    }
    else
    {
        $statusMsg = 'Robot verification failed, please try again.';
        
                    ?>
     
            <script type="text/javascript">
              alert("Robot verification failed, please try again.");window.location.href='https://pranil007.github.io/pranilbamane/index.html';
            </script>
          

        <?php
    }
    }
    die();
?>
  </head>
  <body></body>
</html>

    