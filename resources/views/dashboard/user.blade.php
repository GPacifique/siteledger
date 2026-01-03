<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f5f7fa; color: #333; }
        .container { max-width: 1000px; margin: 0 auto; padding: 2rem; }
        .header { margin-bottom: 1.5rem; }
        .header h1 { font-size: 1.5rem; color: #1f2937; }
        .header p { color: #6b7280; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1rem; }
        .card .label { font-size: 0.85rem; color: #6b7280; }
        .card .value { margin-top: 0.5rem; font-size: 1.75rem; font-weight: 700; color: #111827; }
        .actions a { display: inline-block; margin-right: 0.5rem; margin-top: 0.5rem; padding: 0.5rem 0.75rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-secondary { background: #111827; color: white; }
        .list { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .list-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem; border-bottom: 1px solid #e5e7eb; }
        .list-header h2 { font-size: 1rem; color: #1f2937; }
        .list-header a { font-size: 0.9rem; color: #4f46e5; text-decoration: none; }
        .list-content { padding: 1rem; }
        .list-item { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb; }
        .list-item:last-child { border-bottom: none; }
        .item-title { font-size: 0.95rem; color: #111827; }
        .item-meta { font-size: 0.8rem; color: #6b7280; }
        .item-actions a { font-size: 0.85rem; color: #4f46e5; text-decoration: none; margin-left: 0.5rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome</h1>
            <p>Here’s a quick overview of your projects.</p>
        </div>

        <div class="grid">
            <div class="card">
                <div class="label">Total Projects</div>
                <div class="value">{{ number_format($projectsCount) }}</div>
            </div>
            <div class="card">
                <div class="label">Projects This Month</div>
                <div class="value">{{ number_format($projectsThisMonth) }}</div>
            </div>
            <div class="card">
                <div class="label">Quick Actions</div>
                <div class="actions">
                    <a href="{{ route('projects.index') }}" class="btn-primary">View Projects</a>
                    <a href="{{ route('notifications.index') }}" class="btn-secondary">Notifications</a>
                </div>
            </div>
        </div>

        <div class="list">
            <div class="list-header">
                <h2>Recent Projects</h2>
                <a href="{{ route('projects.index') }}">View all</a>
            </div>
            <div class="list-content">
                @if($recentProjects->isEmpty())
                    <p class="item-meta">No recent projects found.</p>
                @else
                    @foreach($recentProjects as $project)
                        <div class="list-item">
                            <div>
                                <div class="item-title">{{ $project->name }}</div>
                                <div class="item-meta">Created {{ optional($project->created_at)->diffForHumans() }}</div>
                            </div>
                            <div class="item-actions">
                                <a href="{{ route('projects.show', $project) }}">Open</a>
                                <a href="{{ route('projects.edit', $project) }}">Edit</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</body>
</html>
