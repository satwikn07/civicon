<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
        $na=$id=$em=$pass="";
        $nameErr=$idErr=$eMailErr=$repassErr="";
        $status=true;
        if(!empty($_POST))
        {
            if(!empty($_POST['name']))
            {
                $na=$_POST['name'];
            }
            else{
                $status=false;
                $nameErr="Cannot be empty";
            }
            if(!empty($_POST['id']))
            {
                $id=$_POST['id'];
            }
            else{
                $status=false;
                $idErr="Cannot be empty";
            }
            if(!empty($_POST['email']))
            {
                $em=$_POST['email'];
            }
            else{
                $status=false;
                $eMailErr="Cannot be empty";
            }
            if($_POST['pass']==$_POST['repass']){
                $pass=sha1($_POST['pass']);
            }
            else{
                $status=false;
                $repassErr="Passwords do not match";
            }
            // if(!empty($_POST['pass']))
            // {
            //     $pass=sha1($_POST['pass']);
            // }
            // else{
            //     $status=false;
            //     $passErr="Cannot be empty";
            // }

            //Database connection

            $servername= "localhost";
            $username= "root";
            $paassword= "";
            $dbname="civicon";
            //create connection
            $com= new mysqli($servername, $username, $paassword,$dbname);

            //Generate random key

            $vkey=md5(time().$id);

            //check coonection
            if ($com->connect_error ) {
                die("Connection failed ".$com->connect_error);
            }

            else {
                if($status)
                {
                    $sql="INSERT INTO login(name,regd,email,password,vkey) values ('$na','$id','$em','$pass','$vkey')";

                    if($com->query($sql))
                    {
                    require 'PHPMailer/Exception.php';
                    require 'PHPMailer/PHPMailer.php';
                    require 'PHPMailer/SMTP.php';

                    $email=$_POST["email"];

                    // Instantiation and passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        $mail->SMTPDebug = 2;                      // Enable verbose debug output
                        $mail->isSMTP();                                            // Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                        $mail->Username   = 'sender@gmail.com';                     // SMTP username
                        $mail->Password   = 'password';                               // SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                        $mail->Port       = 587;     
    
                        $mail->setFrom('no-reply@gmail.com', 'Mailer');

                        $mail->addAddress($email);               // Name is optional

                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Here is the subject';
                        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';

                        $mail->send();
                        echo 'Message has been sent';
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                            }
                                        else {
                                            echo "Error: ".$sql."<br>".$com->error;
                                        }
                                    $com->close();
                                    }
                                }
                            }
                            ?>
    
    <form action="" method="post">
        <input type="text" name="name" placeholder="Your name" required autofocus ><br><br>
            <p><?php echo $nameErr ?></p>
            <input type="number" name="id" placeholder="Regd. No." required  ><br><br>
            <p><?php echo $idErr ?></p>
            <input type="email" name="email"  placeholder="Email" required  ><br><br>
            <p><?php echo $eMailErr ?></p>
            <input type="password" name="pass"  placeholder="password" required ><br><br>
            <input type="password" name="repass"  placeholder="Re-password" required ><br><br>
            <p><?php echo $repassErr ?></p>
            <input type="submit" value="Submit">

    </form>
</body>
</html>