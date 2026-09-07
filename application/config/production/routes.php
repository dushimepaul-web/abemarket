<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Copie exacte des routes de développement pour la production
$route['shop'] = 'home/shop';
$route['home'] = 'home/index';
$route['about'] = 'home/about';
$route['blog'] = 'home/blog';
$route['offres'] = 'home/offres';
$route['faq'] = 'home/faq';
$route['contact'] = 'home/contact';
$route['cart'] = 'home/cart';
$route['checkout'] = 'home/checkout';
$route['checkout/process'] = 'home/processOrder';
$route['payment/pending/(:any)'] = 'home/payment_pending/$1';
$route['payment/success/(:any)'] = 'Payment/Payment/success/$1';
$route['payment/submit-reference'] = 'home/submit_payment_reference';
$route['wishlist'] = 'home/wishlist';
$route['privacy-policy'] = 'home/privacy_policy';
$route['sellers'] = 'home/sellers';
$route['search'] = 'home/search';

$route['category/(:any)'] = 'home/category/$1';
$route['product/(:any)'] = 'home/product/$1';
$route['seller/(:any)'] = 'home/seller/$1';
$route['order-success/(:any)'] = 'home/order_success/$1';
