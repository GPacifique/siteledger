<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Project — SiteLedger</title>
  <style>
    :root{--bg:#f6f7fb;--card:#fff;--accent:#667eea;--muted:#6b7280}
    *{box-sizing:border-box}
    body{font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;background:var(--bg);margin:0;color:#111}
    .wrap{max-width:900px;margin:28px auto;padding:18px}
    .card{background:var(--card);padding:20px;border-radius:10px;box-shadow:0 8px 24px rgba(16,24,40,0.06)}
    h1{margin:0 0 8px;font-size:1.3rem;color:var(--accent)}
    .muted{color:var(--muted);font-size:0.95rem}
    form{margin-top:14px}
    .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
    @media(max-width:720px){.grid{grid-template-columns:1fr}}
    label{display:block;font-weight:600;margin-bottom:6px}
    input,select,textarea{width:100%;padding:10px;border:1px solid #e6e9f2;border-radius:8px}
    textarea{min-height:110px}
    .row{display:flex;gap:10px;align-items:center}
    .actions{display:flex;gap:10px;margin-top:16px}
    .btn{padding:10px 14px;border-radius:8px;border:none;cursor:pointer;font-weight:700}
    .btn-primary{background:linear-gradient(135deg,var(--accent),#764ba2);color:#fff}
    .btn-ghost{background:transparent;border:1px solid #e6e9f2;color:var(--accent)}
    .note{font-size:0.9rem;color:var(--muted);margin-top:6px}
  </style>
</head>
<body>
@include('components.navbar')
<div class="wrap">
  <div class="card">
    <h1>New Project</h1>
    <div class="muted">Create a project and link initial phases automatically</div>

    @if($errors->any())
      <div style="margin-top:12px;padding:12px;background:#fff1f2;border-radius:8px;color:#7f1d1d">
        <strong>There are validation errors</strong>
        <ul style="margin:8px 0 0 16px">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST">
      @csrf

      <div class="grid" style="margin-top:12px">
        <div>
          <label>Client</label>
          <select name="client_id">
            <option value="">— Select client —</option>
            @foreach($clients as $client)
              <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label>Project Name *</label>
          <input name="name" required value="{{ old('name') }}">
        </div>
      </div>

      <div style="margin-top:12px">
        <label>Project Code</label>
        <input name="project_code" placeholder="Optional" value="{{ old('project_code') }}">
      </div>

      <div style="margin-top:12px">
        <label>Description</label>
        <textarea name="description">{{ old('description') }}</textarea>
      </div>

      <div class="grid" style="margin-top:12px">
        <div>
          <label>Start Date</label>
          <input type="date" name="start_date" value="{{ old('start_date') }}">
        </div>
        <div>
          <label>End Date</label>
          <input type="date" name="end_date" value="{{ old('end_date') }}">
        </div>
      </div>

      <div class="grid" style="margin-top:12px">
        <div>
          <label>Contract Value (RWF) *</label>
          <input type="number" name="contract_value" step="0.01" required value="{{ old('contract_value') }}">
        </div>
        <div>
          <label>Project Type *</label>
          <select name="project_type" id="project_type" required onchange="togglePhases()">
            <option value="">— Select —</option>
            <option value="DESIGN">Design</option>
            <option value="EXECUTION">Execution</option>
            <option value="DESIGN_EXECUTION">Design & Execution</option>
          </select>
        </div>
      </div>

      <div id="phaseInputs" style="margin-top:12px">
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <div style="flex:1;min-width:220px">
            <label>Design Phase Value</label>
            <input name="design_phase_value" step="0.01" value="{{ old('design_phase_value') }}">
            <div class="note">Optional — shown when project type includes design</div>
          </div>
          <div style="flex:1;min-width:220px">
            <label>Execution Phase Value</label>
            <input name="execution_phase_value" step="0.01" value="{{ old('execution_phase_value') }}">
            <div class="note">Optional — shown when project type includes execution</div>
          </div>
        </div>
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">Create Project</button>
        <a href="{{ route('projects.index') }}" class="btn btn-ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>

<script>
  function togglePhases(){
    const type=document.getElementById('project_type').value;const ph=document.getElementById('phaseInputs');
    if(type==='DESIGN'){ph.style.display='block'}else if(type==='EXECUTION'){ph.style.display='block'}else if(type==='DESIGN_EXECUTION'){ph.style.display='block'}else{ph.style.display='block'}
  }
  document.addEventListener('DOMContentLoaded',togglePhases);
</script>
</body>
</html>
