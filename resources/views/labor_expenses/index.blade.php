@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/colorful-theme.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>Labor Expenses</h2>
    <a href="{{ route('labor_expenses.create') }}" class="btn btn-primary mb-3">Add Labor Expense</a>
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="date" name="date" value="{{ request('date') }}" class="form-control" placeholder="Date">
            </div>
            <div class="col">
                <select name="laborer_id" class="form-control">
                    <option value="">All Laborers</option>
                    @foreach($expenses->pluck('laborer')->unique('id') as $laborer)
                        <option value="{{ $laborer->id }}" {{ request('laborer_id') == $laborer->id ? 'selected' : '' }}>{{ $laborer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="text" name="category" value="{{ request('category') }}" class="form-control" placeholder="Category">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Laborer</th>
                <th>Category</th>
                <th>Units</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->date }}</td>
                <td>{{ $expense->laborer->name }}</td>
                <td>{{ $expense->laborer->category }}</td>
                <td>{{ $expense->units }}</td>
                <td>{{ number_format($expense->rate, 2) }}</td>
                <td>{{ number_format($expense->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-end">Total</th>
                <th>{{ number_format($totalCost, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Laborers</h5>
                    <p class="card-text">{{ $totalLaborers }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Cost Today</h5>
                    <p class="card-text">{{ number_format($daily, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Cost This Week</h5>
                    <p class="card-text">{{ number_format($weekly, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Cost This Month</h5>
                    <p class="card-text">{{ number_format($monthly, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
