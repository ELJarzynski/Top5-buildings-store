<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

if(isset($_POST['submit']))
{
    $email = $_POST['email'];
    $message = $_POST['content'];
    $name = $_POST['firstname'];
    $country = $_POST['country'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $message = "Wrong E-mail";
    }
    else
    {
      $final_message = "Dziękuje za kontakt, $name. Odezwę się najszybciej jak tylko będę mógł!</br>Z wyrazami szacunku: <i>Kamil</i>";
      $mail->isSMTP();                                            
      $mail->Host       = 'smtp.gmail.com';                    
      $mail->SMTPAuth   = true;                             
      $mail->Username   = 'jarzynskyyy@gmail.com';                 
      $mail->Password   = 'hpac uzvg fogj bxuy';                        
      $mail->SMTPSecure = 'tls';                              
      $mail->Port       = 587;  
      $mail->CharSet = "UTF-8";
      $mail->setFrom('jarzynskyyy@gmail.com');           
      $mail->addAddress($email, $name);
          
      $mail->IsHTML(true);
      $mail->SetFrom("jarzynskyyy@gmail.com", "no-reply");
      $mail->Subject = "Dziękuje za kontakt!";
      $mail->MsgHTML($final_message);
      if(!$mail->Send()) {
        echo "Error while sending Email: " . $mail->ErrorInfo;
    } else {
        echo "Email sent successfully";
    }
      $mail->ClearAllRecipients();
  
      $final_message = "<p>E-Mail: $email</p><p>Imię: $name</p><p>Kraj: $country</p><p>Treść: $message";
      $mail->setFrom('jarzynskyyy@gmail.com');           
      $mail->addAddress('jarzynskyyy@gmail.com');
      $mail->IsHTML(true);
      $mail->SetFrom("jarzynskyyy@gmail.com", "no-reply");
      $mail->Subject = "Wiadomość z blogu od: $email";
      $mail->MsgHTML($final_message);
      if(!$mail->Send()) {
        echo "Error while sending Email.";
        var_dump($mail);
      } else {
        echo "Email sent successfully";
      }
      header('Location: index.php');
    }  
  }
  
  if(isset($_POST['remind']))
  {
    require_once './scripts/cfg.php';
  
    $final_message = "Hasło to: ".cfg::$user_pass;
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;                             
    $mail->Username   = 'jarzynskyyy@gmail.com';                 
    $mail->Password   = 'hpac uzvg fogj bxuy';                        
    $mail->SMTPSecure = 'tls';                              
    $mail->Port       = 587;  
    $mail->CharSet = "UTF-8";
    $mail->setFrom('jarzynskyyy@gmail.com');           
    $mail->addAddress("jarzynskyyy@gmail.com");
       
    $mail->IsHTML(true);
    $mail->SetFrom("jarzynskyyy@gmail.com", "no-reply");
    $mail->Subject = "Przypomnienie hasła";
    $mail->MsgHTML($final_message);
    if(!$mail->Send()) {
      echo "Error while sending Email.";
      var_dump($mail);
    } else {
      echo "Email sent successfully";
    }
  }
  
  ?>