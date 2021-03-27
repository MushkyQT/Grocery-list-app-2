<?php

session_start();

require_once('php/creds.php');

$loggedIn = false;
$signUpMode = false;
$fatal = "";

// SignOut post check and username/password login check
if (isset($_POST['signOut'])) {
    $loggedIn = false;
    session_unset();
}

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $_POST['username'] = $_SESSION['username'];
    $_POST['password'] = $_SESSION['password'];
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username != "" && $password != "") {
        $myRequest = "SELECT * FROM `users` WHERE `username` = '" . $username . "'";
        $myResult = mysqli_query($myConnection, $myRequest);
        if (mysqli_num_rows($myResult) > 0) {
            $currentResult = mysqli_fetch_array($myResult);
            if ($currentResult['password'] == (md5($password) . md5('a24bond$v'))) {
                if ($currentResult['verified'] == false) {
                    $fatal = "Your e-mail still needs to be verified, please check your inbox and spam for " . $currentResult['email'];
                } else {
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    $_SESSION['id'] = $currentResult['id'];
                    $loggedIn = true;
                }
            } else {
                $fatal = "Incorrect password for " . $username;
            }
        } else {
            $fatal = "Username " . $username . " not found.";
        }
    } else {
        $fatal = "One or both fields were empty, please try again.";
    }
} elseif (isset($_POST['signUpAttempt']) || isset($_POST['signUp'])) {
    $signUpMode = true;
    if (isset($_POST['signUpAttempt'])) {
        if (isset($_POST['newUsername']) && isset($_POST['newEmail']) && isset($_POST['newPass']) && isset($_POST['newPassConfirm'])) {
            $newUsername = $_POST['newUsername'];
            $newEmail = $_POST['newEmail'];
            $newPass = $_POST['newPass'];
            $newPassConfirm = $_POST['newPassConfirm'];
            if ($newUsername != "" && $newEmail != "" && $newPass != "" && $newPassConfirm != "") {
                if ($newPass == $newPassConfirm) {
                    $myRequest = "SELECT `users`.`id` FROM `users` WHERE `username` = '" . $newUsername . "'";
                    if (mysqli_num_rows($myResult = mysqli_query($myConnection, $myRequest)) != 0) {
                        $fatal = $newUsername . " already in use, please try a different username.";
                    } else {
                        $myRequest = "SELECT `users`.`email` FROM `users` WHERE `email` = '" . $newEmail . "'";
                        if (mysqli_num_rows($myResult = mysqli_query($myConnection, $myRequest)) != 0) {
                            $fatal = $newEmail . " already in use, please try a different email.";
                        } else {
                            $hash = md5(rand(0, 1000));
                            $newPass = md5($newPass) . md5('a24bond$v');
                            $addUserRequest = "INSERT INTO `users` (`username`, `password`, `email`, `hash`) VALUES ('" . $newUsername . "', '" . $newPass . "', '" . $newEmail . "', '" . $hash . "')";
                            if ($myResult = mysqli_query($myConnection, $addUserRequest)) {
                                require_once 'vendor/autoload.php';
                                $signUpMode = false;
                                $fatal = "Created account for " . $newUsername . ". You must verify your email address before you can log-in.";
                                // Send verification email to $newEmail with ?verify=&email=$newEmail&hash=$hash
                                $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                                    ->setUsername('melki.irfa.sendmail@gmail.com')
                                    ->setPassword('tWOMEk6%9VgP');
                                $mailer = new Swift_Mailer($transport);
                                $metaData = array(
                                    "subject" => "Karot Account Verification Link",
                                    "message" => "Thank you for signing up to Karot! Your account must be verified before you can log-in. To do so, simply click the following link: https://www.cmelki.cf/karotv2/?verify=&email=" . $newEmail . "&hash=" . $hash,
                                    "fromName" => "Karot List",
                                    "fromEmail" => "melki.irfa.sendmail@gmail.com"
                                );
                                $message = (new Swift_Message($metaData['subject']))
                                    ->setFrom([$metaData['fromEmail'] => $metaData['fromName']])
                                    ->addTo($newEmail)
                                    ->setBody($metaData['message']);
                                if ($mailer->send($message)) {
                                    $fatal .= "<br> Verification link sent to " . $newEmail;
                                } else {
                                    $fatal .= "<br> Uh oh, verification email failed to send. Please create a new account or contact me.";
                                }
                            } else {
                                $fatal = "Account creation fail.";
                            }
                        }
                    }
                } else {
                    $fatal = "Your passwords do not match, please try again carefully.";
                }
            } else {
                $fatal = "One or more fields were empty, please try again.";
            }
        } else {
            $fatal = "One or more fields were empty, please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Karot V2</title>
</head>

<body>
    <?php

    if ($loggedIn == true) {
        include('php/loggedIn.php');
    } elseif ($signUpMode == true) {
        include('php/signUp.php');
    } elseif (isset($_GET['verify'])) {
        include('php/verify.php');
    } else {
        include('php/logIn.php');
    }


    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script type='text/javascript'>
        $(".whiteBox").on("click", ".myTd", function() {
            var id = $(this).siblings().last().children().children().val();
            var product = $(this).html();
            $(this).replaceWith("<td class='myTdNew'><form method='post' id='" + id + "'></form><input type='hidden' form='" + id + "' name='modify' value='" + id + "'><input type='text' form='" + id + "' value='" + product + "' class='form-control editInput' name='editProduct'></td>");
            $(".editInput").focus();
            $(".editInput").focusout(function() {
                if ($(".editInput").val() != product) {
                    $("#" + id).submit();
                } else {
                    $(".myTdNew").replaceWith("<td class='myTd'>" + product + "</td>")
                }
            });
        })

        $(".modify").click(function() {
            $(this).parent().siblings(".myTd").trigger("click");
        })
    </script>
</body>

</html>