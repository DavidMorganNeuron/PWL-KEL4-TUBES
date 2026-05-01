<sidebar>
    <ul>
        <li class="{{ request()->routeIs('main') ? 'active' : '' }}">
            <a href="{{ route('main') }}">Main</a>
        </li>
        <li class="{{ request()->routeIs('orders*') ? 'active' : '' }}">
            <a href="{{ route('orders.branch') }}">Order</a>
        </li>
        <li class="{{ request()->routeIs('history*') ? 'active' : '' }}">
            <a href="{{ route('history') }}">History</a>
        </li>
        <li class="{{ request()->routeIs('account*') ? 'active' : '' }}">
            <a href="{{ route('account') }}">Account</a>
        </li>
        
        {{-- Tombol Logout --}}
        <li style="margin-top: 20px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background: none; border: none; color: grey; text-decoration: underline; cursor: pointer;">
                    Logout
                </button>
            </form>
        </li>
    </ul>
</sidebar>