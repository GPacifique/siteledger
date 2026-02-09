<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - SiteLedger</title>

    <!-- Modern Design System -->
    <link rel="stylesheet" href="{{ asset('css/modern.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="page-wrapper" style="background: var(--gradient-blue);">
    @include('components.navbar')

    <div class="container" style="padding: 2rem;">
        <!-- Enhanced Colorful Page Header -->
        <div class="card-colorful ocean" style="margin-bottom: 2rem;">
            <div class="card-body" style="text-align: center;">
                <h1 style="font-size: 3rem; color: var(--primary); margin-bottom: 0.5rem;">
                    <span class="icon-bounce">👥</span> Our Valued Clients
                </h1>
                <p style="color: var(--gray-700); margin: 0; font-size: 1.2rem;">
                    Building relationships and managing client portfolios
                </p>
            </div>
        </div>
    </div>

    <div class="container" style="padding: 2rem;">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
            <a href="{{ route('clients.create') }}" class="btn-rainbow">
                <span class="icon-bounce">✨</span> Add New Client
            </a>
        </div>

        @if($clients->count() > 0)
            <div class="card-colorful purple">
                <div class="card-body" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table class="table-enhanced">
                            <thead>
                                <tr>
                                    <th><span class="icon-pulse">🏢</span> Client Name</th>
                                    <th><span class="icon-pulse">👤</span> Contact Person</th>
                                    <th><span class="icon-pulse">📧</span> Email</th>
                                    <th><span class="icon-pulse">📱</span> Phone</th>
                                    <th><span class="icon-pulse">📍</span> Address</th>
                                    <th><span class="icon-pulse">⚡</span> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr onclick="window.location='{{ route('clients.show', $client->id) }}'" style="cursor: pointer;">
                                        <td><strong>{{ $client->name }}</strong></td>
                                        <td>{{ $client->contact_person ?? '-' }}</td>
                                        <td>{{ $client->email ?? '-' }}</td>
                                        <td>{{ $client->phone ?? '-' }}</td>
                                        <td>{{ $client->address ?? '-' }}</td>
                                        <td onclick="event.stopPropagation()">
                                            <div style="display: flex; gap: 0.5rem;">
                                                <a href="{{ route('clients.edit', $client->id) }}" class="btn-ocean" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">Edit</a>
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-sunset" style="padding: 0.375rem 0.75rem; font-size: 0.875rem; border: none;">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card-colorful ocean" style="text-align: center; padding: 3rem;">
                <div class="card-body">
                    <span class="icon-bounce" style="font-size: 3rem;">👥</span>
                    <p style="margin: 1rem 0; color: var(--gray-600);">No clients found.</p>
                    <a href="{{ route('clients.create') }}" class="btn-rainbow">Create New Client</a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
