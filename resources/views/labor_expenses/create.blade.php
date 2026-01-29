@extends('layouts.admin')
@section('content')
<div class="container">
    <h2>Add Labor Expense</h2>
    <form method="POST" action="{{ route('labor_expenses.store') }}" id="labor-expense-form">
        @csrf
        <div class="mb-3">
            <label for="laborer_id" class="form-label">Laborer</label>
            <select name="laborer_id" id="laborer_id" class="form-control" required>
                <option value="">Select Laborer</option>
                @foreach($laborers as $laborer)
                    <option value="{{ $laborer->id }}">{{ $laborer->name }} ({{ $laborer->category }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="units" class="form-label">Units (days/hours/tasks)</label>
            <input type="number" step="0.01" name="units" id="units" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="rate" class="form-label">Rate per Unit</label>
            <input type="number" step="0.01" name="rate" id="rate" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" readonly>
        </div>
        <button type="submit" class="btn btn-success">Add Expense</button>
    </form>
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
