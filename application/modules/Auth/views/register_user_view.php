<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
     <meta charset="utf-8" />
     <title>AbeMarket - Inscription</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="Créez votre compte AbeMarket" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

     <!-- App favicon -->
     <link rel="shortcut icon" href="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>">

     <!-- Vendor css -->
     <link href="<?=base_url()?>assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

     <!-- Icons css -->
     <link href="<?=base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />

     <!-- App css -->
     <link href="<?=base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

     <!-- Theme Config js -->
     <script src="<?=base_url()?>assets/js/config.js"></script>
     
     <style>
         .error-message {
             color: #dc3545;
             font-size: 0.875rem;
             margin-top: 0.25rem;
         }
         
         .is-invalid {
             border-color: #dc3545 !important;
         }
         
         .is-valid {
             border-color: #28a745 !important;
         }
         
         .password-wrapper {
             position: relative;
         }
         
         .password-toggle {
             position: absolute;
             right: 10px;
             top: 50%;
             transform: translateY(-50%);
             cursor: pointer;
             color: #6c757d;
             z-index: 10;
             background: transparent;
             border: none;
             padding: 0;
         }
         
         .password-toggle:hover {
             color: #0d6efd;
         }
         
         .password-toggle i {
             font-size: 1.1rem;
         }
         
         .name-row {
             display: flex;
             gap: 12px;
         }
         
         .name-row > div {
             flex: 1;
         }
     </style>
</head>

