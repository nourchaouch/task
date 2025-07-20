@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2>Change Password</h2>
    <form action="{{ url('admin/password') }}" method="POST" class="mt-4" style="max-width:400px;">
        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
        <a href="{{ url('admin') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 