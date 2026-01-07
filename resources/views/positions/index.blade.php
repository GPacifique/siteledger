<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Positions - SiteLedger</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .page-header h1 {
            font-size: 2.2rem;
            color: #333;
        }
        .page-header p {
            color: #666;
            font-size: 1rem;
            flex: 1;
            min-width: 250px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
            text-align: center;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #999;
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        .category-section {
            margin-bottom: 3rem;
        }
        .category-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
        }
        .positions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .position-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-top: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        .position-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .position-card h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }
        .position-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 1rem 0;
            font-size: 0.9rem;
        }
        .position-meta-item {
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .position-meta-label {
            color: #999;
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }
        .position-meta-value {
            color: #333;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .badge-active {
            background: #d4edda;
            color: #155724;
        }
        .badge-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-category {
            background: #e7f3ff;
            color: #0066cc;
        }
        .position-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }
        .position-actions a {
            flex: 1;
            text-align: center;
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: background 0.3s;
        }
        .position-actions a:hover {
            background: #5568d3;
        }
        .empty-message {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .positions-grid {
                grid-template-columns: 1fr;
            }
            .position-meta {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <div>
                <h1>👔 Worker Positions</h1>
                <p>Manage job positions and roles</p>
            </div>
            <a href="{{ route('positions.create') }}" class="btn btn-primary">+ Add Position</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $positions->count() }}</div>
                <div class="stat-label">Total Positions</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $positions->where('is_active', true)->count() }}</div>
                <div class="stat-label">Active Positions</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $positionsByCategory->count() }}</div>
                <div class="stat-label">Categories</div>
            </div>
        </div>

        <!-- Positions by Category -->
        @forelse($positionsByCategory as $category => $categoryPositions)
            <div class="category-section">
                <h2 class="category-title">{{ $category ?? 'Uncategorized' }}</h2>
                <div class="positions-grid">
                    @foreach($categoryPositions as $position)
                        <div class="position-card">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <h3>{{ $position->name }}</h3>
                                <span class="badge {{ $position->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    {{ $position->is_active ? '✓ Active' : '✗ Inactive' }}
                                </span>
                            </div>

                            @if($position->description)
                                <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">{{ $position->description }}</p>
                            @endif

                            <div class="position-meta">
                                <div class="position-meta-item">
                                    <div class="position-meta-label">Seniority Level</div>
                                    <div class="position-meta-value">{{ WorkerPosition::seniorityLevels()[$position->seniority_level] ?? 'Standard' }}</div>
                                </div>
                                <div class="position-meta-item">
                                    <div class="position-meta-label">Assigned Workers</div>
                                    <div class="position-meta-value">{{ $position->workers()->count() }}</div>
                                </div>
                                @if($position->hourly_rate)
                                    <div class="position-meta-item">
                                        <div class="position-meta-label">Hourly Rate</div>
                                        <div class="position-meta-value">RWF {{ number_format($position->hourly_rate, 0) }}</div>
                                    </div>
                                @endif
                                @if($position->daily_rate)
                                    <div class="position-meta-item">
                                        <div class="position-meta-label">Daily Rate</div>
                                        <div class="position-meta-value">RWF {{ number_format($position->daily_rate, 0) }}</div>
                                    </div>
                                @endif
                            </div>

                            <div class="position-actions">
                                <a href="{{ route('positions.show', $position) }}">View</a>
                                <a href="{{ route('positions.edit', $position) }}" style="background: #f39c12;">Edit</a>
                                <form action="{{ route('positions.destroy', $position) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Delete this position?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="width: 100%;">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="empty-message">
                <p>📋 No positions yet. <a href="{{ route('positions.create') }}" class="btn btn-primary" style="display: inline-block; margin-top: 1rem;">Create First Position</a></p>
            </div>
        @endforelse
    </div>
</body>
</html>
