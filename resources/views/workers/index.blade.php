<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers - SiteLedger</title>

    <!-- Modern Design System -->
    <link rel="stylesheet" href="{{ asset('css/modern.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="page-wrapper" style="background: var(--gradient-green);">
    @include('components.navbar')

    <div class="container" style="padding: 2rem;">
        <!-- Enhanced Colorful Page Header -->
        <div class="card-colorful sunset" style="margin-bottom: 2rem;">
            <div class="card-body" style="text-align: center;">
                <h1 style="font-size: 3rem; color: var(--orange-dark); margin-bottom: 0.5rem;">
                    <span class="icon-bounce">👷</span> Our Workforce
                </h1>
                <p style="color: var(--gray-700); margin: 0; font-size: 1.2rem;">
                    Managing our dedicated construction team
                </p>
            </div>
        </div>
    </div>

    <div class="container" style="padding: 2rem;">
        <!-- Enhanced Worker Stats or Actions could go here -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
            <a href="{{ route('workers.create') }}" class="btn-rainbow">
                <span class="icon-bounce">✨</span> Add New Worker
            </a>
        </div>

        @if($workers->count() > 0)
            <div class="card-colorful purple">
                <div class="card-body" style="padding: 0;">
                    <div style="overflow-x: auto;">
                        <table class="table-enhanced">
                            <thead>
                                <tr>
                                    <th><span class="icon-pulse">👤</span> Name</th>
                                    <th><span class="icon-pulse">💼</span> Position</th>
                                    <th><span class="icon-pulse">📧</span> Email</th>
                                    <th><span class="icon-pulse">📊</span> Status</th>
                                    <th><span class="icon-pulse">💰</span> Daily Wages</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workers as $worker)
                                    <tr onclick="window.location.href='/workers/{{ $worker->id }}';" style="cursor: pointer;">
                                        <td><strong>{{ $worker->first_name }} {{ $worker->last_name }}</strong></td>
                                        <td>{{ $worker->position ?? 'N/A' }}</td>
                                        <td>{{ $worker->email ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $statusVariant = $worker->status === 'active' ? 'success' : 'error';
                                            @endphp
                                            <span class="badge-colorful badge-{{ $statusVariant }}">{{ ucfirst($worker->status) }}</span>
                                        </td>
                                        <td>RWF {{ number_format($worker->daily_wage ?? 0, 0) }}</td>
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
                    <span class="icon-bounce" style="font-size: 3rem;">👷</span>
                    <p style="margin: 1rem 0; color: var(--gray-600);">No workers found.</p>
                    <a href="/admin/dashboard" class="btn-ocean">Go to Dashboard</a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
