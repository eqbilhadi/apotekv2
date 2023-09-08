<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apotek | Log in</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>/assets/img/users/favicon.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Apot</b>ek</a>
            </div>
            <div class="card-body mb-3">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="#" method="POST" id="formData">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <label for="remember">

                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-primary btn-login" onclick="login()" style="width: 100%;">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
    <script src="<?= base_url('assets') ?>/bower_components/sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>
<script>
    var formData = $('#formData');
    var btnLogin = $('.btn-login');

    function login() {
        $.ajax({
            type: "post",
            url: "<?= site_url('auth/process') ?>",
            data: formData.serialize(),
            dataType: "json",

            beforeSend: function() {
                btnLogin.attr('disabled', true);
                btnLogin.html('<span class="fa fa-spin fa-spinner"></span> Tunggu');
            },

            complete: function() {
                btnLogin.attr('disabled', false);
                btnLogin.text('Login');
            },

            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: response.success,
                        confirmButtonText: 'Ok',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '<?= site_url('dashboard') ?>'
                        }
                    })
                }
                if (response.failed) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.failed,
                    })
                }
            }
        })
    }
</script>