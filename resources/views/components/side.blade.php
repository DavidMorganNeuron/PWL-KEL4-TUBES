<sidebar>
    <ul>
        <li class="{{ request()->routeIs('main') ? 'active' : '' }}">
    <a href="{{ route('main') }}">Main</a>
</li>
        <li class="{{ request()->routeIs('orders*') ? 'active' : '' }}">
            <a href="{{ route('orders') }}">Order</a>
        </li>

        <li class="{{ request()->routeIs('history*') ? 'active' : '' }}">
            <a href="{{ route('history') }}">History</a>
        </li>

        <li class="{{ request()->routeIs('account*') ? 'active' : '' }}">
            <a href="{{ route('account') }}">Account</a>
        </li>
    </ul>
</sidebar>