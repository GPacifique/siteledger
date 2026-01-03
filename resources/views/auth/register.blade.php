<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CSMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 460px;
            width: 100%;
            color: #fff;
        }
        h1 {
            color: #fff;
            font-size: 2em;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            color: #ccc;
            text-align: center;
            margin-bottom: 30px;
            font-size: 0.95em;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #ddd;
            font-weight: 600;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
            background: rgba(255,255,255,0.06);
            color: #fff;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }
        .error-text {
            color: #ff6b6b;
            font-size: 0.85em;
            margin-top: 6px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #ccc;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .alert {
            background: rgba(255, 107, 107, 0.12);
            border: 1px solid rgba(255, 107, 107, 0.4);
            color: #ffb3b3;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .hint {
            color: #aaa;
            font-size: 0.85em;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <h1>Register</h1>
        <p class="subtitle">Create your CSMS account</p>

        @if($errors->any())
            <div class="alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}">
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}">
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <div class="hint">Use at least 8 characters with letters and numbers.</div>
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit">Register</button>
        </form>

        <div class="footer">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
</body>
</html>
