<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - CSMS</title>
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
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .page-header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .page-header p {
            color: #666;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        thead {
            background: #27ae60;
            color: white;
        }
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        tbody tr {
            cursor: pointer;
            transition: background 0.3s ease;
        }
        tbody tr:hover {
            background: #f9f9f9;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
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
        .no-data {
            text-align: center;
            padding: 3rem;
            color: #999;
        }
        .add-button-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Responsive tables */
        .table-wrap { width: 100%; overflow-x: auto; }
        @media (max-width: 640px) {
            .container { padding: 1rem; }
            .page-header { padding: 1rem; }
            .add-button-container { justify-content: stretch; }
            .btn-primary { display: block; width: 100%; text-align: center; }
            table { font-size: 0.9rem; min-width: 700px; }
            th, td { padding: 0.6rem; }
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container">
        <div class="page-header">
            <h1>👥 Clients</h1>
            <p>Manage all clients and their information</p>
        </div>

        <div class="add-button-container">
            <a href="{{ route('clients.create') }}" class="btn-primary">+ Add Client</a>
        </div>

        @if($clients->count() > 0)
            <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr onclick="window.location='{{ route('clients.show', $client->id) }}'">
                            <td><strong>{{ $client->name }}</strong></td>
                            <td>{{ $client->contact_person ?? '-' }}</td>
                            <td>{{ $client->email ?? '-' }}</td>
                            <td>{{ $client->phone ?? '-' }}</td>
                            <td>{{ $client->address ?? '-' }}</td>
                            <td onclick="event.stopPropagation()">
                                <div class="action-buttons">
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-edit">Edit</a>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @else
            <div style="background: white; padding: 3rem; border-radius: 8px; text-align: center; color: #999;">
                <p>No clients found. <a href="{{ route('clients.create') }}" style="color: #667eea; text-decoration: none;">Create one now</a></p>
            </div>
        @endif
    </div>
</body>
</html>
