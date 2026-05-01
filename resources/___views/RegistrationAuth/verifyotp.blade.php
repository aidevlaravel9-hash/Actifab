<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%;">
            <h4 class="text-center mb-2">Verify OTP</h4>
            <p class="text-center text-muted mb-4">
                We have sent an OTP to your email
            </p>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif


            <form action="{{ route('verify.otp.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <label class="form-label">Enter OTP</label>
                    <input type="text" name="otp" class="form-control text-center fs-4" maxlength="6"
                        placeholder="123456" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Verify OTP
                </button>
            </form>

            <div class="text-center mt-3">
                <form method="POST" action="{{ route('otp.resend') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ old('email', $email ?? '') }}">
                    <button type="submit" class="btn btn-link">
                        Resend OTP
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
