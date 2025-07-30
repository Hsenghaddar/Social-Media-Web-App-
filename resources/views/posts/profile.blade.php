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
                                <livewire:FriendButton :user="$user"/>
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
            <livewire:FriendRequests/>
        @endif
        <livewire:Friends :user="$user"/>
        <livewire:posts :user="$user"/>
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
