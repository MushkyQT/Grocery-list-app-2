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
        <div class="col-4 whiteBox">
            <form method="post" class="mt-2 mb-3">
                <div class="form-group">
                    <label for="newUsername">Username</label>
                    <input type="text" name="newUsername" id="newUsername" class="form-control" placeholder="Enter a username" required>
                </div>
                <div class="form-group">
                    <label for="newEmail">E-Mail</label>
                    <input type="email" name="newEmail" id="newEmail" class="form-control" placeholder="Enter a valid email" required>
                </div>
                <div class="form-group">
                    <label for="newPass">Password</label>
                    <input type="password" name="newPass" id="newPass" class="form-control" placeholder="Enter a password" required>
                </div>
                <div class="form-group">
                    <label for="newPassConfirm">Confirm Password</label>
                    <input type="password" name="newPassConfirm" id="newPassConfirm" class="form-control" placeholder="Enter the password again" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="signUpAttempt">Sign Up!</button>
                </div>
            </form>
            <p><?php echo $fatal ?></p>
        </div>
    </div>
</div>