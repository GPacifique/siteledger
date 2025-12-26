<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $client->name }} - SiteLedger</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .header-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header-card h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #333;
        }
        .header-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-edit {
            background: #3498db;
            color: white;
        }
        .btn-edit:hover {
            background: #2980b9;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .info-card h3 {
            font-size: 0.9rem;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .info-card p {
            font-size: 1.1rem;
            color: #333;
            word-break: break-word;
        }
        .info-card .empty {
            color: #ccc;
            font-style: italic;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <a href="{{ route('clients.index') }}" class="back-link">← Back to Clients</a>

        <div class="header-card">
            <h1>👥 {{ $client->name }}</h1>
            <div class="header-actions">
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-edit">✏️ Edit</a>
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this client?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">🗑️ Delete</button>
                </form>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h3>Contact Person</h3>
                <p>{{ $client->contact_person ?? '—' }}</p>
            </div>

            <div class="info-card">
                <h3>Email</h3>
                @if($client->email)
                    <p><a href="mailto:{{ $client->email }}" style="color: #667eea; text-decoration: none;">{{ $client->email }}</a></p>
                @else
                    <p>—</p>
                @endif
            </div>

            <div class="info-card">
                <h3>Phone</h3>
                <p>{{ $client->phone ?? '—' }}</p>
            </div>

            <div class="info-card" style="grid-column: 1 / -1;">
                <h3>Address</h3>
                <p>{{ $client->address ?? '—' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
