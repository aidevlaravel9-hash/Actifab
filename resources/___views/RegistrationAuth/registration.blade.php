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
        height:100%;
        maring:0;
        overflow:hidden;
        background: linear-gradient(135deg, #2b5673, #4a987f);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .auth-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .auth-card {
        max-width: 600px;
        width: 100%;
        border-radius: 20px;
        border: none;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .auth-card:hover {
        transform: translateY(-5px);
    }

    .auth-card .card-body {
        padding: 45px;
    }

    .auth-title {
        color: #198754;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .form-label {
        font-weight: 500;
        font-size: 14px;
        color: #444;
    }

    .form-control {
        height: 48px;
        border-radius: 10px;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    textarea.form-control {
        height: auto;
        border-radius: 10px;
    }

    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }

    .input-group .btn {
        border-radius: 0 10px 10px 0;
    }

    .btn-custom {
        background: linear-gradient(135deg, #198754, #157347);
        border: none;
        padding: 12px;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(25, 135, 84, 0.3);
    }

    .alert {
        border-radius: 10px;
    }

    .text-danger {
        font-size: 12px;
    }

    img {
        transition: transform 0.3s ease;
    }

    img:hover {
        transform: scale(1.05);
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
                  <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Company Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Contact Person <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="contact_person" class="form-control"
                            placeholder="Enter Contact Person" required>
                    </div>
                  </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Mobile No <span class="text-danger">*</span>
                        </label>
                        <input type="tel"name="mobile"maxlength="10" class="form-control" placeholder="Enter Mobile No"
                            inputmode="numeric" pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            required>
                    </div>
                </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Address <span class="text-danger">*</span>
                        </label>
                        <textarea name="address" rows="2" class="form-control" placeholder="Enter Address" required></textarea>
                    </div>
                    
                    
                <div class="row">
                    <div class="col-md-6 mb-3">
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

                    <div class="col-md-6 mb-3">
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
