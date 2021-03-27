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
            <h1>Home Page - Log in here</h1>
            <p><?php echo $fatal ?></p>
        </div>
    </div>
</div>