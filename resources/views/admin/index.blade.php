@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Admin Dashboard</h2>
    <div class="bg-blue-100 text-blue-800 px-4 py-3 rounded mb-4">Welcome, Admin! Use the links below to manage users or change your password.</div>
    <div class="flex flex-col space-y-2">
        <a href="{{ url('admin/users') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">Manage Users</a>
        <a href="{{ url('admin/password') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded hover:bg-gray-300 transition">Change Password</a>
    </div>
</div>
@endsection 