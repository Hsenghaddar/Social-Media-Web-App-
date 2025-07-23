@extends('layouts.app')

@section('title', $user->name . '\'s Profile')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">Member since {{ $user->created_at->format('F Y') }}</p>
                    @if (Auth::id() == $user->id)
                        <div class="mt-3 text-center">
                            <label class="form-check-label fw-bold mb-2" for="privacySwitch">
                                Profile Visibility
                            </label>
                            <form action="{{ route('User.privacy') }}" method="POST">
                                @csrf

                                <div class="form-check form-switch d-flex justify-content-center align-items-center gap-2">
                                    <input name="privacy" class="form-check-input" type="checkbox" role="switch"
                                        id="privacySwitch" {{ $user->private ? 'checked' : '' }}>
                                    <span id="privacyStatus">Public</span>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Save</button>
                            </form>
                        </div>
                    @endif

                    <div class="row text-center">
                        @auth
                            @if (Auth::id() != $user->id)
                                @if ($isRequestSent || Auth::user()->isFriends($user))
                                    <div class="col-6">
                                        <form action="{{ route('Friend.remove', $user->id) }}" method="POST">
                                            @csrf

                                            <button class="btn btn-m btn-primary">Unfriend</button>
                                        </form>
                                    </div>
                                @else
                                    <div class="col-6">
                                        <form action="{{ route('Friend.add', $user->id) }}" method="POST">
                                            @csrf

                                            <button class="btn btn-m btn-primary">Add Friend</button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        @endauth
                        <div class="col-6">
                            <h5>{{ $user->posts->count() }}</h5>
                            <small class="text-muted">Posts</small>
                        </div>
                        <div class="col-6">
                            <h5>{{ $user->likes->count() }}</h5>
                            <small class="text-muted">Likes Given</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- Friend Requests Section --}}
        @if (Auth::id() == $user->id)
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Friend Requests</h5>

                        <h6 class="mt-3 text-primary">Received Requests</h6>
                        <ul class="list-group mb-3">
                            @if (empty($receivedRequests->first()))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>No Received requests</span>
                                </li>
                            @endif
                            @foreach ($receivedRequests as $request)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $request->sender->name }}</span>
                                    <div>
                                        <form style="display: inline"
                                            action="{{ route('Friend.accept', $request->sender_id) }}" method="POST">
                                            @csrf

                                            <button class="btn btn-sm btn-success">Accept</button>
                                        </form>
                                        <form style="display: inline"
                                            action="{{ route('Friend.decline', $request->sender_id) }}" method="POST">
                                            @csrf

                                            <button class="btn btn-sm btn-danger ms-1">Decline</button>
                                        </form>

                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <h6 class="mt-3 text-secondary">Sent Requests</h6>
                        <ul class="list-group">
                            @if (empty($sentRequests->first()))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>No sent requests</span>
                                </li>
                            @endif
                            @foreach ($sentRequests as $request)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $request->reciever->name }}</span>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-4 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4 text-center" style="font-weight: bold;">
                        <i class="fas fa-users me-2"></i>Friends
                    </h5>
                    @if (empty($user->friends->first()))
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">No friends yet</h6>

                            </div>
                        </div>
                    @endif
                    @foreach ($user->friends as $friend)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle fa-2x text-secondary me-3"></i>
                                <div>
                                    <h6 class="mb-0">{{ $friend->name }}</h6>
                                    <small class="text-muted">since {{ $friend->pivot->updated_at }}</small>
                                </div>
                            </div>
                            @if (Auth::id() == $user->id)
                                <form action="{{ route('Friend.remove', $friend->id) }}" method="POST">
                                    @csrf

                                    <button class="btn btn-m btn-outline-danger">Unfriend</button>
                                </form>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="col-md-8">
            <h4>{{ $user->name }}'s Posts</h4>

            @forelse($posts as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            @auth
                                @if ($post->user_id === Auth::id())
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Edit</a>
                                            </li>
                                            <li>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <p class="card-text">{{ $post->content }}</p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                @auth
                                    <livewire:LikePosts :post="$post"/>
                                @else
                                    <span class="btn btn-sm btn-outline-secondary disabled me-2">
                                        <i class="fas fa-heart"></i>
                                        {{ $post->likes->count() }}
                                    </span>
                                @endauth

                                <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-comment"></i> View
                                </a>
                                @if ($post->privacy)
                                    <span class="btn btn-sm btn-outline-secondary disabled">
                                        <i class="fas fa-lock"></i> Private
                                    </span>
                                @else
                                    <span class="btn btn-sm btn-outline-success disabled">
                                        <i class="fas fa-globe"></i> Public
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="text-muted">No posts yet</h5>
                        <p>{{ $user->name }} hasn't shared anything yet.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        // document.addEventListener('DOMContentLoaded', function() {

        //     document.querySelectorAll('.like-btn').forEach(button => {
        //         button.addEventListener('click', function() {
        //             // alert("Attempting to Like!");
        //             // console.log(this.dataset.postId);
        //             // console.log("{{ csrf_token() }}");//we can open this and echo php code like normal
        //             fetch(`/posts/${this.dataset.postId}/like`, {
        //                     method: "POST",
        //                     headers: {
        //                         "Content-Type": "application/json", //the format the request body is in.
        //                         "Accept": "application/json", //Please respond in JSON
        //                         "X-CSRF-TOKEN": "{{ csrf_token() }}" //When you’re logged into a Laravel app, it sets a CSRF token in your session, On every POST, PUT, DELETE, PATCH request, Laravel expects the same token sent back in the request headers.
        //                     },
        //                 })
        //                 .then((response) => response.json())
        //                 .then((result) => {
        //                     this.classList.toggle(
        //                         "btn-danger"
        //                     ) //if the class is found remove it else put it
        //                     this.classList.toggle("btn-outline-danger")
        //                     this.children[1].textContent = result.likes;
        //                 })
        //                 .catch((error) => console.log(error))
        //         });
        //     });
        // });
        const switchInput = document.getElementById('privacySwitch');
        const statusText = document.getElementById('privacyStatus');
        statusText.textContent = switchInput.checked ? 'Private' : 'Public';
        switchInput.addEventListener('change', () => {
            statusText.textContent = switchInput.checked ? 'Private' : 'Public';
        });
    </script>
@endsection
