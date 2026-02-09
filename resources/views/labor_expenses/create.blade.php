@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
<style>
    .container { max-width: 800px; margin: 2rem auto; padding: 2rem; }
    .card { background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(245, 247, 250, 0.95) 100%); padding: 2.5rem; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); border: 1px solid rgba(102, 126, 234, 0.1); }
    h2 { font-size: 2rem; margin-bottom: 1.5rem; color: #1a202c; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; }
    .mb-3 { margin-bottom: 1.5rem; }
    .form-label { display: block; font-weight: 700; margin-bottom: 0.75rem; color: #2d3748; font-size: 0.95rem; }
    .form-control { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 1rem; background: #ffffff; color: #1a202c; transition: all 0.3s ease; }
    .form-control::placeholder { color: #cbd5e1; }
    .form-control:hover { border-color: #667eea; background: #f8f9ff; }
    .form-control:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15); background: #ffffff; }
    .form-control:read-only { background: #f1f5f9; cursor: not-allowed; }
    .calculation-note { font-size: 0.9rem; color: #94a3b8; padding: 12px 16px; background: rgba(102, 126, 234, 0.08); border-left: 3px solid #667eea; border-radius: 6px; margin-top: 8px; }
    .btn { padding: 12px 24px; border-radius: 10px; border: none; cursor: pointer; font-weight: 700; font-size: 1rem; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
    .btn-success { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4); }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <h2>💰 Add Labor Expense</h2>
        <form method="POST" action="{{ route('labor_expenses.store') }}" id="labor-expense-form">
            @csrf
            <div class="mb-3">
                <label for="laborer_id" class="form-label"><i class="fas fa-user"></i> Laborer *</label>
                <select name="laborer_id" id="laborer_id" class="form-control" required>
                    <option value="">Select Laborer</option>
                    @foreach($laborers as $laborer)
                        <option value="{{ $laborer->id }}" {{ old('laborer_id') == $laborer->id ? 'selected' : '' }}>{{ $laborer->name }} ({{ $laborer->category }})</option>
                    @endforeach
                </select>
                @error('laborer_id')<div style="color:#e53e3e;font-size:0.85rem;margin-top:0.5rem"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="date" class="form-label"><i class="fas fa-calendar-alt"></i> Date *</label>
                <input type="date" name="date" id="date" class="form-control" required value="{{ old('date') }}">
                @error('date')<div style="color:#e53e3e;font-size:0.85rem;margin-top:0.5rem"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="units" class="form-label"><i class="fas fa-clock"></i> Units (days/hours/tasks) *</label>
                <input type="number" step="0.01" name="units" id="units" class="form-control" required value="{{ old('units') }}" placeholder="Enter number of units">
                @error('units')<div style="color:#e53e3e;font-size:0.85rem;margin-top:0.5rem"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="rate" class="form-label"><i class="fas fa-dollar-sign"></i> Rate per Unit (RWF) *</label>
                <input type="number" step="0.01" name="rate" id="rate" class="form-control" required value="{{ old('rate') }}" placeholder="0.00">
                @error('rate')<div style="color:#e53e3e;font-size:0.85rem;margin-top:0.5rem"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label"><i class="fas fa-calculator"></i> Total Amount (RWF) *</label>
                <input type="number" step="0.01" name="amount" id="amount" class="form-control" readonly value="{{ old('amount') }}">
                <div class="calculation-note">
                    <i class="fas fa-info-circle"></i> Automatically calculated: Units × Rate
                </div>
                @error('amount')<div style="color:#e53e3e;font-size:0.85rem;margin-top:0.5rem"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
            </div>
            <div style="display:flex;gap:1rem;margin-top:2rem">
                <button type="submit" class="btn btn-success" style="flex:1"><i class="fas fa-check-circle"></i> Add Expense</button>
                <a href="{{ route('labor_expenses.index') }}" class="btn" style="flex:1;background:#cbd5e1;color:#1a202c;box-shadow:0 2px 8px rgba(0,0,0,0.08)"><i class="fas fa-times-circle"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('units').addEventListener('input', calculateAmount);
    document.getElementById('rate').addEventListener('input', calculateAmount);
    function calculateAmount() {
        var units = parseFloat(document.getElementById('units').value) || 0;
        var rate = parseFloat(document.getElementById('rate').value) || 0;
        document.getElementById('amount').value = (units * rate).toFixed(2);
    }
</script>
@endsection
