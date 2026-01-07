<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - SiteLedger</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: linear-gradient(135deg, #1e1e2e 0%, #282c34 100%); min-height: 100vh; color: #fff; }
        .navbar { background: rgba(0,0,0,0.3); padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .navbar h1 { font-size: 24px; font-weight: 700; color: #667eea; }
        .navbar-links { display: flex; gap: 20px; }
        .navbar-links a { color: #aaa; text-decoration: none; font-weight: 500; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        h2 { font-size: 28px; margin-bottom: 20px; }
        .chips { display: flex; gap: 10px; margin-bottom: 20px; align-items: center; flex-wrap: wrap; }
        .chip { padding: 8px 14px; border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; color: #fff; text-decoration: none; }
        .chip.active { background: #667eea; border-color: #667eea; }
        .stats { display: flex; gap: 12px; margin-bottom: 20px; }
        .stat { background: rgba(255,255,255,0.06); padding: 10px 14px; border-radius: 8px; }
        .list { display: grid; gap: 12px; }
        .item { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); padding: 16px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; }
        .meta { color: #ccc; font-size: 12px; margin-top: 6px; }
        .badge { display:inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; }
        .badge-unread { background: #ff6b6b; color:#fff; }
        .badge-read { background: #4c6ef5; color:#fff; }
        .actions { display:flex; gap:8px; }
        .btn { padding: 8px 12px; border-radius: 6px; text-decoration:none; color:#fff; border:1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); }
        .btn-primary { background:#667eea; border-color:#667eea; }
        .pagination { display:flex; gap:8px; margin-top:20px; }
        .pagination a { color:#fff; text-decoration:none; padding:6px 10px; border:1px solid rgba(255,255,255,0.2); border-radius:6px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🔔 Notifications</h1>
        <div class="navbar-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('super-admin.dashboard') }}">Super Admin</a>
        </div>
    </div>

    <div class="container">
        <h2>Your Notifications</h2>
        <div class="chips">
            <a href="{{ route('notifications.index', ['scope' => 'all']) }}" class="chip {{ $scope === 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('notifications.index', ['scope' => 'unread']) }}" class="chip {{ $scope === 'unread' ? 'active' : '' }}">Unread</a>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="chip" style="cursor:pointer; background: rgba(102,126,234,0.15); border-color:#667eea;">Mark All Read</button>
            </form>
        </div>
        <div class="stats">
            <div class="stat">Total: {{ $counts['all'] }}</div>
            <div class="stat">Unread: {{ $counts['unread'] }}</div>
        </div>

        @if(session('success'))
            <div class="stat" style="background:#1b5e20;border-color:#1b5e20;">{{ session('success') }}</div>
        @endif

        <div class="list">
            @forelse($notifications as $n)
                <div class="item">
                    <div>
                        <div style="font-weight:600;">{{ data_get($n->data, 'title', class_basename($n->type)) }}</div>
                        <div class="meta">{{ data_get($n->data, 'message') }}</div>
                        <div class="meta">Created: {{ $n->created_at->format('M d, Y g:i A') }}</div>
                        <span class="badge {{ is_null($n->read_at) ? 'badge-unread' : 'badge-read' }}">{{ is_null($n->read_at) ? 'Unread' : 'Read' }}</span>
                    </div>
                    <div class="actions">
                        @if(is_null($n->read_at))
                        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Mark Read</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('notifications.destroy', $n->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="color:#aaa;">No notifications</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $notifications->links() }}
        </div>
    </div>
</body>
</html>
