<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Penugasan ED Retail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#082A5E;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .login-card{
            background:white;
            border-radius:16px;
            padding:40px 35px;
            width:100%;
            max-width:400px;
        }
        .logo{
            font-size:32px;
            font-weight:bold;
            color:#082A5E;
            margin-bottom:5px;
        }
        .logo span{ color:#49A5FF; }
        .subtitle{
            color:#6c757d;
            font-size:14px;
            margin-bottom:25px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo"><span>R</span>EDIS</div>
        <div class="subtitle">Login khusus Tim ED Retail &mdash; Penugasan</div>

        @if ($errors->any())
            <div class="alert alert-danger py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('penugasan.login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('dashboard') }}" class="small text-muted">&larr; Kembali ke Dashboard</a>
        </div>
    </div>

</body>
</html>
