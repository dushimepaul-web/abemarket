<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du code - ABEMARKET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .verify-container {
            max-width: 500px;
            width: 100%;
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .verify-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .verify-header {
            background: linear-gradient(135deg, #ff6600, #ff8533);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .verify-header .icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .verify-header .icon i {
            font-size: 40px;
        }
        
        .verify-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .verify-header p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .verify-body {
            padding: 40px 30px;
        }
        
        .email-display {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .email-display i {
            color: #ff6600;
            margin-right: 10px;
        }
        
        .code-input-group {
            margin-bottom: 25px;
        }
        
        .code-input-group input {
            width: 100%;
            height: 60px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0 15px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .code-input-group input:focus {
            border-color: #ff6600;
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
            outline: none;
        }
        
        .resend-link {
            text-align: center;
            margin-top: 15px;
        }
        
        .resend-link a {
            color: #ff6600;
            text-decoration: none;
            font-size: 14px;
        }
        
        .resend-link a:hover {
            text-decoration: underline;
        }
        
        .btn-verify {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #ff6600, #ff8533);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 102, 0, 0.3);
        }
        
        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .password-section {
            display: none;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }
        
        .input-group-custom input {
            width: 100%;
            height: 50px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 0 45px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .input-group-custom input:focus {
            border-color: #ff6600;
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
            outline: none;
        }
        
        .input-group-custom .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 18px;
        }
        
        .strength-meter {
            margin-top: 8px;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
        }
        
        .strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .strength-text {
            font-size: 12px;
            margin-top: 5px;
            color: #666;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-to-login a {
            color: #ff6600;
            text-decoration: none;
            font-size: 14px;
        }
        
        .timer {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #999;
        }
        
        @media (max-width: 576px) {
            .verify-header {
                padding: 30px 20px;
            }
            .verify-body {
                padding: 30px 20px;
            }
            .code-input-group input {
                font-size: 18px;
                letter-spacing: 5px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-header">
                <div class="icon">
                    <i class="ri-mail-send-line"></i>
                </div>
                <h1>Vérification du code</h1>
                <p>Un code à 6 chiffres a été envoyé à votre adresse email</p>
            </div>
            
            <div class="verify-body">
                <div class="email-display">
                    <i class="ri-mail-line"></i>
                    <strong><?= htmlspecialchars($email); ?></strong>
                </div>
                
                <!-- Étape 1: Saisie du code OTP -->
                <div id="step1">
                    <div class="code-input-group">
                        <input type="text" id="otpCode" maxlength="6" placeholder="000000" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    
                    <div class="timer" id="timer"></div>
                    
                    <button class="btn-verify" id="verifyCodeBtn">
                        <i class="ri-check-line"></i> Vérifier le code
                    </button>
                    
                    <div class="resend-link">
                        <a href="#" id="resendCodeBtn">Renvoyer le code</a>
                    </div>
                </div>
                
                <!-- Étape 2: Nouveau mot de passe -->
                <div id="step2" class="password-section">
                    <div class="input-group-custom">
                        <i class="ri-lock-line"></i>
                        <input type="password" id="newPassword" placeholder="Nouveau mot de passe">
                        <i class="ri-eye-off-line toggle-password" data-target="newPassword" style="cursor: pointer;"></i>
                    </div>
                    <div class="strength-meter">
                        <div class="strength-meter-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-text" id="strengthText">Minimum 8 caractères</div>
                    
                    <div class="input-group-custom">
                        <i class="ri-lock-line"></i>
                        <input type="password" id="confirmPassword" placeholder="Confirmer le mot de passe">
                        <i class="ri-eye-off-line toggle-password" data-target="confirmPassword" style="cursor: pointer;"></i>
                    </div>
                    
                    <button class="btn-verify" id="resetPasswordBtn">
                        <i class="ri-refresh-line"></i> Réinitialiser le mot de passe
                    </button>
                </div>
                
                <div class="back-to-login">
                    <a href="<?= base_url(); ?>">
                        <i class="ri-arrow-left-line"></i> Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('ri-eye-off-line');
                this.classList.toggle('ri-eye-line');
            });
        });
        
        // Timer de 15 minutes
        let timeLeft = 900; // 15 minutes en secondes
        const timerElement = document.getElementById('timer');
        
        function startTimer() {
            const timer = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.innerHTML = `Le code expire dans ${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    timerElement.innerHTML = 'Code expiré. Veuillez renvoyer un nouveau code.';
                    document.getElementById('verifyCodeBtn').disabled = true;
                }
                timeLeft--;
            }, 1000);
        }
        
        startTimer();
        
        // Force du mot de passe
        const passwordInput = document.getElementById('newPassword');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';
            let color = '';
            
            if (password.length === 0) {
                strength = 0;
                message = 'Minimum 8 caractères';
                color = '#e0e0e0';
            } else if (password.length < 8) {
                strength = 25;
                message = 'Trop court - minimum 8 caractères';
                color = '#ff4444';
            } else {
                strength = 50;
                message = 'Moyen';
                color = '#ffaa00';
                
                if (password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password)) {
                    strength = 100;
                    message = 'Fort';
                    color = '#00cc66';
                } else if (password.length >= 8 && /[0-9]/.test(password)) {
                    strength = 75;
                    message = 'Bon';
                    color = '#ff6600';
                }
            }
            
            strengthFill.style.width = strength + '%';
            strengthFill.style.backgroundColor = color;
            strengthText.innerHTML = message;
            strengthText.style.color = color;
        });
        
        // Vérifier le code OTP
        document.getElementById('verifyCodeBtn').addEventListener('click', async function() {
            const code = document.getElementById('otpCode').value;
            
            if (code.length !== 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Code invalide',
                    text: 'Veuillez entrer un code à 6 chiffres',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Vérification...';
            btn.disabled = true;
            
            try {
                const response = await fetch('<?= base_url("auth/verify_code"); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'code=' + encodeURIComponent(code)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Cacher l'étape 1 et montrer l'étape 2
                    document.getElementById('step1').style.display = 'none';
                    document.getElementById('step2').style.display = 'block';
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Code valide !',
                        text: 'Veuillez créer votre nouveau mot de passe',
                        confirmButtonColor: '#ff6600',
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Code invalide',
                        text: data.message,
                        confirmButtonColor: '#ff6600',
                        html: false
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue',
                    confirmButtonColor: '#ff6600',
                    html: false
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
        
        // Réinitialiser le mot de passe
        document.getElementById('resetPasswordBtn').addEventListener('click', async function() {
            const password = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password.length < 6) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Le mot de passe doit contenir au moins 8 caractères',
                        confirmButtonColor: '#ff6600',
                        html: false
                    });
                return;
            }
            
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Les mots de passe ne correspondent pas',
                    confirmButtonColor: '#ff6600',
                    html: false
                });
                return;
            }
            
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Réinitialisation...';
            btn.disabled = true;
            
            try {
                const response = await fetch('<?= base_url("auth/verify_code"); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'password=' + encodeURIComponent(password) + 
                          '&confirm_password=' + encodeURIComponent(confirmPassword)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Mot de passe modifié !',
                        text: data.message,
                        confirmButtonColor: '#ff6600',
                        confirmButtonText: 'Se connecter',
                        html: false
                    }).then(() => {
                        window.location.href = '<?= base_url(); ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message,
                        confirmButtonColor: '#ff6600',
                        html: false
                    });
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue',
                    confirmButtonColor: '#ff6600',
                    html: false
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });
        
        // Renvoyer le code
        document.getElementById('resendCodeBtn').addEventListener('click', async function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Renvoyer le code ?',
                text: 'Un nouveau code sera envoyé à votre adresse email',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ff6600',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, renvoyer',
                cancelButtonText: 'Annuler'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const btn = this;
                    btn.innerHTML = 'Envoi...';
                    btn.style.pointerEvents = 'none';
                    
                    try {
                        const response = await fetch('<?= base_url("auth/resend_otp"); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'email=' + encodeURIComponent('<?= addslashes(htmlspecialchars($email, ENT_QUOTES, 'UTF-8')); ?>')
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Code renvoyé !',
                                text: data.message,
                                confirmButtonColor: '#ff6600',
                                html: false
                            });
                            // Réinitialiser le timer
                            timeLeft = 900;
                            startTimer();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: data.message,
                                confirmButtonColor: '#ff6600',
                                html: false
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Une erreur est survenue',
                            confirmButtonColor: '#ff6600',
                            html: false
                        });
                    }
                    
                    btn.innerHTML = 'Renvoyer le code';
                    btn.style.pointerEvents = 'auto';
                }
            });
        });
    </script>
</body>
</html>