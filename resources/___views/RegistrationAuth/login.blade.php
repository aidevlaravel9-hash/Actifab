<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
    </style>
</head>

<body>

    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow-sm border-0" style="max-width: 420px; width: 100%;">
            <div class="card-body p-4">

                <h4 class="text-center mb-3">Login</h4>

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
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter password" required>
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('password','icon')">
                                <i class="bi bi-eye" id="icon"></i>
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
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        }
    </script>

</body>

</html>
