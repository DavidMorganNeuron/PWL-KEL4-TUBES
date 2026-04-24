<!DOCTYPE html>
<html>
<head>
    <title>Pod's layout</title>
</head>
<body>

<div style="display: flex;">
    
    {{-- Sidebar (shared everywhere) --}}
    @include('components.side')

    {{-- Page content goes here --}}
    <div>
        @yield('content')
    </div>

</div>

</body>
</html>