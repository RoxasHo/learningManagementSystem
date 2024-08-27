<!-- resources/views/superuser/moderatorRejection.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Reject Moderator</h2>
    <form action="{{ route('superuser.rejectModerator', $moderator->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="rejection_reason">Rejection Reason:</label>
            <textarea name="rejection_reason" id="rejection_reason" rows="4" required class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-danger">Reject Moderator</button>
    </form>
</div>
@endsection
