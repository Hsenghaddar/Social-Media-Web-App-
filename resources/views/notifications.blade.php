@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                    <span class="fw-semibold">All Notifications</span>
                    @if($notifications->isNotEmpty())
                        <form method="POST" action="{{ route('notifications.clear') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light">Clear All</button>
                        </form>
                    @endif
                </div>

                <div class="card-body p-0">
                    @if($notifications->isEmpty())
                        <div class="p-4 text-center text-muted">
                            You have no notifications.
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="fw-semibold">
                                        <a href="{{ $notification->link}}" class="text-decoration-none text-dark">
                                            {{ $notification->message }}
                                        </a>
                                    </div>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
