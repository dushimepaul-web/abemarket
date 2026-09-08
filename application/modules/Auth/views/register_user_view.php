<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - <?= htmlspecialchars($settings['site_name'] ?? 'AbeMarket') ?></title>
    <link rel="icon" href="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_favicon', 'favicon.svg')); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/frontend/css/vendors/bootstrap.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/frontend/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/frontend/css/vendors/remixicon.css'); ?>">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        .register-container { max-width: 500px; margin: 60px auto; }
        .register-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 40px; }
        .register-card h2 { font-weight: 700; margin-bottom: 8px; }
        .register-card .subtitle { color: #6c757d; margin-bottom: 28px; }
        .form-floating { margin-bottom: 16px; }
        .form-floating .form-control { border-radius: 10px; padding: 14px 12px; height: 52px; }
        .btn-register { background: #ff6b35; color: #fff; border: none; border-radius: 10px; padding: 12px; font-weight: 600; font-size: 16px; width: 100%; }
        .btn-register:hover { background: #e55a2b; color: #fff; }
        .register-logo { text-align: center; margin-bottom: 24px; }
        .register-logo img { height: 48px; }
        .password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d; background: none; border: none; z-index: 5; }
        .alert { border-radius: 10px; font-size: 14px; }
        .form-check { font-size: 14px; }
        .row-name { display: flex; gap: 12px; }
        .row-name > div { flex: 1; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-logo">
                <a href="<?= base_url(); ?>">
                    <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')); ?>" alt="AbeMarket">
                </a>
            </div>

            <h2>Créer un compte</h2>
            <p class="subtitle">Rejoignez AbeMarket et commencez à commander</p>

            <div id="registerAlert"></div>

            <form id="registerForm" method="POST" action="<?= base_url('auth/register'); ?>">
                <div class="row-name">
                    <div class="form-floating">
                        <input type="text" name="prenom" id="prenom" class="form-control" placeholder="Prénom" required>
                        <label for="prenom"><i class="ri-user-line"></i> Prénom</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Nom" required>
                        <label for="nom"><i class="ri-user-line"></i> Nom</label>
                    </div>
                </div>

                <div class="form-floating">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                    <label for="email"><i class="ri-mail-line"></i> Adresse email</label>
                </div>

                <div class="form-floating">
                    <input type="tel" name="telephone" id="telephone" class="form-control" placeholder="Téléphone" required>
                    <label for="telephone"><i class="ri-phone-line"></i> Numéro de téléphone</label>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" required minlength="6">
                    <label for="password"><i class="ri-lock-line"></i> Mot de passe</label>
                    <button type="button" class="password-toggle" onclick="togglePass('password','icon1')">
                        <i class="ri-eye-off-line" id="icon1"></i>
                    </button>
                </div>

                <div class="form-floating position-relative">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmer" required minlength="6">
                    <label for="confirm_password"><i class="ri-lock-line"></i> Confirmer le mot de passe</label>
                    <button type="button" class="password-toggle" onclick="togglePass('confirm_password','icon2')">
                        <i class="ri-eye-off-line" id="icon2"></i>
                    </button>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" value="1" required>
                        <label class="form-check-label" for="agree_terms">
                            J'accepte les <a href="<?= base_url('pages/terms'); ?>" target="_blank" class="text-primary">conditions d'utilisation</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-register" id="registerBtn">
                    <i class="ri-user-add-line"></i> Créer mon compte
                </button>
            </form>

            <p class="text-center mt-3" style="font-size:14px;">
                Déjà un compte ?
                <a href="<?= base_url('auth/login'); ?>" class="text-primary fw-bold text-decoration-none">Se connecter</a>
            </p>
        </div>
    </div>

    <script>
    function togglePass(fieldId, iconId) {
        const pwd = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (pwd.type === 'password') { pwd.type = 'text'; icon.className = 'ri-eye-line'; }
        else { pwd.type = 'password'; icon.className = 'ri-eye-off-line'; }
    }

    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('registerBtn');
        const alertDiv = document.getElementById('registerAlert');
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;

        if (password !== confirm) {
            alertDiv.innerHTML = '<div class="alert alert-danger">Les mots de passe ne correspondent pas.</div>';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line"></i> Inscription...';

        try {
            const response = await fetch('<?= base_url("auth/register"); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams(new FormData(this))
            });
            const data = await response.json();

            if (data.success) {
                alertDiv.innerHTML = '<div class="alert alert-success">Inscription réussie ! Vérification email en cours...</div>';
                setTimeout(() => { window.location.href = data.redirect || '<?= base_url('auth/otp_verification_page'); ?>'; }, 1200);
            } else {
                alertDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || "Erreur lors de l'inscription.") + '</div>';
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-user-add-line"></i> Créer mon compte';
            }
        } catch (err) {
            alertDiv.innerHTML = '<div class="alert alert-danger">Erreur de connexion. Veuillez réessayer.</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-user-add-line"></i> Créer mon compte';
        }
    });
    </script>
</body>
</html>
