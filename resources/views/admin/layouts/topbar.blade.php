<div
    style="height:64px; background:#FFFDF9; border-bottom:1px solid #EDE0CC; display:flex; align-items:center; justify-content:space-between; padding:0 2rem; box-shadow:0 1px 6px rgba(28,15,10,0.06);"
>

    <div>
        <p style="font-size:0.6875rem; font-weight:600; letter-spacing:0.2em; text-transform:uppercase; color:var(--pods-muted); margin-bottom:0.125rem; line-height:1;">
            Admin Pusat
        </p>
        <h1 class="font-serif" style="font-size:1.125rem; font-weight:700; color:var(--pods-espresso); line-height:1.2;">
            @yield('page-title', 'Dashboard')
        </h1>
    </div>

    <div style="text-align:right;">
        <p id="adm-topbar-time" style="font-size:1rem; font-weight:600; color:var(--pods-espresso); letter-spacing:0.04em; line-height:1.2; font-variant-numeric:tabular-nums;">
            --:--:--
        </p>
        <p id="adm-topbar-date" style="font-size:0.6875rem; font-weight:300; color:var(--pods-muted); line-height:1;">
            Loading...
        </p>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var timeEl = document.getElementById('adm-topbar-time');
    var dateEl = document.getElementById('adm-topbar-date');
    if (!timeEl || !dateEl) return;
    var days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    var months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    function tick() {
        var n  = new Date();
        timeEl.textContent = [n.getHours(),n.getMinutes(),n.getSeconds()].map(function(v){return String(v).padStart(2,'0');}).join(':');
        dateEl.textContent = days[n.getDay()] + ', ' + n.getDate() + ' ' + months[n.getMonth()] + ' ' + n.getFullYear();
    }
    tick();
    setInterval(tick, 1000);
}());
</script>
@endpush