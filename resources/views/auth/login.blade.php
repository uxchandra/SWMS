<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            overflow: hidden;  /* Mencegah scrolling */
        }
        
        .bg-image {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('images/background.png') }}');
            background-size: 100% 100%;  /* atau bisa pakai 'contain' */
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1;
            width: 100%;
            max-width: 400px;
        }

        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo img {
            height: 100px;
            width: auto;
        }

        /* Input fields */
        .input-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #4a5568;
        }

        .text-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .text-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Tombol login */
        .primary-button {
            background-color: #667eea;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-weight: 500;
        }

        .primary-button:hover {
            background-color: #5a67d8;
        }

        /* Error messages */
        .input-error {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Remember me checkbox */
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .remember-me input {
            margin-right: 0.5rem;
        }

        .remember-me span {
            font-size: 0.875rem;
            color: #4a5568;
        }
    </style>
</head>
<body class="bg-image">
    <div class="login-container">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Warehouse">
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="input-label">Username</label>
                <input id="username" class="text-input" type="text" name="username" value="{{ old('username') }}" required autofocus>
                @if ($errors->has('username'))
                    <div class="input-error">{{ $errors->first('username') }}</div>
                @endif
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="input-label">Password</label>
                <input id="password" class="text-input" type="password" name="password" required>
                @if ($errors->has('password'))
                    <div class="input-error">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="remember-me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Remember me</span>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="primary-button">Login</button>
            </div>
        </form>
    </div>
</body>
</html>