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
                                             <a href="index.html" class="logo-dark">
                                                  <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" height="24" alt="abemarket logo">
                                             </a>
                                        </div>

                                        <h2 class="fw-bold fs-24">Sign In</h2>

                                        <p class="text-muted mt-1 mb-4">Enter your email address and password to access admin panel.</p>

                                        <div class="mb-5">
                                            <?php if($this->session->flashdata('sms')): ?>
                                                <div class="alert alert-<?php echo strpos($this->session->flashdata('sms'), 'success') !== false ? 'success' : 'danger'; ?>">
                                                    <?php echo $this->session->flashdata('sms'); ?>
                                                </div>
                                            <?php endif; ?>
                                             
                                             <form action="<?=base_url('Admin/do_login')?>" class="authentication-form" method="POST" id="loginForm">

                                                  <div class="mb-3">
                                                       <label class="form-label" for="example-email">Email</label>
                                                       <input type="email" id="example-email" name="email" class="form-control" placeholder="Enter your email">
                                                       <div class="error-message" id="emailError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <a href="auth-password" class="float-end text-muted text-unline-dashed ms-1">Reset password</a>
                                                       <label class="form-label" for="example-password">Password</label>
                                                       <div class="password-wrapper">
                                                           <input type="password" id="example-password" name="password" class="form-control" placeholder="Enter your password">
                                                           <button type="button" class="password-toggle" id="togglePassword">
                                                               <i class="bx bx-hide" id="toggleIcon"></i>
                                                           </button>
                                                       </div>
                                                       <div class="error-message" id="passwordError"></div>
                                                  </div>

                                                  <div class="mb-3">
                                                       <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                                            <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                                       </div>
                                                  </div>

                                                  <div class="mb-1 text-center d-grid">
                                                       <button class="btn btn-soft-primary" type="submit">Sign In</button>
                                                  </div>
                                             </form>

                                             <p class="mt-3 fw-semibold no-span">OR sign with</p>

                                             <div class="d-grid gap-2">
                                                  <a href="javascript:void(0);" class="btn btn-soft-dark"><i class="bx bxl-google fs-20 me-1"></i> Sign in with Google</a>
                                                  <a href="javascript:void(0);" class="btn btn-soft-primary"><i class="bx bxl-facebook fs-20 me-1"></i> Sign in with Facebook</a>
                                             </div>
                                        </div>

                                        <p class="text-danger text-center">Don't have an account? <a href="auth-signup.html" class="text-dark fw-bold ms-1">Sign Up</a></p>
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
        // Email validation function
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Function to show error
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            const inputElement = document.getElementById(elementId.replace('Error', ''));
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
            
            if (inputElement) {
                inputElement.classList.add('is-invalid');
                inputElement.classList.remove('is-valid');
            }
        }

        // Function to clear error
        function clearError(elementId) {
            const errorElement = document.getElementById(elementId);
            const inputElement = document.getElementById(elementId.replace('Error', ''));
            
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
            
            if (inputElement) {
                inputElement.classList.remove('is-invalid');
                inputElement.classList.add('is-valid');
            }
        }

        // Function to clear all errors
        function clearAllErrors() {
            clearError('emailError');
            clearError('passwordError');
        }

        // Form validation function
        function validateForm(event) {
            event.preventDefault();
            
            let isValid = true;
            
            // Get values
            const email = document.getElementById('example-email').value.trim();
            const password = document.getElementById('example-password').value.trim();
            
            // Clear previous errors
            clearAllErrors();
            
            // Email validation
            if (email === '') {
                showError('emailError', 'Please enter your email address');
                isValid = false;
            } else if (!validateEmail(email)) {
                showError('emailError', 'Please enter a valid email address (e.g., name@domain.com)');
                isValid = false;
            }
            
            // Password validation
            if (password === '') {
                showError('passwordError', 'Please enter your password');
                isValid = false;
            } else if (password.length < 6) {
                showError('passwordError', 'Password must be at least 6 characters long');
                isValid = false;
            }
            
            // If valid, submit the form
            if (isValid) {
                // Disable button to prevent double submission
                const submitButton = document.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Signing In...';
                
                // Submit the form
                event.target.submit();
            } else {
                // Scroll to first invalid field
                const firstErrorField = document.querySelector('.is-invalid');
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorField.focus();
                }
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('example-password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            }
        }

        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('example-email');
            const passwordInput = document.getElementById('example-password');
            const toggleButton = document.getElementById('togglePassword');
            
            // Add toggle password event
            if (toggleButton) {
                toggleButton.addEventListener('click', togglePasswordVisibility);
            }
            
            // Add submit event
            if (form) {
                form.addEventListener('submit', validateForm);
            }
            
            // Real-time email validation
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const email = this.value.trim();
                    if (email !== '') {
                        if (!validateEmail(email)) {
                            showError('emailError', 'Please enter a valid email address');
                        } else {
                            clearError('emailError');
                        }
                    } else {
                        clearError('emailError');
                    }
                });
                
                emailInput.addEventListener('input', function() {
                    if (this.value.trim() !== '' && validateEmail(this.value.trim())) {
                        clearError('emailError');
                    }
                });
            }
            
            // Real-time password validation
            if (passwordInput) {
                passwordInput.addEventListener('blur', function() {
                    const password = this.value.trim();
                    if (password !== '' && password.length < 6) {
                        showError('passwordError', 'Password must be at least 6 characters long');
                    } else if (password !== '') {
                        clearError('passwordError');
                    }
                });
                
                passwordInput.addEventListener('input', function() {
                    if (this.value.trim() !== '' && this.value.trim().length >= 6) {
                        clearError('passwordError');
                    }
                });
            }
            
            // Clear errors on focus
            if (emailInput) {
                emailInput.addEventListener('focus', function() {
                    clearError('emailError');
                });
            }
            
            if (passwordInput) {
                passwordInput.addEventListener('focus', function() {
                    clearError('passwordError');
                });
            }
            
            // Remember me functionality
            const rememberCheckbox = document.getElementById('checkbox-signin');
            if (rememberCheckbox && localStorage.getItem('rememberedEmail')) {
                emailInput.value = localStorage.getItem('rememberedEmail');
                rememberCheckbox.checked = true;
            }
            
            // Save email if remember me is checked
            if (rememberCheckbox) {
                rememberCheckbox.addEventListener('change', function() {
                    if (this.checked && emailInput.value.trim()) {
                        localStorage.setItem('rememberedEmail', emailInput.value.trim());
                    } else {
                        localStorage.removeItem('rememberedEmail');
                    }
                });
            }
        });
     </script>
</body>


<!-- Mirrored from techzaa.in/larkon/admin/auth-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 09 Apr 2026 03:48:14 GMT -->
</html>