<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Project — SiteLedger</title>
  <style>
    :root{--bg:#f6f7fb;--card:#fff;--accent:#667eea;--muted:#6b7280}
    body{font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);margin:0;color:#111}
    .wrap{max-width:1000px;margin:24px auto;padding:18px}
    .card{background:var(--card);padding:18px;border-radius:10px;box-shadow:0 8px 24px rgba(16,24,40,0.06)}
    h1{color:var(--accent);margin:0;font-size:1.2rem}
    .muted{color:var(--muted);font-size:0.95rem}
    form{margin-top:12px}
    .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
    @media(max-width:760px){.grid{grid-template-columns:1fr}}
    label{display:block;margin-bottom:6px;font-weight:600}
    input,select,textarea{width:100%;padding:10px;border:1px solid #e6e9f2;border-radius:8px}
    textarea{min-height:110px}
    .actions{margin-top:14px;display:flex;gap:8px}
    .btn{padding:10px 14px;border-radius:8px;border:none;cursor:pointer;font-weight:700}
    .btn-primary{background:linear-gradient(135deg,var(--accent),#764ba2);color:#fff}
    .btn-ghost{background:transparent;border:1px solid #e6e9f2;color:var(--accent)}
  </style>
</head>
<body>
@include('components.navbar')
<div class="wrap">
  <div class="card">
    <h1>Edit Project</h1>
    <div class="muted">Update details and phase values</div>

    @if($errors->any())
      <div style="margin-top:10px;padding:10px;background:#fff1f2;border-radius:8px;color:#7f1d1d">
        <strong>Validation issues</strong>
        <ul style="margin:8px 0 0 16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
      @csrf @method('PUT')

      <div class="grid">
        <div>
          <label>Client</label>
          <select name="client_id" required>
            <option value="">— Select client —</option>
            @foreach($clients as $c)
              <option value="{{ $c->id }}" {{ old('client_id', $project->client_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label>Project Name</label>
          <input name="name" value="{{ old('name', $project->name) }}" required>
        </div>
      </div>

      <div style="margin-top:10px" class="grid">
        <div>
          <label>Project Code</label>
          <div style="display:flex;gap:8px"><input id="project_code" name="project_code" value="{{ old('project_code', $project->project_code) }}"><button id="genCode" class="btn btn-ghost" type="button">Generate</button></div>
        </div>
        <div>
          <label>Contract Value (RWF)</label>
          <input type="number" name="contract_value" step="0.01" value="{{ old('contract_value', $project->contract_value) }}">
        </div>
      </div>

      <div style="margin-top:10px">
        <label>Description</label>
        <textarea name="description">{{ old('description', $project->description) }}</textarea>
      </div>

      <div style="margin-top:12px" class="grid">
        <div>
          <label>Start Date</label>
          <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
        </div>
        <div>
          <label>End Date</label>
          <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
        </div>
      </div>

      <div style="margin-top:12px" class="grid">
        <div>
          <label>Design Phase Value</label>
          <input name="design_phase_value" step="0.01" value="{{ old('design_phase_value', $project->design_phase_value) }}">
        </div>
        <div>
          <label>Execution Phase Value</label>
          <input name="execution_phase_value" step="0.01" value="{{ old('execution_phase_value', $project->execution_phase_value) }}">
        </div>
      </div>

      <div style="margin-top:12px">
        <label>Project Manager</label>
        <select name="manager_id">
          <option value="">— select —</option>
          @foreach($workers as $w)
            <option value="{{ $w->id }}" {{ old('manager_id', $project->manager_id) == $w->id ? 'selected' : '' }}>{{ $w->first_name }} {{ $w->last_name }}</option>
          @endforeach
        </select>
      </div>

      <div class="actions">
        <button class="btn btn-primary" type="submit">Save changes</button>
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('genCode')?.addEventListener('click',()=>{
    const chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';let out='PRJ-';for(let i=0;i<6;i++)out+=chars[Math.floor(Math.random()*chars.length)];document.getElementById('project_code').value=out;
  });
</script>
</body>
</html>
