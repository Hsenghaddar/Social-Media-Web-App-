<li class="nav-item dropdown">
    <div class="nav-link position-relative" role="button" data-bs-toggle="dropdown">
        <span class="noti-count">{{ $notifications->count() }} </span>
        <i class="fas fa-bell"></i>
    </div>
    <ul style="right:1px" class="dropdown-menu dropdown-menu-end {{ $showDropDown ? "show":"" }}">
        <li>
            <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                Notifications
                @if ($notifications->count())
                    <button wire:click="readAll" class="btn btn-sm btn-link text-decoration-none text-primary p-0 m-0"
                        style="font-size: 0.85rem;">Mark all as read</button>
                @endif
            </h6>
        </li>

        @forelse($notifications as $notification)
            <li wire:key={{ $notification->id }}>
                <a class="dropdown-item d-block fw-semibold text-dark text-decoration-none"
                    href="{{ $notification->link }}" style="transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='rgba(13,110,253,0.8 )'"
                    onmouseout="this.style.backgroundColor=''">
                    {{ $notification->message }}
                </a>
            </li>
        @empty
            <li class="dropdown-item">No notifications yet</li>
        @endforelse

        <li class="dropdown-item border-top">
            <a href="{{ route('notifications') }}">View all notifications</a>
        </li>
    </ul>
</li>
