@extends('layouts.admin')

@section('title', 'Create Project — SiteLedger')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
<style>
  .wrap{max-width:900px;margin:28px auto;padding:18px}
  .card {background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.6)) !important; border: 2px solid rgba(102, 126, 234, 0.15); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4) !important;}
  h1{margin:0 0 8px;font-size:2rem;font-weight:700;color:#ffffff}
  h1::before{content:'📋 ';font-size:2.2rem;margin-right:0.5rem}
  .muted{color:#94a3b8;font-size:0.95rem}
  form{margin-top:14px}
  .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
  @media(max-width:720px){.grid{grid-template-columns:1fr}}
  label{display:block;font-weight:700;margin-bottom:8px;color:#cbd5e1}
  input,select,textarea{width:100%;padding:12px 16px;border:2px solid rgba(102,126,234,0.2);border-radius:10px;background:rgba(11,19,36,0.7);color:#e2e8f0;font-family:inherit;font-size:1rem;transition:all 0.3s ease}
  input::placeholder,select::placeholder,textarea::placeholder{color:#64748b}
  input:hover,select:hover,textarea:hover{border-color:rgba(102,126,234,0.4);background:rgba(11,19,36,0.9)}
  input:focus,select:focus,textarea:focus{outline:none;border-color:#667eea;background:rgba(11,19,36,1);box-shadow:0 0 0 4px rgba(102,126,234,0.15)}
  input:focus,select:focus,textarea:focus{color:#ffffff}
  textarea{min-height:110px}
  .row{display:flex;gap:10px;align-items:center}
  .actions{display:flex;gap:10px;margin-top:24px}
  .btn{padding:12px 24px;border-radius:10px;border:none;cursor:pointer;font-weight:700;font-size:1rem;transition:all 0.3s ease;flex:1}
  .btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;box-shadow:0 4px 12px rgba(102,126,234,0.2)}
  .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(102,126,234,0.35)}
  .btn-ghost{background:rgba(255,255,255,0.05);border:2px solid rgba(102,126,234,0.2);color:#cbd5e1}
  .btn-ghost:hover{background:rgba(102,126,234,0.1);border-color:rgba(102,126,234,0.4);color:#667eea;transform:translateY(-2px)}
  .note{font-size:0.9rem;color:#94a3b8;margin-top:8px;padding:8px;background:rgba(102,126,234,0.08);border-left:3px solid #667eea;border-radius:4px}
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="card">
    <h1>New Project</h1>
    <div class="muted">Create a project and link initial phases automatically</div>

    @if($errors->any())
      <div style="margin-top:12px;padding:16px;background:rgba(239,68,68,0.1);border-radius:8px;border-left:4px solid #fb7185;color:#fecaca">
        <strong style="color:#fb7185"><i class="fas fa-exclamation-circle"></i> There are validation errors</strong>
        <ul style="margin:12px 0 0 16px">
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
          <label><i class="fas fa-user"></i> Client</label>
          <select name="client_id">
            <option value="">— Select client —</option>
            @foreach($clients as $client)
              <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label><i class="fas fa-project-diagram"></i> Project Name *</label>
          <input name="name" required value="{{ old('name') }}">
        </div>
      </div>

      <div style="margin-top:12px">
        <label><i class="fas fa-barcode"></i> Project Code</label>
        <input name="project_code" placeholder="Optional" value="{{ old('project_code') }}">
      </div>

      <div style="margin-top:12px">
        <label><i class="fas fa-file-alt"></i> Description</label>
        <textarea name="description">{{ old('description') }}</textarea>
      </div>

      <div class="grid" style="margin-top:12px">
        <div>
          <label><i class="fas fa-calendar-alt"></i> Start Date</label>
          <input type="date" name="start_date" value="{{ old('start_date') }}">
        </div>
        <div>
          <label><i class="fas fa-calendar-check"></i> End Date</label>
          <input type="date" name="end_date" value="{{ old('end_date') }}">
        </div>
      </div>

      <div class="grid" style="margin-top:12px">
        <div>
          <label><i class="fas fa-dollar-sign"></i> Contract Value (RWF) *</label>
          <input type="number" name="contract_value" step="0.01" required value="{{ old('contract_value') }}">
        </div>
        <div>
          <label><i class="fas fa-flag"></i> Project Type *</label>
          <select name="project_type" id="project_type" required onchange="togglePhases()">
            <option value="">— Select —</option>
            <option value="DESIGN" {{ old('project_type') === 'DESIGN' ? 'selected' : '' }}>Design</option>
            <option value="EXECUTION" {{ old('project_type') === 'EXECUTION' ? 'selected' : '' }}>Execution</option>
            <option value="DESIGN_EXECUTION" {{ old('project_type') === 'DESIGN_EXECUTION' ? 'selected' : '' }}>Design & Execution</option>
          </select>
        </div>
      </div>

      <div id="phaseInputs" style="margin-top:12px">
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <div style="flex:1;min-width:220px">
            <label><i class="fas fa-pen-fancy"></i> Design Phase Value</label>
            <input name="design_phase_value" step="0.01" value="{{ old('design_phase_value') }}">
            <div class="note">Optional — shown when project type includes design</div>
          </div>
          <div style="flex:1;min-width:220px">
            <label><i class="fas fa-hammer"></i> Execution Phase Value</label>
            <input name="execution_phase_value" step="0.01" value="{{ old('execution_phase_value') }}">
            <div class="note">Optional — shown when project type includes execution</div>
          </div>
        </div>
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> Create Project</button>
        <a href="{{ route('projects.index') }}" class="btn btn-ghost"><i class="fas fa-times-circle"></i> Cancel</a>
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
@endsection
