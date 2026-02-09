<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Client - SiteLedger</title>
    <link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            min-height: 100vh;
            color: #333;
            padding: 2rem 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .page-header p {
            font-size: 1.1rem;
            opacity: 0.95;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .form-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            position: relative;
        }
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #06b6d4 0%, #0891b2 50%, #06b6d4 100%);
            border-radius: 16px 16px 0 0;
        }
        .form-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .form-header-icon {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .form-header h2 {
            font-size: 1.8rem;
            color: #1f2937;
            margin: 0;
        }
        .form-header p {
            font-size: 0.95rem;
            color: #6b7280;
            margin: 0;
        }
        .form-group {
            margin-bottom: 1.75rem;
        }
        .form-label-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        label {
            display: block;
            font-weight: 700;
            color: #1f2937;
            font-size: 0.95rem;
        }
        .required {
            color: #ef4444;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 0.95rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        input[type="text"]::placeholder,
        input[type="email"]::placeholder,
        textarea::placeholder {
            color: #9ca3af;
        }
        input:hover,
        textarea:hover {
            border-color: #d1d5db;
            background: #ffffff;
        }
        input:focus,
        textarea:focus {
            outline: none;
            border-color: #06b6d4;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
        }
        textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .page-header h1 {
                font-size: 1.8rem;
            }
            .form-card {
                padding: 1.5rem;
            }
        }
        .button-group {
            display: flex;
            gap: 1.25rem;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid #f0f0f0;
        }
        button[type="submit"] {
            flex: 1;
            padding: 1.125rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.2);
        }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.35);
        }
        button[type="submit"]:active {
            transform: translateY(0);
        }
        .btn {
            flex: 1;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #374151;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1.125rem 2rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            border: 2px solid #d1d5db;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn i {
            margin-right: 0.75rem;
        }
        .btn:hover {
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        .error {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .error::before {
            content: '⚠';
            font-size: 1rem;
        }
        .alert {
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            animation: slideIn 0.3s ease;
        }
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 2px solid #fca5a5;
            border-left: 4px solid #dc2626;
        }
        .alert strong {
            display: block;
            margin-bottom: 0.75rem;
            font-size: 1.05rem;
        }
        .alert ul {
            margin-left: 1.75rem;
            margin-top: 0.5rem;
        }
        .alert li {
            margin-bottom: 0.35rem;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .field-icon {
            color: #06b6d4;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Add New Client</h1>
            <p>Create and manage your client information</p>
        </div>

        <div class="form-card">
            <div class="form-header">
                <div class="form-header-icon"><i class="fas fa-address-card"></i></div>
                <div>
                    <h2>Client Details</h2>
                    <p>Fill in the client information below</p>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <!-- Client Name -->
                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label for="name">
                            <i class="fas fa-building field-icon"></i>
                            Client Name <span class="required">*</span>
                        </label>
                    </div>
                    <input type="text" name="name" id="name" placeholder="Enter company or client name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contact and Email -->
                <div class="form-row">
                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="contact_person">
                                <i class="fas fa-user field-icon"></i>
                                Contact Person <span class="required">*</span>
                            </label>
                        </div>
                        <input type="text" name="contact_person" id="contact_person" placeholder="Full name" value="{{ old('contact_person') }}" required>
                        @error('contact_person')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-label-wrapper">
                            <label for="email">
                                <i class="fas fa-envelope field-icon"></i>
                                Email
                            </label>
                        </div>
                        <input type="email" name="email" id="email" placeholder="email@example.com" value="{{ old('email') }}">
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label for="phone">
                            <i class="fas fa-phone field-icon"></i>
                            Phone <span class="required">*</span>
                        </label>
                    </div>
                    <input type="text" name="phone" id="phone" placeholder="+250 XXX XXX XXX" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="form-group">
                    <div class="form-label-wrapper">
                        <label for="address">
                            <i class="fas fa-map-marker-alt field-icon"></i>
                            Address
                        </label>
                    </div>
                    <textarea name="address" id="address" placeholder="📍 Enter client address (optional)...">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit"><i class="fas fa-save"></i> Save Client</button>
                    <a href="{{ route('clients.index') }}" class="btn"><i class="fas fa-arrow-left"></i> Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
