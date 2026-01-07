<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $position->name }} - SiteLedger</title>
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
            max-width: 900px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .header-card h1 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }
        .header-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
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
        .btn-primary {
            background: white;
            color: #667eea;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .btn-danger {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .btn-danger:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .card h2 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 1rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 1.5rem;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            color: #999;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        .table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #667eea;
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        .table a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .table a:hover {
            text-decoration: underline;
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #999;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .stat-box {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            text-align: center;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #999;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        @media (max-width: 768px) {
            .header-card {
                padding: 1.5rem;
            }
            .header-card h1 {
                font-size: 1.6rem;
            }
            .header-actions {
                flex-direction: column;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <a href="{{ route('positions.index') }}" class="back-link">← Back to Positions</a>

        <div class="header-card">
            <h1>👔 {{ $position->name }}</h1>
            <span class="badge {{ $position->is_active ? 'badge-active' : 'badge-inactive' }}">
                {{ $position->is_active ? '✓ Active' : '✗ Inactive' }}
            </span>
            @if($position->description)
                <p style="margin-top: 1rem; opacity: 0.95;">{{ $position->description }}</p>
            @endif
            <div class="header-actions">
                <a href="{{ route('positions.edit', $position) }}" class="btn btn-primary">✏️ Edit</a>
                <form action="{{ route('positions.destroy', $position) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this position?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                </form>
            </div>
        </div>

        <!-- Position Details -->
        <div class="card">
            <h2>📋 Position Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Category</span>
                    <span class="info-value">{{ $position->category ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Seniority Level</span>
                    <span class="info-value">{{ \App\Models\WorkerPosition::seniorityLevels()[$position->seniority_level] ?? '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Hourly Rate</span>
                    <span class="info-value">{{ $position->hourly_rate ? 'RWF ' . number_format($position->hourly_rate, 0) : '—' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Daily Rate</span>
                    <span class="info-value">{{ $position->daily_rate ? 'RWF ' . number_format($position->daily_rate, 0) : '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Workers Statistics -->
        <div class="card">
            <h2>👥 Workers Statistics</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value">{{ $totalWorkers }}</div>
                    <div class="stat-label">Total Workers</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $activeWorkers }}</div>
                    <div class="stat-label">Active Workers</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $totalWorkers - $activeWorkers }}</div>
                    <div class="stat-label">Inactive Workers</div>
                </div>
            </div>
        </div>

        <!-- Assigned Workers -->
        @if($position->workers()->count() > 0)
            <div class="card">
                <h2>👷 Assigned Workers ({{ $position->workers()->count() }})</h2>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Worker</th>
                                <th>Status</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Hired Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($position->workers as $worker)
                                <tr>
                                    <td><strong>{{ $worker->first_name }} {{ $worker->last_name }}</strong></td>
                                    <td>
                                        <span class="badge {{ $worker->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                            {{ ucfirst($worker->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $worker->phone ?? '—' }}</td>
                                    <td>{{ $worker->email ?? '—' }}</td>
                                    <td>{{ $worker->hired_at?->format('M d, Y') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('workers.show', $worker) }}">View Profile</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card">
                <h2>👷 Assigned Workers</h2>
                <div class="empty-state">
                    <p>No workers assigned to this position yet.</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
