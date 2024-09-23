<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    $mail = new PHPMailer;

    //Server settings                                                                                 
    $mail->isSMTP();                                                        
    $mail->Host       = 'smtp.gmail.com'; // SMTP server của Gmail                           
    $mail->SMTPAuth   = true;             // Sử dụng xác thực SMTP                                               
    $mail->Username   = 'htgiang09042003@gmail.com'; // Địa chỉ email của bạn
    $mail->Password   = 'uwjr trrj emhs qhdm'; // Mật khẩu ứng dụng mới bạn vừa tạo (dùng mật khẩu ứng dụng)
    $mail->SMTPSecure = 'tls';            // Giao thức bảo mật TLS
    $mail->Port       = 587;              // Cổng SMTP của Gmail

    try {
        if(isset($_POST["submit"]) && $_POST["submit"] == "query") {
            $recipient = "htgiang09042003@gmail.com";
            $subject = "Query from bookstore";
            $sender = $_POST["sender"];
            $senderEmail = $_POST["senderEmail"];
            $message = $_POST["message"];
            $mailBody = "Tên: $sender<br>Email: $senderEmail<br><br>Nội dung: $message";

            //Sender info
            $mail->setFrom($mail->Username, $sender);

            //Tiêu đề Reply-To
            $mail->addReplyTo($senderEmail, $sender);

            //Add a recipient
            $mail->addAddress($recipient);

            //Set email format to HTML
            $mail->isHTML(true);

            //Mail subject
            $mail->Subject = $subject;

            //Mail subject
            $mail->Body = $mailBody;

            if($mail->send())
            {              
                echo '<script type="text/javascript">alert("Tin nhắn đã gửi thành công! Chúng tôi sẽ trả lời bạn sớm nhất có thể."); window.location.href = "home_page.php";</script>';
            }
            else
            {
                echo '<script type="text/javascript">alert("Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau."); window.location.href = "home_page.php";</script>';
            }
        }
    } catch(Exception $e) {
        echo "Caught exception: ",  $e->getMessage(), "\n";
    }
?>
