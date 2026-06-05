<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
     <title><?= $this->Model->get_setting('site_name', 'AbeMarket') ?></title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="A fully responsive premium admin dashboard template" />
     <meta name="author" content="Techzaa" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />

     <!-- App favicon -->
     <link rel="shortcut icon" href="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_favicon', 'assets/fro.png')) ?>">

     <!-- Vendor css (Require in all Page) -->
     <link href="<?=base_url()?>assets/css/vendor.min.css" rel="stylesheet" type="text/css" />

     <!-- Icons css (Require in all Page) -->
     <link href="<?=base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />

     <!-- App css (Require in all Page) -->
     <link href="<?=base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

     <!-- Theme Config js (Require in all Page) -->
     <script src="<?=base_url()?>assets/js/config.js"></script>

     <!-- jQuery (OBLIGATOIRE avant Toastr) -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     
     <!-- Toastr CSS -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
     
     <!-- Toastr JS (après jQuery) -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

     <style>
     .toast-success {
         background-color: #28a745 !important;
     }
     .toast-error {
         background-color: #dc3545 !important;
     }
     .toast-info {
         background-color: #17a2b8 !important;
     }
     .toast-warning {
         background-color: #ffc107 !important;
     }
     </style>
</head>

<body>
     <!-- START Wrapper -->
     <div class="wrapper">