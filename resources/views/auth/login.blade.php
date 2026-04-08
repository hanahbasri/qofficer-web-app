<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Q-Officer System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --q-teal: #0b2833;
            --q-yellow: #ffc857;
            --q-teal2: #133547;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            margin: 0;
            background: radial-gradient(circle at top left, rgba(255, 200, 87, .45) 0, transparent 55%),
                radial-gradient(circle at bottom right, rgba(11, 40, 51, .55) 0, transparent 52%),
                #fff9ec;
        }

        /* ── LEFT PANEL ── */
        .login-left {
            flex: 0 0 48%;
            background:
                linear-gradient(160deg, rgba(11, 40, 51, .82) 0%, rgba(11, 40, 51, .65) 40%, rgba(11, 40, 51, .88) 100%),
                url('/images/pemeriksaan-karantina.jpg') center center / cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: flex-start;
            padding: 2.5rem 2.75rem;
            position: relative;
            overflow: hidden;
        }

        /* decorative circles */
        .login-left .deco-circle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .deco-1 {
            width: 340px;
            height: 340px;
            top: -100px;
            right: -100px;
            background: rgba(255, 200, 87, .10);
            animation: floatA 7s ease-in-out infinite;
        }

        .deco-2 {
            width: 200px;
            height: 200px;
            bottom: -60px;
            left: -60px;
            background: rgba(255, 200, 87, .08);
            animation: floatB 9s ease-in-out infinite;
        }

        .deco-3 {
            width: 130px;
            height: 130px;
            bottom: 30%;
            right: 8%;
            background: rgba(255, 255, 255, .04);
            animation: floatA 11s ease-in-out infinite reverse;
        }

        @keyframes floatA {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-18px) scale(1.04);
            }
        }

        @keyframes floatB {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(12px) rotate(6deg);
            }
        }

        /* ── PHOTO CAPTION BADGE ── */
        .photo-badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(255, 200, 87, .2);
            border: 1px solid rgba(255, 200, 87, .4);
            color: #ffc857;
            border-radius: 2rem;
            padding: .3rem .85rem;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .05em;
            margin-bottom: 1rem;
            backdrop-filter: blur(4px);
            animation: fadeSlideUp .6s ease both;
        }

        /* ── LEFT TEXT ── */
        .left-content {
            animation: fadeSlideUp .9s ease .1s both;
        }

        .left-content h1 {
            color: #fff;
            font-size: 1.65rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: .5rem;
        }

        .left-content h1 span {
            color: #ffc857;
        }

        .left-content p {
            color: rgba(249, 250, 251, .7);
            font-size: .85rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .feature-list {
            margin-top: 1.2rem;
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: .7rem;
            color: rgba(249, 250, 251, .78);
            font-size: .83rem;
            animation: fadeSlideUp .7s ease both;
        }

        .feature-item:nth-child(1) {
            animation-delay: .3s;
        }

        .feature-item:nth-child(2) {
            animation-delay: .4s;
        }

        .feature-item:nth-child(3) {
            animation-delay: .5s;
        }

        .feature-item:nth-child(4) {
            animation-delay: .6s;
        }

        .feature-dot {
            width: 28px;
            height: 28px;
            border-radius: .5rem;
            background: rgba(255, 200, 87, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .85rem;
            color: #ffc857;
        }

        /* ── RIGHT PANEL ── */
        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.75rem;
            background: transparent;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 1.1rem;
            padding: 1.8rem 1.7rem 1.6rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .16);
            animation: fadeSlideUp .7s ease .15s both;
        }

        .login-card .card-logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--q-teal), var(--q-teal2));
            border-radius: .75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: .9rem;
            box-shadow: 0 6px 16px rgba(11, 40, 51, .28);
        }

        .login-card h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--q-teal);
            margin-bottom: .22rem;
        }

        .login-card .subtitle {
            color: #6b7280;
            font-size: .83rem;
            margin-bottom: 1.4rem;
        }

        .form-label {
            font-weight: 600;
            font-size: .78rem;
            color: #374151;
            margin-bottom: .4rem;
        }

        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: .7rem;
            padding: .65rem .9rem;
            font-size: .9rem;
            background: #f9fafb;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        .form-control:focus {
            border-color: var(--q-teal);
            background: #ffffff;
            box-shadow: 0 0 0 .17rem rgba(11, 40, 51, .14);
            outline: none;
        }

        .input-group-text {
            background: #f3f4f6;
            border: 1.5px solid #e5e7eb;
            color: #6b7280;
            border-radius: .7rem 0 0 .7rem;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 .7rem .7rem 0;
        }

        .input-group .form-control:focus {
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--q-teal);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--q-teal), #1a4a60);
            border: none;
            color: #fff;
            font-weight: 700;
            padding: .72rem 1.5rem;
            border-radius: .7rem;
            font-size: .92rem;
            letter-spacing: .01em;
            width: 100%;
            transition: transform .15s, box-shadow .15s;
            box-shadow: 0 8px 22px rgba(11, 40, 51, .32);
        }

        .btn-login:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(11, 40, 51, .4);
        }

        .btn-login:active {
            transform: none;
        }

        .error-box {
            display: flex;
            align-items: center;
            gap: .65rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: .7rem;
            color: #b91c1c;
            font-size: .83rem;
            padding: .65rem .85rem;
            margin-bottom: 1rem;
        }

        .error-box i {
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* hide browser native password reveal */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
        }

        /* show/hide password */
        .pw-toggle {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            padding: 0;
            cursor: pointer;
            line-height: 1;
            font-size: 1rem;
            z-index: 5;
        }

        .pw-toggle:hover {
            color: var(--q-teal);
        }

        .pw-wrap {
            position: relative;
        }

        .pw-wrap .form-control {
            padding-right: 2.4rem;
        }

        /* recaptcha wrapper */
        .recaptcha-wrap {
            transform: scale(0.85);
            transform-origin: left top;
            height: 65px;
            overflow: hidden;
        }

        .footer-note {
            text-align: center;
            color: #9ca3af;
            font-size: .72rem;
            margin-top: 1.4rem;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .login-left {
                display: none;
            }

            body {
                background: linear-gradient(180deg, #0b2833 0%, #0b2833 35%, #fff9ec 35%, #fff9ec 100%);
                align-items: flex-end;
            }

            .login-right {
                padding: 2.1rem 1.4rem 1.6rem;
            }

            .login-card {
                max-width: 480px;
                margin: 0 auto;
                box-shadow: 0 -6px 24px rgba(15, 23, 42, .28);
                border-radius: 1.4rem 1.4rem 0 0;
            }
        }
    </style>
</head>

<body>

    {{-- LEFT PANEL --}}
    <div class="login-left d-none d-lg-flex flex-column">
        <div class="deco-circle deco-1"></div>
        <div class="deco-circle deco-2"></div>
        <div class="deco-circle deco-3"></div>

        <div class="left-content">
            <div class="photo-badge">
                <i class="bi bi-building-check"></i>
                BARANTIN · PDSI
            </div>
            <h1>Q-Officer<br><span>System</span></h1>
            <p>
                Sistem Informasi Pemeriksaan Kesehatan<br>
                Karantina Hewan, Ikan &amp; Tumbuhan<br>
                Badan Karantina Indonesia
            </p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot"><i class="bi bi-file-earmark-check"></i></div>
                    Manajemen Surat Tugas &amp; Penugasan
                </div>
                <div class="feature-item">
                    <div class="feature-dot"><i class="bi bi-clipboard2-data"></i></div>
                    Review Hasil Pemeriksaan K-3.7b
                </div>
                <div class="feature-item">
                    <div class="feature-dot"><i class="bi bi-bar-chart-line"></i></div>
                    Dashboard &amp; Monitoring Pimpinan
                </div>
                <div class="feature-item">
                    <div class="feature-dot"><i class="bi bi-people"></i></div>
                    Manajemen Pengguna &amp; UPT
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="login-right">
        <div class="login-card">
            <div class="card-logo">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <h2>Selamat Datang</h2>
            <p class="subtitle">Masuk dengan username dan kata sandi Anda</p>

            @if ($errors->any())
                <div class="error-box">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="nip">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nip" id="nip" class="form-control"
                            placeholder="Masukkan username" value="{{ old('nip') }}" required autofocus
                            autocomplete="username">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <div class="pw-wrap flex-grow-1">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Masukkan kata sandi" required autocomplete="current-password">
                            <button type="button" class="pw-toggle" id="pwToggle" tabindex="-1">
                                <i class="bi bi-eye" id="pwToggleIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="recaptcha-wrap">
                        <div class="g-recaptcha"
                            data-sitekey="{{ env('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}">
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember" style="color:#6b7280">Ingat saya</label>
                    </div>
                </div>
                <button type="submit" class="btn-login" id="submitBtn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <div class="footer-note">
                Q-Officer v1.0 &copy; 2026 Barantin &mdash; PDSI
            </div>
        </div>
    </div>

    @if (session('access_denied'))
        <div class="modal fade" id="modalAksesDitolak" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3 overflow-hidden shadow-lg"
                    style="animation: slideUpModal .5s ease-out;">
                    <div class="modal-body text-center p-0">
                        <!-- Icon Section -->
                        <div
                            style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); padding: 2.5rem 1.5rem 2rem;">
                            <div
                                style="width:80px;height:80px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;box-shadow:0 12px 32px rgba(220,38,38,.15);position:relative">
                                <i class="bi bi-shield-exclamation"
                                    style="font-size:2rem;color:#dc2626;animation:iconShake .6s ease-in-out .3s"></i>
                                <div
                                    style="position:absolute;width:100%;height:100%;border-radius:50%;border:2px solid #fca5a5;animation:pulseRing 2s infinite">
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div style="padding: 2rem 1.75rem 2.5rem;">
                            <h5 class="fw-800 mb-2" style="color:#111827;font-weight:800;font-size:1.1rem">Akses
                                Ditolak</h5>
                            <p
                                style="font-size:.85rem;color:#6b7280;margin-bottom:2rem;line-height:1.6;animation:fadeInText .6s ease-out .2s both">
                                {{ session('access_denied') }}
                            </p>
                            <button type="button" class="btn w-100 fw-600" data-bs-dismiss="modal"
                                style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);color:#fff;border-radius:.65rem;font-weight:600;padding:.75rem 1.5rem;border:none;font-size:.9rem;transition:all .3s;box-shadow:0 8px 16px rgba(220,38,38,.25);animation:slideUpButton .5s ease-out .4s both"
                                onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 24px rgba(220,38,38,.35)'"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 16px rgba(220,38,38,.25)'">
                                <i class="bi bi-check-circle me-2"></i>Mengerti
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Animations -->
        <style>
            @keyframes slideUpModal {
                from {
                    opacity: 0;
                    transform: translateY(50px) scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            @keyframes iconShake {

                0%,
                100% {
                    transform: rotate(0deg);
                }

                15% {
                    transform: rotate(-8deg);
                }

                30% {
                    transform: rotate(8deg);
                }

                45% {
                    transform: rotate(-6deg);
                }

                60% {
                    transform: rotate(6deg);
                }

                75% {
                    transform: rotate(0deg);
                }
            }

            @keyframes pulseRing {
                0% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4);
                }

                50% {
                    box-shadow: 0 0 0 12px rgba(220, 38, 38, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
                }
            }

            @keyframes fadeInText {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideUpButton {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // ── Show/hide password ──────────────────────────────
        const pwToggle = document.getElementById('pwToggle');
        const pwInput = document.getElementById('password');
        const pwIcon = document.getElementById('pwToggleIcon');

        pwToggle.addEventListener('click', () => {
            const isHidden = pwInput.type === 'password';
            pwInput.type = isHidden ? 'text' : 'password';
            pwIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });

        @if (session('access_denied'))
            new bootstrap.Modal(document.getElementById('modalAksesDitolak')).show();
        @endif
    </script>
</body>

</html>
