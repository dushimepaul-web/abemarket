<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= htmlspecialchars($settings['site_name'] ?? 'AbeMarket') ?></title>
    <link rel="icon" href="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_favicon', 'favicon.svg')); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/frontend/css/themefour.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/frontend/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        .login-container { max-width: 440px; margin: 80px auto; }
        .login-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; }
        .login-card h2 { font-weight: 700; margin-bottom: 8px; }
        .login-card .subtitle { color: #6c757d; margin-bottom: 28px; }
        .form-floating { margin-bottom: 16px; }
        .form-floating .form-control { border-radius: 10px; padding: 14px 12px; height: 52px; }
        .btn-login { background: #ff6b35; color: #fff; border: none; border-radius: 10px; padding: 12px; font-weight: 600; font-size: 16px; width: 100%; }
        .btn-login:hover { background: #e55a2b; color: #fff; }
        .login-logo { text-align: center; margin-bottom: 24px; }
        .login-logo img { height: 48px; }
        .divider { text-align: center; margin: 20px 0; position: relative; }
        .divider::before { content: ''; position: absolute; left: 0; top: 50%; width: 100%; height: 1px; background: #dee2e6; }
        .divider span { background: #fff; padding: 0 12px; position: relative; color: #6c757d; font-size: 13px; }
        .social-btns { display: flex; gap: 10px; }
        .social-btns a { flex: 1; text-align: center; padding: 10px; border-radius: 10px; border: 1px solid #dee2e6; color: #333; text-decoration: none; font-weight: 500; transition: 0.2s; }
        .social-btns a:hover { background: #f1f1f1; }
        .alert { border-radius: 10px; font-size: 14px; }
        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d; background: none; border: none; z-index: 5; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <a href="<?= base_url(); ?>">
                    <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')); ?>" alt="<?= htmlspecialchars($settings['site_name'] ?? 'AbeMarket') ?>">
                </a>
            </div>

            <h2>Connexion</h2>
            <p class="subtitle">Connectez-vous pour accéder à votre compte</p>

            <div id="loginAlert"></div>

            <form id="loginForm" method="POST" action="<?= base_url('auth/login'); ?>">
                <div class="form-floating position-relative">
                    <input type="email" name="email" id="login_email" class="form-control" placeholder="Email" required>
                    <label for="login_email"><i class="ri-mail-line"></i> Adresse email</label>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" name="password" id="login_password" class="form-control" placeholder="Mot de passe" required>
                    <label for="login_password"><i class="ri-lock-line"></i> Mot de passe</label>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="ri-eye-off-line" id="toggleIcon"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="<?= base_url('auth/forgot_password'); ?>" class="text-primary text-decoration-none" style="font-size:14px;">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn btn-login" id="loginBtn">
                    <i class="ri-login-box-line"></i> Se connecter
                </button>
            </form>

            <div class="divider"><span>ou</span></div>

            <p class="text-center mt-3" style="font-size:14px;">
                Pas encore de compte ?
                <a href="<?= base_url('auth/register'); ?>" class="text-primary fw-bold text-decoration-none">Créer un compte</a>
            </p>
        </div>
    </div>

    <script>
    function togglePassword() {
        const pwd = document.getElementById('login_password');
        const icon = document.getElementById('toggleIcon');
        if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'ri-eye-line'; }
        else { pwd.type = 'password'; icon.className = 'ri-eye-off-line'; }
    }

    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('loginBtn');
        const alertDiv = document.getElementById('loginAlert');
        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line"></i> Connexion...';

        try {
            const response = await fetch('<?= base_url("auth/login"); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams(new FormData(this))
            });
            const data = await response.json();

            if (data.success) {
                alertDiv.innerHTML = '<div class="alert alert-success">Connexion réussie ! Redirection...</div>';
                setTimeout(() => { window.location.href = data.redirect || '<?= base_url(); ?>'; }, 800);
            } else {
                alertDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Email ou mot de passe incorrect.') + '</div>';
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-login-box-line"></i> Se connecter';
            }
        } catch (err) {
            alertDiv.innerHTML = '<div class="alert alert-danger">Erreur de connexion. Veuillez réessayer.</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-login-box-line"></i> Se connecter';
        }
    });
    </script>
</body>
</html>
