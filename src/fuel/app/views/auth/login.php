<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <?php echo \Asset::css('style.css'); ?>

    <title>Hello, world!</title>
</head>

<body class="login-page bg-body-secondary">

    <div class="login-box">
        <?php if (\Session::get_flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo \Session::get_flash('error'); ?>
            </div>
        <?php endif; ?>
        <?php if (\Session::get_flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo \Session::get_flash('success'); ?>
            </div>
        <?php endif; ?>
        <div class="login-logo">
            <a href="<?php echo \Uri::create('login'); ?>">Login</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <form action="<?php echo \Uri::create('login'); ?>" method="post">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Login to start your todo list</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required/>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required/>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <!--begin::Row-->
                    <div class="row mb-3">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                                <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                        </div>
                        <!-- /.col -->
                    </div>

                    <!--end::Row-->
                    <div class="social-auth-links text-center mb-3 d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                    <!-- /.social-auth-links -->
                    <p class="mb-1"><a href="forgot-password.html">I forgot my password</a></p>
                    <p class="mb-0">
                        <a href="<?php echo \Uri::create('register'); ?>" class="text-center"> Register a new membership </a>
                    </p>
                </div>
            </form>
            <!-- /.login-card-body -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>