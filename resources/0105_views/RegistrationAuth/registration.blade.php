<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            max-width: 500px;
            width: 100%;
            border-radius: 12px;
        }

        .auth-card .card-body {
            padding: 40px;
        }

        .auth-title {
            color: #198754;
            font-weight: 600;
        }

        .form-control {
            height: 45px;
        }

        .btn-custom {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-custom:hover {
            background-color: #157347;
            border-color: #157347;
        }
    </style>
</head>

<body>

    <div class="auth-wrapper">
        <div class="card shadow auth-card">

            <div class="card-body">

                <!-- Logo -->
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" height="40">
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <!-- Form -->
                <form method="POST" action="{{ route('registration.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            Company Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Contact Person <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="contact_person" class="form-control"
                            placeholder="Enter Contact Person" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Mobile No <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="mobile" maxlength="10" class="form-control"
                            placeholder="Enter Mobile No" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="address" rows="2" class="form-control" placeholder="Enter Address" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter Password" required>

                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('password','toggleIcon')">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Confirm Password <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="confirm_password"
                                class="form-control" placeholder="Confirm Password" required>

                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('confirm_password','toggleIcon2')">
                                <i class="bi bi-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-custom text-white w-100">
                        Sign In
                    </button>

                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }
</script>
