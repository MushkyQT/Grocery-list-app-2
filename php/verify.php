<?php

if (isset($_GET['verify']) && isset($_GET['email']) && isset($_GET['hash'])) {
    $verifyEmail = $_GET['email'];
    $verifyHash = $_GET['hash'];
    $myRequest = "SELECT `users`.`verified`, `users`.`hash`, `users`.`username` FROM `users` WHERE `email` = '" . $verifyEmail . "'";
    $myResult = mysqli_query($myConnection, $myRequest);
    if (mysqli_num_rows($myResult) != 0) {
        $currentResult = mysqli_fetch_array($myResult);
        if ($verifyHash == $currentResult['hash'] && $currentResult['verified'] == false) {
            $verifiedUser = $currentResult['username'];
            $myRequest = "UPDATE `users` SET `verified` = '1' WHERE `users`.`email` = '" . $verifyEmail . "'";
            if ($myResult = mysqli_query($myConnection, $myRequest)) {
                $fatal = $verifiedUser . ", your email is now verified! Please log-in.";
                require_once 'vendor/autoload.php';
                $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                    ->setUsername('melki.irfa.sendmail@gmail.com')
                    ->setPassword('ENTER_PASSWORD_HERE');
                $mailer = new Swift_Mailer($transport);
                $metaData = array(
                    "subject" => "Karot Account Verified!",
                    "message" => "Hey " . $verifiedUser . ", your Karot account is now verified! You can log in at https://www.cmelki.cf/karotv2/",
                    "fromName" => "Karot List",
                    "fromEmail" => "melki.irfa.sendmail@gmail.com"
                );
                $message = (new Swift_Message($metaData['subject']))
                    ->setFrom([$metaData['fromEmail'] => $metaData['fromName']])
                    ->addTo($verifyEmail)
                    ->setBody($metaData['message']);
                if (!$mailer->send($message)) {
                    $fatal .= "<br> Uh oh, verification email failed to send. Please create a new account or contact me.";
                }
            } else {
                $fatal = "Verification failed. Please try again.";
            }
        } else {
            $fatal = "Invalid hash or account already verified. Try again or log-in.";
        }
    } else {
        $fatal = "No account found for this email address. Please try again.";
    }
} else {
    $fatal = "Invalid verification link, please try again.";
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between">
    <a class="navbar-brand" href=".">Karot</a>
    <ul class="navbar-nav">
        <li class="nav-item">
            <form class="form-inline mr-4" method="post">
                <div class="form-group mr-2">
                    <input type="text" name="username" id="username" placeholder="Enter your username" class="form-control">
                </div>
                <div class="form-group mr-2">
                    <input type="password" name="password" id="password" placeholder="Enter a password" class="form-control">
                </div>
                <button type="submit" class="btn btn-success" name="signIn">Sign In</button>
            </form>
        </li>
        <li class="nav-item">
            <form method="post">
                <button type="submit" class="btn btn-warning" name="signUp">Sign Up</button>
            </form>
        </li>
    </ul>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-6 whiteBox">
            <h1>EMAIL VERIFICATION</h1>
            <p><?php echo $fatal ?></p>
        </div>
    </div>
</div>