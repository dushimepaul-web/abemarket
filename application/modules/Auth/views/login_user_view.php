<!DOCTYPE html>
<html lang="en" class="h-100">


<!-- Mirrored from techzaa.in/larkon/admin/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Apr 2026 03:48:14 GMT -->
<head>
     <!-- Title Meta -->
     <meta charset="utf-8" />
     <title>AbeMarket</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="A fully responsive premium admin dashboard template" />
     <meta name="author" content="Techzaa" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

     <!-- App favicon -->
     <link rel="shortcut icon" href="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>">

     <!-- Vendor css (Require in all Page) -->
     <link href="<?=base_url()?>assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

     <!-- Icons css (Require in all Page) -->
     <link href="<?=base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />

     <!-- App css (Require in all Page) -->
     <link href="<?=base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

     <!-- Theme Config js (Require in all Page) -->
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

                                        <h2 class="fw-bold fs-24">Sign In</h2>

                                        <p class="text-muted mt-1 mb-4">Enter your email address and password to access admin panel.</p>

                                        <div class="mb-5">
                                             <div id="loginAlert"></div>
                                             
                                             <form class="authentication-form" id="loginForm">

                                                  <div class="mb-3">
                                                       <label class="form-label" for="login_email">Email</label>
                                                       <input type="email" id="login_email" name="email" class="form-control" placeholder="Votre adresse email" required>
                                                       <div class="error-message" id="emailError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <a href="<?= base_url('auth/forgot_password'); ?>" class="float-end text-muted text-unline-dashed ms-1">Mot de passe oublié ?</a>
                                                       <label class="form-label" for="login_password">Mot de passe</label>
                                                       <div class="password-wrapper">
                                                           <input type="password" id="login_password" name="password" class="form-control" placeholder="Votre mot de passe" required>
                                                           <button type="button" class="password-toggle" id="togglePassword">
                                                               <i class="bx bx-hide" id="toggleIcon"></i>
                                                           </button>
                                                       </div>
                                                       <div class="error-message" id="passwordError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                                            <label class="form-check-label" for="checkbox-signin">Se souvenir de moi</label>
                                                       </div>
                                                  </div>

                                                  <div class="mb-1 text-center d-grid">
                                                       <button class="btn btn-soft-primary" type="submit" id="loginBtn">Se connecter</button>
                                                  </div>
                                             </form>

                                             <p class="mt-3 fw-semibold no-span">OR sign with</p>

                                             <div class="d-grid gap-2">
                                                  <a href="javascript:void(0);" class="btn btn-soft-dark"><i class="bx bxl-google fs-20 me-1"></i> Sign in with Google</a>
                                                  <a href="javascript:void(0);" class="btn btn-soft-primary"><i class="bx bxl-facebook fs-20 me-1"></i> Sign in with Facebook</a>
                                             </div>
                                        </div>

                                         <p class="text-danger text-center">Don't have an account? <a href="<?= base_url('auth/register_page'); ?>" class="text-dark fw-bold ms-1">Sign Up</a></p>
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

     <!-- Vendor Javascript (Require in all Page) -->
     <script src="<?=base_url()?>assets/js/vendor.js"></script>

     <!-- App Javascript (Require in all Page) -->
     <script src="<?=base_url()?>assets/js/app.js"></script>

     <script>
        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputId = elementId.replace('Error', '');
            const inputElement = document.getElementById(inputId);
            if (errorElement) { errorElement.textContent = message; errorElement.style.display = 'block'; }
            if (inputElement) { inputElement.classList.add('is-invalid'); inputElement.classList.remove('is-valid'); }
        }

        function clearError(elementId) {
            const errorElement = document.getElementById(elementId);
            const inputId = elementId.replace('Error', '');
            const inputElement = document.getElementById(inputId);
            if (errorElement) { errorElement.textContent = ''; errorElement.style.display = 'none'; }
            if (inputElement) { inputElement.classList.remove('is-invalid'); inputElement.classList.add('is-valid'); }
        }

        function clearAllErrors() { clearError('emailError'); clearError('passwordError'); }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('login_email');
            const passwordInput = document.getElementById('login_password');
            const toggleButton = document.getElementById('togglePassword');
            const alertDiv = document.getElementById('loginAlert');

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const icon = document.getElementById('toggleIcon');
                    if (passwordInput.type === 'password') { passwordInput.type = 'text'; icon.classList.remove('bx-hide'); icon.classList.add('bx-show'); }
                    else { passwordInput.type = 'password'; icon.classList.remove('bx-show'); icon.classList.add('bx-hide'); }
                });
            }

            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    clearAllErrors();
                    alertDiv.innerHTML = '';

                    const email = emailInput.value.trim();
                    const password = passwordInput.value;
                    let isValid = true;

                    if (!email) { showError('emailError', 'Veuillez entrer votre email'); isValid = false; }
                    else if (!validateEmail(email)) { showError('emailError', 'Email invalide'); isValid = false; }

                    if (!password) { showError('passwordError', 'Veuillez entrer votre mot de passe'); isValid = false; }
                    else if (password.length < 8) { showError('passwordError', 'Minimum 8 caractères'); isValid = false; }

                    if (!isValid) {
                        const firstError = document.querySelector('.is-invalid');
                        if (firstError) { firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); firstError.focus(); }
                        return;
                    }

                    const submitBtn = document.getElementById('loginBtn');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Connexion...';

                    try {
                        const response = await fetch('<?= base_url("auth/login"); ?>', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                            body: new URLSearchParams(new FormData(form))
                        });
                        const data = await response.json();

                        if (data.success) {
                            alertDiv.textContent = data.message;
                            alertDiv.className = 'alert alert-success';
                            setTimeout(() => { window.location.href = data.redirect || '<?= base_url(); ?>'; }, 800);
                        } else {
                            alertDiv.textContent = data.message || 'Email ou mot de passe incorrect.';
                            alertDiv.className = 'alert alert-danger';
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Se connecter';
                            passwordInput.value = '';
                        }
                    } catch (err) {
                        alertDiv.textContent = 'Erreur de connexion. Veuillez réessayer.';
                        alertDiv.className = 'alert alert-danger';
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Se connecter';
                    }
                });
            }

            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const val = this.value.trim();
                    if (val !== '' && !validateEmail(val)) showError('emailError', 'Email invalide');
                    else clearError('emailError');
                });
                emailInput.addEventListener('input', function() { if (validateEmail(this.value.trim())) clearError('emailError'); });
                emailInput.addEventListener('focus', function() { clearError('emailError'); });
            }

            if (passwordInput) {
                passwordInput.addEventListener('blur', function() {
                    const val = this.value;
                    if (val !== '' && val.length < 8) showError('passwordError', 'Minimum 8 caractères');
                    else clearError('passwordError');
                });
                passwordInput.addEventListener('input', function() { if (this.value.length >= 8) clearError('passwordError'); });
                passwordInput.addEventListener('focus', function() { clearError('passwordError'); });
            }
        });
     </script>
</body>


<!-- Mirrored from techzaa.in/larkon/admin/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Apr 2026 03:48:14 GMT -->
</html>