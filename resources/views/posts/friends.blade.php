@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row">
        <div class="col-md-8">
            @auth
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('posts.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="3"
                                    placeholder="What's on your mind?" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex gap-2">
                                <div class="form-check form-switch d-flex justify-content-center align-items-center gap-2">
                                    <input name="privacy" class="form-check-input" type="checkbox" role="switch"
                                        id="privacySwitch" {{ Auth::user()->private ? 'checked' : '' }}>
                                    <span id="privacyStatus">Public</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endauth

            <h4>Recent Posts</h4>
            @foreach ($friends as $friend)
                @foreach ($friend->posts as $post)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="card-subtitle mb-1">
                                        <a href="{{ route('profile', $post->user) }}" class="text-decoration-none">
                                            {{ $post->user->name }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
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
                                        <button data-post-id="{{ $post->id }}" type="button"
                                            class="like-btn btn btn-sm {{ $post->isLikedBy(Auth::user()) ? 'btn-danger' : 'btn-outline-danger' }}">
                                            <i class="fas fa-heart"></i>
                                            <span>{{ $post->likes->count() }}</span>
                                        </button>
                                    @else
                                        <span class="btn btn-sm btn-outline-secondary disabled">
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
                @endforeach
            @endforeach
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Welcome to Social Media</h5>
                </div>
                <div class="card-body">
                    @guest
                        <p>Join our community to share your thoughts and connect with others!</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                        </div>
                    @else
                        <p>Welcome back, {{ Auth::user()->name }}!</p>
                        <div class="d-grid">
                            <a href="{{ route('profile', Auth::user()) }}" class="btn btn-outline-primary">View Profile</a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // alert("Attempting to Like!");
                    // console.log(this.dataset.postId);
                    // console.log("{{ csrf_token() }}");//we can open this and echo php code like normal
                    fetch(`/posts/${this.dataset.postId}/like`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json", //the format the request body is in.
                                "Accept": "application/json", //Please respond in JSON
                                "X-CSRF-TOKEN": "{{ csrf_token() }}" //When you’re logged into a Laravel app, it sets a CSRF token in your session, On every POST, PUT, DELETE, PATCH request, Laravel expects the same token sent back in the request headers.
                            },
                        })
                        .then((response) => response.json())
                        .then((result) => {
                            this.classList.toggle(
                                "btn-danger") //if the class is found remove it else put it
                            this.classList.toggle("btn-outline-danger")
                            this.children[1].textContent = result.likes;
                        })
                        .catch((error) => console.log(error))
                });
            });
        });

        const switchInput = document.getElementById('privacySwitch');
        const statusText = document.getElementById('privacyStatus');
        statusText.textContent = switchInput.checked ? 'Private' : 'Public';
        switchInput.addEventListener('change', () => {
            statusText.textContent = switchInput.checked ? 'Private' : 'Public';
        });
    </script>
@endsection
