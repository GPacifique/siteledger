@extends('layouts.admin')
@section('content')
<div class="container">
    <h2>Laborers</h2>
    <a href="{{ route('laborers.create') }}" class="btn btn-primary mb-3">Add Laborer</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laborers as $laborer)
            <tr>
                <td>{{ $laborer->name }}</td>
                <td>{{ $laborer->category }}</td>
                <td>{{ ucfirst($laborer->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
