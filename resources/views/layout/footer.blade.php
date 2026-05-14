{{-- FOOTER --}}
<footer style="background: #1C0F0A; color: rgba(245,233,211,0.65); margin-top: auto;">
    <div style="width: 1280px; margin: 0 auto; padding: 3.5rem 2.5rem 0;">

        {{-- GRID 3 KOLOM: statis, tidak collapse --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 3rem;">

            {{-- KOLOM 1: Brand --}}
            <div style="max-width: 280px;">
                <a
                    href="{{ route('main') }}"
                    class="font-serif"
                    style="font-size: 1.5rem; font-weight: 900; color: #F5E9D3; text-decoration: none; display: inline-block; margin-bottom: 0.75rem;"
                >
                    Pod's
                </a>
                <p style="font-size: 0.875rem; line-height: 1.75; color: rgba(245,233,211,0.45);">
                    Where every cup tells a story.<br>Crafted with passion, served with warmth.
                </p>
                {{-- garis dekoratif karamel --}}
                <div style="width: 32px; height: 2px; background: #C8813B; margin-top: 1.25rem;"></div>
            </div>

            {{-- KOLOM 2: Navigasi cepat --}}
            <nav aria-label="Navigasi footer" style="min-width: 140px;">
                <p style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: #C8813B; margin-bottom: 1rem;">
                    Fiture
                </p>
                <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.625rem;" role="list">
                    <li>
                        <a href="{{ route('main') }}" style="font-size: 0.875rem; color: rgba(245,233,211,0.6); text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#C8813B'" onmouseout="this.style.color='rgba(245,233,211,0.6)'">
                            Home
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('orders.branch') }}" style="font-size: 0.875rem; color: rgba(245,233,211,0.6); text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#C8813B'" onmouseout="this.style.color='rgba(245,233,211,0.6)'">
                            Order Now
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('history') }}" style="font-size: 0.875rem; color: rgba(245,233,211,0.6); text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#C8813B'" onmouseout="this.style.color='rgba(245,233,211,0.6)'">
                            History
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('account') }}" style="font-size: 0.875rem; color: rgba(245,233,211,0.6); text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#C8813B'" onmouseout="this.style.color='rgba(245,233,211,0.6)'">
                            Account
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="{{ route('login') }}" style="font-size: 0.875rem; color: rgba(245,233,211,0.6); text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#C8813B'" onmouseout="this.style.color='rgba(245,233,211,0.6)'">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" style="font-size: 0.875rem; color: rgba(245,233,211,0.6); text-decoration: none; transition: color 0.2s;"
                           onmouseover="this.style.color='#C8813B'" onmouseout="this.style.color='rgba(245,233,211,0.6)'">
                            Daftar
                        </a>
                    </li>
                    @endauth
                </ul>
            </nav>

            {{-- KOLOM 3: Jam operasional --}}
            <div style="min-width: 220px;">
                <p style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.2em; text-transform: uppercase; color: #C8813B; margin-bottom: 1rem;">
                    Jam Operasional
                </p>
                <dl style="display: flex; flex-direction: column; gap: 0.375rem;">
                    <div style="display: flex; justify-content: space-between; gap: 1rem;">
                        <dt style="font-size: 0.8125rem; color: rgba(245,233,211,0.5);">Senin – Jumat</dt>
                        <dd style="font-size: 0.8125rem; color: rgba(245,233,211,0.75); font-weight: 500;">07.00 – 22.00</dd>
                    </div>
                    <div style="display: flex; justify-content: space-between; gap: 1rem;">
                        <dt style="font-size: 0.8125rem; color: rgba(245,233,211,0.5);">Sabtu – Minggu</dt>
                        <dd style="font-size: 0.8125rem; color: rgba(245,233,211,0.75); font-weight: 500;">08.00 – 23.00</dd>
                    </div>
                    {{-- catatan khusus cabang --}}
                    <p style="font-size: 0.75rem; color: rgba(245,233,211,0.3); margin-top: 0.5rem; font-style: italic;">
                        *Cab. Dr. Mansyur: Buka 24 Jam
                    </p>
                </dl>
            </div>

        </div>

        {{-- BOTTOM BAR: copyright --}}
        <div style="margin-top: 2.5rem; padding: 1.25rem 0; border-top: 1px solid rgba(245,233,211,0.08); text-align: center;">
            <p style="font-size: 0.75rem; color: rgba(245,233,211,0.25);">
                &copy; {{ date('Y') }} Pod's F&B System. All rights reserved.
            </p>
        </div>

    </div>
</footer>