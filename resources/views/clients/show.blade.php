<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $client->name }} - CSMS</title>
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
        .projects-section {
            margin-top: 2rem;
        }
        .projects-section h2 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .project-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        .project-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .project-card-link {
            display: block;
            padding: 1.5rem;
            color: inherit;
            text-decoration: none;
            cursor: pointer;
        }
        .project-card h3 {
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
            color: #333;
        }
        .project-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .project-meta-item {
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .project-meta-label {
            color: #999;
            font-size: 0.8rem;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }
        .project-meta-value {
            color: #333;
            font-weight: 600;
        }
        .no-projects {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            color: #999;
        }
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        .status-badge.completed {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.in_progress {
            background: #fff3cd;
            color: #856404;
        }
        .status-badge.pending {
            background: #e2e3e5;
            color: #383d41;
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

        <!-- Associated Projects Section -->
        <div class="projects-section">
            <h2>🏗️ Associated Projects</h2>

            @if($projects->count() > 0)
                <div class="projects-grid">
                    @foreach($projects as $project)
                        <div class="project-card">
                            <a href="{{ route('projects.show', $project) }}" class="project-card-link">
                                <h3>{{ $project->name }}</h3>

                                <span class="status-badge {{ strtolower($project->status) }}">
                                    @if($project->status === 'completed')
                                        ✅ Completed
                                    @elseif($project->status === 'in_progress')
                                        ⏱️ In Progress
                                    @else
                                        ⏳ Pending
                                    @endif
                                </span>

                                <div class="project-meta">
                                    <div class="project-meta-item">
                                        <div class="project-meta-label">Contract Value</div>
                                        <div class="project-meta-value">RWF {{ number_format($project->contract_value, 0) }}</div>
                                    </div>
                                    <div class="project-meta-item">
                                        <div class="project-meta-label">Amount Paid</div>
                                        <div class="project-meta-value">RWF {{ number_format($project->amount_paid, 0) }}</div>
                                    </div>
                                    <div class="project-meta-item">
                                        <div class="project-meta-label">Tasks</div>
                                        <div class="project-meta-value">{{ $project->tasks->count() }}</div>
                                    </div>
                                    <div class="project-meta-item">
                                        <div class="project-meta-label">Remaining</div>
                                        <div class="project-meta-value">RWF {{ number_format($project->amount_remaining, 0) }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-projects">
                    <p>No projects associated with this client yet.</p>
                </div>
            @endif
        </div>
</body>
</html>
