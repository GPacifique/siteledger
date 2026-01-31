<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SiteLedger</title>
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
            font-size: 1.8em;
            margin-bottom: 0;
            text-align: center;
        }
        .logo-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo-header .logo-icon {
            font-size: 3rem;
            display: block;
            margin-bottom: 8px;
        }
        .logo-header .tagline {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.85em;
            font-weight: 600;
            margin-top: 5px;
            letter-spacing: 0.5px;
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
        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 55px;
            height: 55px;
            background: #25d366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(37, 211, 102, 0.5);
        }
        .whatsapp-float svg {
            width: 28px;
            height: 28px;
            fill: #fff;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="logo-header">
            <span class="logo-icon">🏗️</span>
            <h1>SiteLedger</h1>
            <p class="tagline">Construction Sites Management</p>
        </div>
        <p class="subtitle">Create your account to get started</p>

        <div style="background: rgba(102, 126, 234, 0.12); border: 1px solid rgba(102, 126, 234, 0.4); color: #b3c5ff; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9em;">
            📧 You'll need to verify your email address before accessing your account
        </div>

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

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/250786163963" target="_blank" class="whatsapp-float" title="Chat with us on WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</body>
</html>
