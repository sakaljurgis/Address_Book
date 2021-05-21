<ul class="nav nav-pills flex-column">
    @guest
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() === 'login' ? 'active' : '' }}" href="/login">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() === 'register' ? 'active' : '' }}" href="/register">Register</a>
        </li>
    @endguest
    @auth
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() === 'contacts' ? 'active' : '' }}" href="/contacts">Contacts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() === 'shares' ? 'active' : '' }}" href="/shares">Shared contacts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/logout">Logout</a>
        </li>
    @endauth
</ul>

