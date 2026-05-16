{{-- TOPBAR --}}
<div
    style="
        height: 64px;
        background: #FFFDF9;
        border-bottom: 1px solid #EDE0CC;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 2rem;
        box-shadow: 0 1px 6px rgba(28,15,10,0.06);
    "
>

    {{-- judul halaman aktif --}}
    <div style="display: flex; align-items: center; gap: 0.75rem;">

        {{-- breadcrumb label: di-yield oleh masing-masing halaman --}}
        <div>
            <p style="font-size: 0.6875rem; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: var(--pods-muted); margin-bottom: 0.125rem; line-height: 1;">
                Manager Cabang
            </p>
            <h1
                class="font-serif"
                style="font-size: 1.125rem; font-weight: 700; color: var(--pods-espresso); line-height: 1.2;"
            >
                @yield('page-title', 'Dashboard')
            </h1>
        </div>
    </div>

    {{-- indikator waktu real-time + status cabang --}}
    <div style="display: flex; align-items: center; gap: 1.25rem;">

        {{-- indikator status cabang: always-open untuk Dr. Mansyur --}}
        <div style="display: flex; align-items: center; gap: 0.5rem; background: #D1FAE5; border: 1px solid #A7F3D0; border-radius: 9999px; padding: 0.3125rem 0.875rem;">
            <span
                style="width: 7px; height: 7px; border-radius: 9999px; background: #059669; flex-shrink: 0; animation: topbar-pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;"
                aria-hidden="true"
            ></span>
            <span style="font-size: 0.75rem; font-weight: 600; color: #065F46; white-space: nowrap;">
                Cabang Buka
            </span>
        </div>

        {{-- jam real-time --}}
        <div style="text-align: right;">
            <p id="topbar-time" style="font-size: 1rem; font-weight: 600; color: var(--pods-espresso); letter-spacing: 0.04em; line-height: 1.2; font-variant-numeric: tabular-nums;">
                --:--:--
            </p>
            <p id="topbar-date" style="font-size: 0.6875rem; font-weight: 300; color: var(--pods-muted); line-height: 1;">
                Loading...
            </p>
        </div>

    </div>
</div>

@once
@push('head-scripts')
<style>
    /* animasi denyut: indikator status cabang aktif */
    @keyframes topbar-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }
</style>
@endpush
@endonce

{{-- SCRIPT TOPBAR: jam real-time diupdate tiap detik --}}
@push('scripts')
<script>
(function () {
    const timeEl = document.getElementById('topbar-time');
    const dateEl = document.getElementById('topbar-date');
    if (!timeEl || !dateEl) return;

    const dayNames = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    function updateClock() {
        const now = new Date();

        /* format jam HH:MM:SS */
        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');
        timeEl.textContent = hh + ':' + mm + ':' + ss;

        /* format tanggal: Senin, 15 Mei 2026 */
        const day   = dayNames[now.getDay()];
        const date  = now.getDate();
        const month = monthNames[now.getMonth()];
        const year  = now.getFullYear();
        dateEl.textContent = day + ', ' + date + ' ' + month + ' ' + year;
    }

    updateClock();
    setInterval(updateClock, 1000);
}());
</script>
@endpush