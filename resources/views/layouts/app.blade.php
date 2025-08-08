<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Social Media App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-comments"></i> LinkUp Social
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('posts.create') }}">New Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('posts.friends') }}">Friends Posts</a>
                        </li>
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <livewire:notifications />
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile', Auth::user()) }}">Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    {{-- notifications toast --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <a class="notification-link text-decoration-none text-white" >
                    <div class="toast-body">
                        New element added successfully!
                    </div>
                </a>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>


    <main class="container my-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message = "Action completed!",link) {
            const toastEl = document.getElementById('liveToast');
            toastEl.querySelector(".toast-body").textContent = message;
            document.querySelector(".notification-link").href=link;
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000
            }); // 3s delay
            toast.show();
        }

        @auth
        window.Echo = new Echo({ //initialize the WebSocket connection to Pusher
            broadcaster: 'pusher',
            key: "{{ env('PUSHER_APP_KEY') }}", //Identify your app using the key and cluster
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            auth: { //Since private channels require authentication, Echo needs to send a CSRF token to Laravel for authorization.
                headers: { //This header is included in the POST request Echo makes
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" //Laravel uses this to verify the request is from a logged-in and valid user.
                }
            }
        });
        //listen on a private channel named notifications.{user_id}.
        window.Echo.private("notifications.{{ Auth::id() }}").listen("UserNotified", (
            event) => { //listens for the event called "UserNotified" on that channel
            //console.log(event.message);//the callback function runs everytime the event is recieved
            const list = document.querySelector(".dropdown-menu")
            Livewire.dispatch('newNotification', {detail: list.classList.contains("show") ? true : false}) //dispatch an event that livewire components can listen to
            showToast(event.message,event.link);
        });
        @endauth
    </script>

    @yield('scripts')
</body>

</html>
