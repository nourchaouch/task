@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2>Admin Dashboard</h2>
    <div class="alert alert-info mt-3">Welcome, Admin! Use the links below to manage users or change your password.</div>
    <div class="list-group mt-4">
        <a href="{{ url('admin/users') }}" class="list-group-item list-group-item-action">Manage Users</a>
        <a href="{{ url('admin/password') }}" class="list-group-item list-group-item-action">Change Password</a>
    </div>
</div>
@endsection 