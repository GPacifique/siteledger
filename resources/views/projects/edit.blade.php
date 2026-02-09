@extends('layouts.admin')

@section('title', 'Edit Project — SiteLedger')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
<style>
    :root {
      --primary: #667eea;
      --primary-dark: #5a67d8;
      --secondary: #764ba2;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --bg: #f8fafc;
      --card: #ffffff;
      --border: #e2e8f0;
      --text: #1f2937;
      --text-muted: #6b7280;
      --text-light: #9ca3af;
    }

    * { box-sizing: border-box; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background: var(--bg);
      margin: 0;
      color: var(--text);
      line-height: 1.6;
    }

    .container {
      max-width: 1000px;
      margin: 2rem auto;
      padding: 1rem;
    }

    .header {
      background: var(--card);
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 16px rgba(16, 24, 40, 0.08);
      border-left: 4px solid var(--primary);
    }

    .header h1 {
      color: var(--primary);
      margin: 0;
      font-size: 1.75rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .header p {
      color: var(--text-muted);
      margin: 0.5rem 0 0 0;
      font-size: 1.1rem;
    }

    .form-card {
      background: var(--card);
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 4px 16px rgba(16, 24, 40, 0.08);
      border: 1px solid var(--border);
    }

    .alert {
      margin-bottom: 1.5rem;
      padding: 1rem;
      border-radius: 8px;
      border-left: 4px solid var(--danger);
      background: #fef2f2;
      color: #7f1d1d;
    }

    .alert-title {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .alert ul {
      margin: 0;
      padding-left: 1.25rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-row {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .form-section {
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .form-section h3 {
      margin: 0 0 1rem 0;
      color: var(--primary);
      font-size: 1.1rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--text);
      font-size: 0.9rem;
    }

    .required {
      color: var(--danger);
    }

    input, select, textarea {
      width: 100%;
      padding: 0.75rem;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.2s ease;
      background: white;
    }

    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea {
      min-height: 120px;
      resize: vertical;
    }

    .input-group {
      display: flex;
      gap: 0.5rem;
      align-items: end;
    }

    .input-group input {
      flex: 1;
    }

    .btn {
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.95rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      transition: all 0.2s ease;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
      background: white;
      border: 2px solid var(--border);
      color: var(--text);
    }

    .btn-secondary:hover {
      background: #f8fafc;
      border-color: var(--primary);
      color: var(--primary);
    }

    .btn-generate {
      background: var(--success);
      color: white;
      padding: 0.75rem 1rem;
      white-space: nowrap;
    }

    .btn-generate:hover {
      background: #059669;
      transform: translateY(-1px);
    }

    .form-actions {
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border);
      display: flex;
      gap: 1rem;
      justify-content: space-between;
      align-items: center;
    }

    .actions-left {
      display: flex;
      gap: 1rem;
    }

    .currency-symbol {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-weight: 600;
    }

    .currency-input {
      padding-left: 3rem !important;
    }

    .currency-wrapper {
      position: relative;
    }

    @media (max-width: 768px) {
      .container {
        margin: 1rem;
        padding: 0.5rem;
      }

      .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      .form-actions {
        flex-direction: column;
        gap: 1rem;
      }

      .actions-left {
        flex-direction: column;
        width: 100%;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }
    }

    .project-info {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
    }

    .project-info h4 {
      margin: 0 0 0.5rem 0;
      font-size: 1.1rem;
    }

    .project-info p {
      margin: 0;
      opacity: 0.9;
      font-size: 0.95rem;
    </style>
@endsection

@section('content')

    <div class="container">
    <div class="header">
      <h1>📝 Edit Project</h1>
      <p>Update project details, phase values, and assignments</p>
    </div>

    <div class="project-info">
      <h4>🏗️ {{ $project->name ?? 'Project' }}</h4>
      <p>Project Code: {{ $project->project_code ?? 'Not assigned' }} • Created: {{ $project->created_at?->format('M j, Y') ?? 'Unknown' }}</p>
    </div>

    <div class="form-card">
      @if($errors->any())
        <div class="alert">
          <div class="alert-title">❌ Please fix the following issues:</div>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-section">
          <h3>📋 Basic Information</h3>

          <div class="form-row">
            <div class="form-group">
              <label>Client <span class="required">*</span></label>
              <select name="client_id" required>
                <option value="">— Select a client —</option>
                @foreach($clients as $client)
                  <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>Project Name <span class="required">*</span></label>
              <input type="text" name="name" value="{{ old('name', $project->name) }}" required placeholder="Enter project name">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Project Code</label>
              <div class="input-group">
                <input type="text" id="project_code" name="project_code" value="{{ old('project_code', $project->project_code) }}" placeholder="PRJ-XXXXXX">
                <button type="button" id="generateCode" class="btn btn-generate">🎲 Generate</button>
              </div>
            </div>

            <div class="form-group">
              <label>Contract Value</label>
              <div class="currency-wrapper">
                <span class="currency-symbol">RWF</span>
                <input type="number" name="contract_value" class="currency-input" step="0.01" value="{{ old('contract_value', $project->contract_value) }}" placeholder="0.00">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Provide a detailed description of the project...">{{ old('description', $project->description) }}</textarea>
          </div>
        </div>

        <div class="form-section">
          <h3>📅 Timeline</h3>

          <div class="form-row">
            <div class="form-group">
              <label>Start Date</label>
              <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
              <label>End Date</label>
              <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
            </div>
          </div>
        </div>

        <div class="form-section">
          <h3>💰 Phase Values</h3>

          <div class="form-row">
            <div class="form-group">
              <label>Design Phase Value</label>
              <div class="currency-wrapper">
                <span class="currency-symbol">RWF</span>
                <input type="number" name="design_phase_value" class="currency-input" step="0.01" value="{{ old('design_phase_value', $project->design_phase_value) }}" placeholder="0.00">
              </div>
            </div>

            <div class="form-group">
              <label>Execution Phase Value</label>
              <div class="currency-wrapper">
                <span class="currency-symbol">RWF</span>
                <input type="number" name="execution_phase_value" class="currency-input" step="0.01" value="{{ old('execution_phase_value', $project->execution_phase_value) }}" placeholder="0.00">
              </div>
            </div>
          </div>
        </div>

        <div class="form-section">
          <h3>👥 Team Assignment</h3>

          <div class="form-group">
            <label>Project Manager</label>
            <select name="manager_id">
              <option value="">— Select a project manager —</option>
              @foreach($workers as $worker)
                <option value="{{ $worker->id }}" {{ old('manager_id', $project->manager_id) == $worker->id ? 'selected' : '' }}>
                  {{ $worker->first_name }} {{ $worker->last_name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-actions">
          <div class="actions-left">
            <button type="submit" class="btn btn-primary">💾 Save Changes</button>
            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary">❌ Cancel</a>
          </div>

          <a href="{{ route('projects.index') }}" class="btn btn-secondary">📋 All Projects</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Generate project code
    document.getElementById('generateCode')?.addEventListener('click', function() {
      const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      let code = 'PRJ-';
      for (let i = 0; i < 6; i++) {
        code += chars[Math.floor(Math.random() * chars.length)];
      }
      document.getElementById('project_code').value = code;
    });

    // Auto-calculate total from phases
    const designInput = document.querySelector('input[name="design_phase_value"]');
    const executionInput = document.querySelector('input[name="execution_phase_value"]');
    const contractInput = document.querySelector('input[name="contract_value"]');

    function updateTotal() {
      const design = parseFloat(designInput.value) || 0;
      const execution = parseFloat(executionInput.value) || 0;
      const total = design + execution;

      if (total > 0 && !contractInput.value) {
        contractInput.value = total.toFixed(2);
      }
    }

    designInput?.addEventListener('blur', updateTotal);
    executionInput?.addEventListener('blur', updateTotal);

    // Form validation
    document.querySelector('form')?.addEventListener('submit', function(e) {
      const requiredFields = this.querySelectorAll('[required]');
      let hasErrors = false;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.style.borderColor = 'var(--danger)';
          hasErrors = true;
        } else {
          field.style.borderColor = 'var(--border)';
        }
      });

      if (hasErrors) {
        e.preventDefault();
        alert('Please fill in all required fields');
      }
    </script>
@endsection
