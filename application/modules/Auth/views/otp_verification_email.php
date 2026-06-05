<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification OTP - ABEMARKET</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .otp-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .otp-header {
            margin-bottom: 30px;
        }
        .otp-header .icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .otp-header .icon i {
            font-size: 40px;
            color: white;
        }
        .otp-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
        }
        .otp-header p {
            color: #6c757d;
            font-size: 14px;
        }
        .otp-header .email-display {
            background: #f0f7ff;
            padding: 10px;
            border-radius: 10px;
            margin-top: 15px;
            font-weight: 600;
            color: #0a66c2;
        }
        .otp-input-group {
            margin: 30px 0;
        }
        .otp-input-group input {
            width: 100%;
            padding: 18px;
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-family: monospace;
            transition: all 0.3s;
        }
        .otp-input-group input:focus {
            outline: none;
            border-color: #0a66c2;
            box-shadow: 0 0 0 3px rgba(10,102,194,0.1);
        }
        .otp-input-group input.error {
            border-color: #dc2626;
        }
        .timer {
            margin: 20px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .timer span {
            font-weight: bold;
            color: #ff6600;
            font-size: 18px;
        }
        .btn-verify {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: transform 0.2s;
            margin-bottom: 15px;
        }
        .btn-verify:hover {
            transform: translateY(-2px);
        }
        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .resend-link {
            color: #0a66c2;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .resend-link:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #dc2626;
            font-size: 13px;
            margin-top: 10px;
            display: none;
        }
        .success-message {
            color: #10b981;
            font-size: 13px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
<div class="otp-container">
    <div class="otp-header">
        <div class="icon">
            <i class="fas fa-envelope"></i>
        </div>
        <h2>Vérification du compte</h2>
        <p>Nous avons envoyé un code de vérification à :</p>
        <div class="email-display" id="emailDisplay"></div>
    </div>

    <div class="otp-input-group">
        <input type="text" id="otp_code" maxlength="6" placeholder="------" autocomplete="off" inputmode="numeric">
        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>
    </div>

    <div class="timer">
        <i class="fas fa-clock"></i> Le code expire dans : <span id="timer">10:00</span>
    </div>

    <button class="btn-verify" id="verifyBtn">
        <i class="fas fa-check-circle"></i> Vérifier mon compte
    </button>

    <div>
        <a href="#" id="resendLink" class="resend-link">
            <i class="fas fa-redo-alt"></i> Renvoyer le code
        </a>
    </div>
</div>

<script>
    // Récupérer les paramètres de l'URL
    const urlParams = new URLSearchParams(window.location.search);
    let currentUserId = urlParams.get('user_id');
    let currentEmail = urlParams.get('email');
    let timerInterval = null;
    let timeLeft = 600; // 10 minutes en secondes

    // Afficher l'email
    const emailDisplay = document.getElementById('emailDisplay');
    if (currentEmail) {
        emailDisplay.innerHTML = `<i class="fas fa-envelope"></i> ${currentEmail}`;
    } else {
        emailDisplay.innerHTML = 'Email non disponible';
    }

    // Focus sur le champ OTP
    const otpInput = document.getElementById('otp_code');
    otpInput.focus();

    // Filtrer les caractères (uniquement chiffres)
    otpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        
        // Effacer les messages d'erreur quand l'utilisateur tape
        document.getElementById('errorMessage').style.display = 'none';
        document.getElementById('errorMessage').innerHTML = '';
        this.classList.remove('error');
    });

    // Soumettre automatiquement quand 6 chiffres sont saisis
    otpInput.addEventListener('keyup', function(e) {
        if (this.value.length === 6) {
            verifyCode();
        }
    });

    // Démarrer le timer
    function startTimer() {
        timerInterval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                document.getElementById('timer').innerHTML = '00:00';
                document.getElementById('errorMessage').innerHTML = 'Le code a expiré. Veuillez renvoyer un nouveau code.';
                document.getElementById('errorMessage').style.display = 'block';
                document.getElementById('verifyBtn').disabled = true;
            } else {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                document.getElementById('timer').innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);
    }

    startTimer();

    // Vérifier le code
    async function verifyCode() {
        const code = otpInput.value.trim();
        
        if (code.length !== 6) {
            document.getElementById('errorMessage').innerHTML = 'Veuillez saisir un code à 6 chiffres';
            document.getElementById('errorMessage').style.display = 'block';
            otpInput.classList.add('error');
            return;
        }
        
        const verifyBtn = document.getElementById('verifyBtn');
        const originalText = verifyBtn.innerHTML;
        verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Vérification...';
        verifyBtn.disabled = true;
        
        try {
            const response = await fetch('<?= base_url("auth/verify_otp") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'user_id=' + encodeURIComponent(currentUserId) + '&code=' + encodeURIComponent(code)
            });
            
            const data = await response.json();
            
            if (data.success) {
                clearInterval(timerInterval);
                document.getElementById('successMessage').innerHTML = 'Compte vérifié avec succès ! Redirection...';
                document.getElementById('successMessage').style.display = 'block';
                
                setTimeout(() => {
                    window.location.href = '<?= base_url("Auth/choose_profile_page") ?>';
                }, 2000);
            } else {
                document.getElementById('errorMessage').innerHTML = data.message;
                document.getElementById('errorMessage').style.display = 'block';
                otpInput.classList.add('error');
                otpInput.value = '';
                otpInput.focus();
                verifyBtn.innerHTML = originalText;
                verifyBtn.disabled = false;
            }
        } catch (error) {
            document.getElementById('errorMessage').innerHTML = 'Une erreur est survenue. Veuillez réessayer.';
            document.getElementById('errorMessage').style.display = 'block';
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
        }
    }

    // Renvoyer le code
    async function resendCode() {
        const resendLink = document.getElementById('resendLink');
        resendLink.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
        resendLink.style.pointerEvents = 'none';
        
        try {
            const response = await fetch('<?= base_url("auth/resend_otp") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'user_id=' + encodeURIComponent(currentUserId) + '&email=' + encodeURIComponent(currentEmail)
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Réinitialiser le timer
                clearInterval(timerInterval);
                timeLeft = 600;
                startTimer();
                
                // Réinitialiser le champ
                otpInput.value = '';
                otpInput.focus();
                otpInput.classList.remove('error');
                document.getElementById('errorMessage').style.display = 'none';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Code renvoyé !',
                    text: 'Un nouveau code a été envoyé à votre adresse email.',
                    confirmButtonColor: '#0a66c2',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: data.message,
                    confirmButtonColor: '#ff6600'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Impossible de renvoyer le code. Veuillez réessayer.',
                confirmButtonColor: '#ff6600'
            });
        } finally {
            resendLink.innerHTML = '<i class="fas fa-redo-alt"></i> Renvoyer le code';
            resendLink.style.pointerEvents = 'auto';
        }
    }

    // Événements
    document.getElementById('verifyBtn').addEventListener('click', verifyCode);
    document.getElementById('resendLink').addEventListener('click', function(e) {
        e.preventDefault();
        resendCode();
    });
</script>
</body>
</html>