<!doctype html>
<html lang="en">
 
<head>
<?php include "sessions.php";
?>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="css/style1.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"><a class="navbar-brand" href="index.html">ABAY HOME</a><span class="splash-description">Please enter your user information.</span></div>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="loginUN" type="text" placeholder="Username" autocomplete="off">
                    </div>
                    <span id="errorMessage" style="color:red; font-size: 80%"></span>
                    <div class="form-group">
                        <input class="form-control form-control-lg" id="loginPW" type="password" placeholder="Password">
                    </div>
                    <br>
                    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="loginUser()">Sign in</button>
                </form>
            </div>
        </div>
    </div>
  
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="js/custom.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
</body>
 
</html>