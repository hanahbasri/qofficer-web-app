<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Q-Officer Web Dashboard</title>

    <link href="https://fonts.bunny.net/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Color palette: teal, yellow, maroon, mauve */
            --q-primary: #0b2833;   /* deep teal */
            --q-accent: #ffc857;    /* warm yellow (base) */
            --q-maroon: #5b252b;    /* dark maroon */
            --q-neutral: #8b6b6b;   /* muted mauve */
            --q-bg: #fff7e0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--q-accent) 0%, #ffe9aa 40%, #fffdf7 100%);
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .shell {
            width: 100%;
            max-width: 1120px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow:
                0 18px 36px rgba(15, 23, 42, 0.20);
            padding: 28px 26px;
            display: grid;
            grid-template-columns: minmax(0, 3fr) minmax(0, 2.4fr);
            gap: 32px;
        }

        @media (max-width: 900px) {
            .shell {
                grid-template-columns: minmax(0, 1fr);
                padding: 22px 20px;
            }
        }

        .badge-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
        }

        .badge {
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            background: linear-gradient(135deg, var(--q-primary), #132f3b);
            color: #f9fafb;
        }

        .role-pill {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(11, 40, 51, 0.06);
            color: #374151;
        }

        h1 {
            font-size: clamp(26px, 3.1vw, 34px);
            line-height: 1.15;
            margin: 0 0 10px;
            color: var(--q-primary);
        }

        .subtitle {
            font-size: 14px;
            line-height: 1.6;
            color: #4b5563;
            max-width: 34rem;
            margin-bottom: 18px;
        }

        .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 14px;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 22px;
        }

        .meta-label {
            font-weight: 500;
            color: #4b5563;
        }

        .cta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }

        .btn-primary {
            border: none;
            padding: 10px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--q-primary), #122837);
            color: #f9fafb;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary {
            border-radius: 999px;
            border: 1px solid rgba(11, 40, 51, 0.18);
            background: #fff7e6;
            color: #92400e;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .helper-text {
            font-size: 12px;
            color: #6b7280;
        }

        .hero-footer {
            margin-top: 18px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            font-size: 11px;
            color: #6b7280;
        }

        .hero-footer strong {
            color: #374151;
        }

        .card-right {
            background: #fff7f3;
            border-radius: 16px;
            padding: 20px 18px;
            border: 1px solid rgba(91, 37, 43, 0.18);
            box-shadow: 0 10px 22px rgba(148, 27, 45, 0.08);
            color: #4b5563;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-tag {
            font-size: 10px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 10px;
        }

        .card-heading {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--q-maroon);
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .kpi {
            padding: 10px 10px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid rgba(148, 163, 184, 0.35);
        }

        .kpi-label {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--q-primary);
        }

        .role-list {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed rgba(148, 163, 184, 0.6);
            font-size: 11px;
            color: #d1d5db;
        }

        .role-list span {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            margin-right: 6px;
            margin-top: 4px;
        }

        .role-list .tag-admin {
            background: rgba(255, 199, 90, 0.12);
            color: #facc15;
        }

        .role-list .tag-koor {
            background: rgba(96, 165, 250, 0.18);
            color: #bfdbfe;
        }

        .role-list .tag-petugas {
            background: rgba(248, 113, 113, 0.16);
            color: #fecaca;
        }

        footer {
            margin-top: 22px;
            font-size: 11px;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
<main class="shell">
    <section>
        <div class="badge-row">
            <div class="badge">Q-Officer · Web Dashboard</div>
            <div class="role-pill">Super Admin · Koordinator UPT · Pimpinan</div>
        </div>

        <h1>Monitoring penugasan & pemeriksaan karantina dalam satu dashboard terintegrasi.</h1>

        <p class="subtitle">
            Q-Officer Web menyambungkan data dari mobile Petugas dan Koordinator UPT
            ke dalam tampilan yang rapi untuk supervisi, rekomendasi tindakan karantina,
            serta pelaporan lintas UPT.
        </p>

        <div class="meta-row">
            <div><span class="meta-label">Instansi:</span> UPT BBKHIT DKI Jakarta &amp; PDSI Barantin</div>
            <div><span class="meta-label">Versi dokumen:</span> 2.0.0 · Maret 2026</div>
            <div><span class="meta-label">Status:</span> Data dummy / simulasi</div>
        </div>

        <div class="cta-row">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/home') }}" class="btn-primary">
                        Buka dashboard
                        <span>→</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">
                        Masuk sebagai pengguna internal
                        <span>→</span>
                    </a>
                @endauth
            @else
                <a href="#" class="btn-primary">
                    Backend API siap diakses
                </a>
            @endif

            <a href="#" class="btn-secondary">
                Lihat daftar UPT tersinkron
            </a>
        </div>

        <p class="helper-text">
            Reset password dikelola berjenjang: Petugas melalui Koordinator UPT,
            Koordinator melalui Super Admin. Hak akses mengikuti role &amp; UPT masing-masing.
        </p>

        <div class="hero-footer">
            <div><strong>Integrasi platform:</strong> Mobile Flutter (Petugas &amp; Koordinator) · Web Laravel Blade (Semua role)</div>
            <div><strong>Autentikasi:</strong> REST API · Bearer Token · RBAC per UPT</div>
        </div>

        <footer>
            <span>Q-Officer — Sistem Informasi Pemeriksaan Kesehatan Badan Karantina Indonesia</span>
            <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} · PHP v{{ PHP_VERSION }}</span>
        </footer>
    </section>

    <aside class="card-right">
        <div>
            <div class="card-tag">Live snapshot (simulasi)</div>
            <div class="card-heading">Rekap cepat lintas UPT karantina</div>

            <div class="kpi-grid">
                <div class="kpi">
                    <div class="kpi-label">UPT aktif</div>
                    <div class="kpi-value">{{ 42 }}</div>
                </div>
                <div class="kpi">
                    <div class="kpi-label">ST K-2.2 berjalan</div>
                    <div class="kpi-value">{{ 128 }}</div>
                </div>
                <div class="kpi">
                    <div class="kpi-label">Form K-3.7b tersinkron</div>
                    <div class="kpi-value">{{ 864 }}</div>
                </div>
                <div class="kpi">
                    <div class="kpi-label">Notifikasi FCM terkirim</div>
                    <div class="kpi-value">{{ 3120 }}</div>
                </div>
            </div>

            <div class="role-list">
                Role &amp; alur reset password:
                <div>
                    <span class="tag-petugas">Petugas</span>
                    Petugas yang lupa password menghubungi Koordinator UPT masing-masing.
                </div>
                <div>
                    <span class="tag-koor">Koordinator UPT</span>
                    Koordinator dapat mereset password Petugas dalam UPT yang sama saja.
                </div>
                <div>
                    <span class="tag-admin">Super Admin</span>
                    Super Admin dapat mereset password seluruh role, termasuk Koordinator.
                </div>
            </div>
        </div>
    </aside>
</main>
</body>
</html>