<body class="h-100">
     <div class="d-flex flex-column h-100 p-3">
          <div class="d-flex flex-column flex-grow-1">
               <div class="row h-100">
                    <div class="col-xxl-7">
                         <div class="row justify-content-center h-100">
                              <div class="col-lg-6 py-lg-5">
                                   <div class="d-flex flex-column h-100 justify-content-center">
                                        <div class="auth-logo mb-4">
                                             <a href="<?= base_url(); ?>" class="logo-dark">
                                                  <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" style="max-width:200px; height:auto; display:block;" alt="AbeMarket">
                                             </a>
                                        </div>

                                        <h2 class="fw-bold fs-24">Créer un compte</h2>

                                        <p class="text-muted mt-1 mb-4">Rejoignez AbeMarket et commencez à commander.</p>

                                        <div class="mb-5">
                                             <div id="registerAlert"></div>

                                             <form id="registerForm">

                                                  <div class="name-row mb-3">
                                                       <div>
                                                            <label class="form-label" for="prenom">Prénom</label>
                                                            <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Votre prénom" required>
                                                            <div class="error-message" id="prenomError"></div>
                                                       </div>
                                                       <div>
                                                            <label class="form-label" for="nom">Nom</label>
                                                            <input type="text" id="nom" name="nom" class="form-control" placeholder="Votre nom" required>
                                                            <div class="error-message" id="nomError"></div>
                                                       </div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <label class="form-label" for="email">Email</label>
                                                       <input type="email" id="email" name="email" class="form-control" placeholder="Votre adresse email" required>
                                                       <div class="error-message" id="emailError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <label class="form-label" for="telephone">Téléphone</label>
                                                       <input type="tel" id="telephone" name="telephone" class="form-control" placeholder="Votre numéro de téléphone" required>
                                                       <div class="error-message" id="telephoneError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <label class="form-label" for="password">Mot de passe</label>
                                                       <div class="password-wrapper">
                                                            <input type="password" id="password" name="password" class="form-control" placeholder="Créez votre mot de passe" required minlength="8">
                                                           <button type="button" class="password-toggle" id="togglePassword">
                                                               <i class="bx bx-hide" id="toggleIcon1"></i>
                                                           </button>
                                                       </div>
                                                       <div class="error-message" id="passwordError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <label class="form-label" for="confirm_password">Confirmer le mot de passe</label>
                                                       <div class="password-wrapper">
                                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirmez votre mot de passe" required minlength="8">
                                                           <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                                               <i class="bx bx-hide" id="toggleIcon2"></i>
                                                           </button>
                                                       </div>
                                                       <div class="error-message" id="confirmPasswordError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" value="1" required>
                                                            <label class="form-check-label" for="agree_terms">
                                                                J'accepte les <a href="<?= base_url('pages/terms'); ?>" target="_blank" class="text-primary">conditions d'utilisation</a>
                                                            </label>
                                                       </div>
                                                       <div class="error-message" id="agreeTermsError"></div>
                                                  </div>

                                                  <div class="mb-1 text-center d-grid">
                                                       <button class="btn btn-soft-primary" type="submit" id="registerBtn">Créer mon compte</button>
                                                  </div>
                                             </form>
                                        </div>

                                        <p class="text-danger text-center">Déjà un compte ? <a href="<?= base_url('auth/login_page'); ?>" class="text-dark fw-bold ms-1">Se connecter</a></p>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-xxl-5 d-none d-xxl-flex">
                         <div class="card h-100 mb-0 overflow-hidden">
                              <div class="d-flex flex-column h-100">
                                   <img src="<?=base_url()?>assets/images/small/img-10.jpg" alt="" class="w-100 h-100">
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>

     <!-- Vendor Javascript -->
     <script src="<?=base_url()?>assets/js/vendor.js"></script>

     <!-- App Javascript -->
     <script src="<?=base_url()?>assets/js/app.js"></script>

     <script>
        // Email validation
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Phone validation
        function validatePhone(phone) {
            const re = /^[0-9+\s()-]{8,15}$/;
            return re.test(phone);
        }

        // Show error
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputId = elementId.replace('Error', '');
            const inputElement = document.getElementById(inputId);
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
            if (inputElement) {
                inputElement.classList.add('is-invalid');
                inputElement.classList.remove('is-valid');
            }
        }

        // Clear error
        function clearError(elementId) {
            const errorElement = document.getElementById(elementId);
            const inputId = elementId.replace('Error', '');
            const inputElement = document.getElementById(inputId);
            
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
            if (inputElement) {
                inputElement.classList.remove('is-invalid');
                inputElement.classList.add('is-valid');
            }
        }

        // Clear all errors
        function clearAllErrors() {
            ['prenomError', 'nomError', 'emailError', 'telephoneError', 'passwordError', 'confirmPasswordError', 'agreeTermsError'].forEach(clearError);
        }

        // Toggle password
        function togglePass(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }

        // Form validation
        async function validateForm(event) {
            event.preventDefault();
            let isValid = true;
            clearAllErrors();

            const prenom = document.getElementById('prenom').value.trim();
            const nom = document.getElementById('nom').value.trim();
            const email = document.getElementById('email').value.trim();
            const telephone = document.getElementById('telephone').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const agreeTerms = document.getElementById('agree_terms').checked;

            if (!prenom) { showError('prenomError', 'Veuillez entrer votre prénom'); isValid = false; }
            if (!nom) { showError('nomError', 'Veuillez entrer votre nom'); isValid = false; }
            
            if (!email) {
                showError('emailError', 'Veuillez entrer votre adresse email');
                isValid = false;
            } else if (!validateEmail(email)) {
                showError('emailError', 'Veuillez entrer une adresse email valide');
                isValid = false;
            }
            
            if (!telephone) {
                showError('telephoneError', 'Veuillez entrer votre numéro de téléphone');
                isValid = false;
            } else if (!validatePhone(telephone)) {
                showError('telephoneError', 'Veuillez entrer un numéro de téléphone valide');
                isValid = false;
            }
            
            if (!password) {
                showError('passwordError', 'Veuillez entrer un mot de passe');
                isValid = false;
            } else if (password.length < 8) {
                showError('passwordError', 'Le mot de passe doit contenir au moins 8 caractères');
                isValid = false;
            }
            
            if (password !== confirmPassword) {
                showError('confirmPasswordError', 'Les mots de passe ne correspondent pas');
                isValid = false;
            }
            
            if (!agreeTerms) {
                showError('agreeTermsError', 'Vous devez accepter les conditions d\'utilisation');
                isValid = false;
            }

            if (isValid) {
                const submitButton = document.getElementById('registerBtn');
                const alertDiv = document.getElementById('registerAlert');
                submitButton.disabled = true;
                submitButton.textContent = 'Inscription...';

                try {
                    const response = await fetch('<?= base_url("auth/register"); ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                        body: new URLSearchParams(new FormData(document.getElementById('registerForm')))
                    });
                    const data = await response.json();

                    if (data.success) {
                        alertDiv.textContent = data.message;
                        alertDiv.className = 'alert alert-success';
                        setTimeout(() => { window.location.href = data.redirect_url || '<?= base_url('auth/otp_verification_page'); ?>'; }, 1200);
                    } else {
                        alertDiv.textContent = data.message || "Erreur lors de l'inscription.";
                        alertDiv.className = 'alert alert-danger';
                        submitButton.disabled = false;
                        submitButton.textContent = 'Créer mon compte';
                    }
                } catch (err) {
                    alertDiv.textContent = 'Erreur de connexion. Veuillez réessayer.';
                    alertDiv.className = 'alert alert-danger';
                    submitButton.disabled = false;
                    submitButton.textContent = 'Créer mon compte';
                }
            } else {
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        }

        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');

            if (togglePasswordBtn) {
                togglePasswordBtn.addEventListener('click', function() { togglePass('password', 'toggleIcon1'); });
            }
            if (toggleConfirmBtn) {
                toggleConfirmBtn.addEventListener('click', function() { togglePass('confirm_password', 'toggleIcon2'); });
            }

            if (form) {
                form.addEventListener('submit', validateForm);
            }

            // Real-time field validation
            ['prenom', 'nom', 'email', 'telephone', 'password', 'confirm_password'].forEach(function(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('blur', function() {
                        const val = this.value.trim();
                        if (val !== '') {
                            if (fieldId === 'email' && !validateEmail(val)) {
                                showError('emailError', 'Email invalide');
                            } else if (fieldId === 'telephone' && !validatePhone(val)) {
                                showError('telephoneError', 'Téléphone invalide');
                            } else if (fieldId === 'password' && val.length < 8) {
                                showError('passwordError', 'Minimum 8 caractères');
                            } else {
                                clearError(fieldId + 'Error');
                            }
                        }
                    });
                    field.addEventListener('input', function() {
                        const val = this.value.trim();
                        if (fieldId === 'email' && validateEmail(val)) clearError('emailError');
                        if (fieldId === 'telephone' && validatePhone(val)) clearError('telephoneError');
                        if (fieldId === 'password' && val.length >= 8) clearError('passwordError');
                        if (fieldId === 'confirm_password') {
                            const pwd = document.getElementById('password').value;
                            if (val === pwd && val !== '') clearError('confirmPasswordError');
                        }
                    });
                    field.addEventListener('focus', function() {
                        clearError(fieldId + 'Error');
                    });
                }
            });
        });
     </script>
</body>
</html>
