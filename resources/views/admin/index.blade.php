<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            max-width: 400px;
            margin: 5% auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .login-card h3 {
            font-weight: 600;
            color: #4b4b4b;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 12px;
        }

        .btn-primary {
            background-color: #6f42c1;
            border: none;
            border-radius: 12px;
            padding: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #5a32a3;
        }

        .text-purple {
            color: #6f42c1;
        }

        .form-check-input:checked {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" width="80" alt="user">
            <h3 class="mt-3">Welcome Back</h3>
            <p class="text-muted">Login to your account</p>
        </div>

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email">

                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Enter your password">

                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            {{-- <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="#" class="text-purple">Forgot Password?</a>
            </div> --}}
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        {{-- <div class="text-center mt-3">
            <p class="text-muted">Don’t have an account? <a href="#" class="text-purple fw-semibold">Sign Up</a>
            </p>
        </div> --}}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>














































{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head> --}}

{{-- Make me db schema for urbanclap where there is admin which is okay but there will be service provider and customer,
athem after entering phone number they will put otp after otp the users will register and in case of service provider
there will be KYC upload and now there below there are catagories like ac ,tv like that and under that we got ac
servicing , ac odour like stuffs with cost --}}

{{-- </html> --}}
