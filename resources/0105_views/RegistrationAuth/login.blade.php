<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Remix Icons (for eye icon like admin panel) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #0f172a;
            font-family: 'Segoe UI', sans-serif;
        }

        .auth-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #405189, #0ab39c);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
        }

        .login-card .card-body {
            padding: 35px;
        }

        .login-title {
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">
        <div class="card shadow-lg border-0 login-card">
            <div class="card-body">

                <div class="text-center mb-4">
                    <h4 class="login-title text-primary">User Login</h4>
                    <p class="text-muted">Sign in to continue</p>
                </div>

                {{-- Success / Error --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
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

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            placeholder="Enter email" required>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password" class="form-control pe-5"
                                placeholder="Enter password" required>

                            <div class="mb-4 text-end">
                                <a href="{{ route('password.forgot') }}" class="text-decoration-none text-primary">
                                    Forgot Password?
                                </a>
                            </div>

                            <button type="button"
                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                style="margin-top: 5px;" onclick="togglePassword()">
                                <i class="ri-eye-fill" id="icon"></i>
                            </button>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("icon");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("ri-eye-fill");
                icon.classList.add("ri-eye-off-fill");
            } else {
                input.type = "password";
                icon.classList.remove("ri-eye-off-fill");
                icon.classList.add("ri-eye-fill");
            }
        }
    </script>

</body>

</html>
