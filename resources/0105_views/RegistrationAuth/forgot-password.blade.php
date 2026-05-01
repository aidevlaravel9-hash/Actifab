<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: linear-gradient(135deg, #405189, #0ab39c);
            font-family: 'Segoe UI', sans-serif;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-box {
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">
        <div class="card shadow-lg border-0 card-box">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <h4 class="text-primary">Forgot Password</h4>
                    <p class="text-muted">Enter your email to receive new password</p>
                </div>

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error Message --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.send') }}">
                    @csrf

                    <input type="email" name="email" class="form-control" required>

                    <button type="submit" class="btn btn-primary mt-3">
                        Send New Password
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('loginuser') }}" class="text-decoration-none text-muted">
                        Back to Login
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
