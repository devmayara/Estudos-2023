<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('assets/css/admin.login.css') }}">
    <title>B7Bio | Cadastro</title>
</head>
<body class="text-center">
    @if ($error)
        <div class="error">
            {{ $error }}
        </div>
    @endif
    <form class="form-signin" method="POST" action="">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Faça Cadastro</h1>
        <div class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Digite seu e-mail">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Digite sua senha">
        </div>
        <input class="btn btn-primary mb-4" type="submit" value="Cadastro">
        <br>Já tem cadastro? <a href="{{ url('/admin/login') }}"> Faça Login</a>
        <p class="mt-5 mb-3 text-muted">copyright &copy; - <?php echo date('Y'); ?></p>
    </form>
</body>
</html>
