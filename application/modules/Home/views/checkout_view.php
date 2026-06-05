

    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-section">
        <div class="custom-container">
            <div class="breadcrumb-contain">
                <h2>Checkout</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="index.html">
                                <i class="ri-home-3-fill"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Return login section start -->
    <section class="return-login-section section-t-space">
        <div class="custom-container">
            <div class="return-box">
                <h3>Returning customer?</h3>
                <button class="btn" data-bs-toggle="modal" data-bs-target="#authenticationModal">Click here to
                    Login</button>
            </div>
        </div>
    </section>
    <!-- Return login section end -->

    <!-- Checkout Section Start -->
    <section class="checkout-section-new section-t-space">
        <div class="custom-container">
            <div class="row g-sm-4 g-3">
                <div class="col-xl-7 col-lg-6">
                    <div class="checkout-left-box">
                        <div class="billing-box checkbox-bg-color">
                            <div class="checkout-title">
                                <h4>Billing details</h4>
                            </div>

                            <form class="row g-sm-4 g-3 needs-validation theme-form" novalidate>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details1" class="form-label">First name <span>*</span></label>
                                    <input type="text" placeholder="Enter your first name" class="form-control"
                                        id="details1">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details2" class="form-label">Last name <span>*</span></label>
                                    <input type="text" class="form-control" id="details2"
                                        placeholder="Enter your first name">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details4" class="form-label">Email Address <span>*</span></label>
                                    <input type="email" class="form-control" id="details4"
                                        placeholder="Enter your Email address">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details5" class="form-label">Phone Number <span>*</span></label>
                                    <input type="number" class="form-control" id="details5"
                                        placeholder="Enter your number" oninput="limitLength(this)" required>
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details6" class="form-label">Street Address <span>*</span></label>
                                    <input type="text" class="form-control" id="details6"
                                        placeholder="Enter your street address">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details7" class="form-label">Apartment/Suite (Optional)
                                        <span>*</span></label>
                                    <input type="text" class="form-control" id="details7"
                                        placeholder="Enter your apartment/suite (optional)">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details8" class="form-label">City <span>*</span></label>
                                    <input type="text" class="form-control" id="details8" placeholder="Enter your city">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details9" class="form-label">State/Province/Region
                                        <span>*</span></label>
                                    <input type="text" class="form-control" id="details9"
                                        placeholder="Enter your state/province/region">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details10" class="form-label">ZIP/Postal Code <span>*</span></label>
                                    <input type="text" class="form-control" id="details10"
                                        placeholder="Enter your ZIP/postal code">
                                </div>
                                <div class="col-xl-6 col-lg-12 col-sm-6">
                                    <label for="details11" class="form-label">Country <span>*</span></label>
                                    <input type="text" class="form-control" id="details11"
                                        placeholder="Enter your country">
                                </div>
                                <div class="col-12">
                                    <label for="details12" class="form-label">Payment Method <span>*</span></label>
                                    <select class="form-select" id="details12">
                                        <option selected disabled>Choose payment method</option>
                                        <option value="1">Credit/Debit Card</option>
                                        <option value="2">PayPal / Digital Wallet Options</option>
                                        <option value="3">Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <ul class="checkout-list">
                                        <li class="form-check theme-checkbox">
                                            <input class="form-check-input" type="checkbox" id="list">
                                            <label class="form-check-label" for="list">Create an account?</label>
                                        </li>
                                        <li class="form-check theme-checkbox">
                                            <input class="form-check-input" type="checkbox" id="list1">
                                            <label class="form-check-label" for="list1">Ship to a different
                                                address?</label>
                                        </li>
                                        <li class="form-check theme-checkbox">
                                            <input class="form-check-input" type="checkbox" id="list2">
                                            <label class="form-check-label" for="list2">Sign me up to receive email
                                                updates and news (optional)</label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <label for="details14" class="form-label">Order Notes (Optional)
                                        <span>*</span></label>
                                    <textarea class="form-control" id="details14" rows="4"
                                        placeholder="Any special instructions for the order"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-6">
                    <div class="checkout-right-box checkbox-bg-color">
                        <div class="checkout-title">
                            <h4>Your order</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table order-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="checkout-product-box">
                                                <a href="product-circle.html" class="product-image">
                                                    <img src="../assets/images/product/30.png" class="img-fluid" alt="">
                                                </a>
                                                <div class="product-contain">
                                                    <a href="product-color.html">
                                                        <h5>Canon EOS 1500D DSLR Camera Body + 18-55 mm <span>x2</span>
                                                        </h5>
                                                    </a>
                                                    <ul class="product-category-list">
                                                        <li>Brand: <span>Canon</span></li>
                                                        <li>Color: <span>Starlight</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$152.36</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="checkout-product-box">
                                                <a href="product-circle.html" class="product-image">
                                                    <img src="../assets/images/product/24.png" class="img-fluid" alt="">
                                                </a>
                                                <div class="product-contain">
                                                    <a href="product-color.html">
                                                        <h5>Refurb macbook air space gray m1 202009 <span>x1</span></h5>
                                                    </a>
                                                    <ul class="product-category-list">
                                                        <li>Brand: <span>Apple</span></li>
                                                        <li>Storage: <span>1TB</span></li>
                                                        <li>Color: <span>Dark Gray</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$32.45</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="checkout-product-box">
                                                <a href="product-circle.html" class="product-image">
                                                    <img src="../assets/images/product/26.png" class="img-fluid" alt="">
                                                </a>
                                                <div class="product-contain">
                                                    <a href="product-color.html">
                                                        <h5>EvoFox Game Box 32 GB with Asphalt 8 <span>x4</span></h5>
                                                    </a>
                                                    <ul class="product-category-list">
                                                        <li>Brand: <span>EvoFox</span></li>
                                                        <li>Color: <span>Light Gray</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$1025.35</td>
                                    </tr>
                                    <tr class="price-tb">
                                        <td>Subtotal</td>
                                        <td>$1210.16</td>
                                    </tr>
                                    <tr class="price-tb">
                                        <td>Tax</td>
                                        <td>$0.00</td>
                                    </tr>
                                    <tr class="price-tb">
                                        <td>Total</td>
                                        <td>$1210.16</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="accordion checkout-payment-accordion section-t-space-2" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    <div class="form-check">
                                        <input class="form-check-input" name="flexRadioDefault" type="radio" id="pay"
                                            checked>
                                        <label class="form-check-label" for="pay"><span class="circle"></span>
                                            <span>Buy Now, Pay Later <a href="#!">What is Klarna?</a></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p><i class="ri-check-line"></i> Enjoy <span>Buyer production</span> with
                                            Klarna. See <span>Payment options</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    <div class="form-check">
                                        <input class="form-check-input" name="flexRadioDefault" type="radio" id="pay1">
                                        <label class="form-check-label" for="pay1"><span class="circle"></span> Paypal
                                            Express Checkout</label>
                                    </div>
                                </div>
                                <div id="collapseTwo" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p><i class="ri-check-line"></i> Enjoy <span>Buyer production</span> with
                                            Klarna. See <span>Payment options</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    <div class="form-check">
                                        <input class="form-check-input" name="flexRadioDefault" type="radio" id="pay2">
                                        <label class="form-check-label" for="pay2"><span class="circle"></span> Amazon
                                            Pay</label>
                                    </div>
                                </div>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p><i class="ri-check-line"></i> Enjoy <span>Buyer production</span> with
                                            Klarna. See <span>Payment options</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-t-space-2">
                            <div class="checkout-information">
                                <p>Your personal data will be used to process your order, support your experience
                                    throughout this website, and for other purposes described in our <a
                                        href="#!">privacy policy</a>.</p>
                                <div class="form-check theme-checkbox">
                                    <input class="form-check-input checkbox_animated" type="checkbox" name="information"
                                        id="details">
                                    <label class="form-check-label" for="details">I Have Read And Agree To The Website
                                        Terms And Conditions <span>*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="section-t-space-2">
                            <button onclick="location.href = 'order_success.php';"
                                class="btn theme-bg-color text-white rounded-pill w-100">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Checkout Section End -->



    <!-- News-letter Section Start -->
    <section class="section-block-space newsletter-section">
        <div class="custom-container">
            <div class="newsletter-box">
                <img src="../assets/images/newsletter/1.svg" class="newsletter-1" alt="">
                <img src="../assets/images/newsletter/2.svg" class="newsletter-2" alt="">
                <img src="../assets/images/newsletter/3.svg" class="newsletter-3" alt="">
                <div class="row g-3">
                    <div class="col-xl-6">
                        <div class="newsletter-content">
                            <svg>
                                <use xlink:href="../assets/images/newsletter/newsletter-icon.svg#newsletter"></use>
                            </svg>
                            <div>
                                <h3>Subscribe to our newsletter</h3>
                                <h4>Get all the latest information on Events, sales and Offers</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <form class="newsletter-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter Your E-mail Address">
                                <button class="input-group-text btn newsletter-form-button">Subscribe Now!</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- News-letter Section End -->

    <!-- Footer Section Start -->
    <footer class="footer-section">
        <div class="custom-container">
            <div class="main-footer">
                <div class="row g-sm-4 g-3">
                    <div class="col-xl-3 col-md-4 col-sm-7">
                        <div class="footer-title-2">
                            <h4>Contact Info</h4>
                        </div>
                        <ul class="footer-content-list">
                            <li>
                                <a href="tel:(603)555-0123" class="content-box">
                                    <svg>
                                        <use xlink:href="../assets/svg/footer-icon.svg#contact"></use>
                                    </svg>
                                    <h4>(603) 555-0123</h4>
                                </a>
                            </li>
                            <li>
                                <a href="#!" class="content-box">
                                    <div class="footer-content-icon">
                                        <svg>
                                            <use xlink:href="../assets/svg/footer-icon.svg#location"></use>
                                        </svg>
                                    </div>
                                    <h5>3228 Bicetown Road Huntington, NY 11743</h5>
                                </a>
                            </li>
                            <li>
                                <a href="mailto:pixelstrap@contact.com" class="content-box">
                                    <div class="footer-content-icon">
                                        <svg>
                                            <use xlink:href="../assets/svg/footer-icon.svg#mail"></use>
                                        </svg>
                                    </div>
                                    <h5>pixelstrap@contact.com</h5>
                                </a>
                            </li>
                        </ul>
                        <div class="social-icon-box">
                            <h5 class="content-color">Stay Connected :</h5>
                            <ul class="social-icon-list">
                                <li>
                                    <a href="https://www.facebook.com/" target="_blank">
                                        <i class="ri-facebook-fill"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/" target="_blank">
                                        <i class="ri-twitter-x-line"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/" target="_blank">
                                        <i class="ri-instagram-fill"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.pinterest.ca/" target="_blank">
                                        <i class="ri-pinterest-fill"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
    
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="footer-title">
                            <h4>Information</h4>
                        </div>
                        <ul class="footer-list">
                            <li>
                                <a href="index.html">Home</a>
                            </li>
                            <li>
                                <a href="shop-left-sidebar.html">Collection</a>
                            </li>
                            <li>
                                <a href="about-us.html">About Us</a>
                            </li>
                            <li>
                                <a href="blog-3-grid.html">Blogs</a>
                            </li>
                            <li>
                                <a href="shop-banner.html">Offer</a>
                            </li>
                            <li>
                                <a href="search.html">Search</a>
                            </li>
                            <li>
                                <a href="faq.html">FAQ's</a>
                            </li>
                            <li>
                                <a href="contact-us.html">Contact us</a>
                            </li>
                        </ul>
                    </div>
    
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="footer-title">
                            <h4>Our Services</h4>
                        </div>
                        <ul class="footer-list">
                            <li>
                                <a href="shop-left-sidebar.html">Mobile Phones</a>
                            </li>
                            <li>
                                <a href="shop-left-sidebar.html">Television</a>
                            </li>
                            <li>
                                <a href="shop-left-sidebar.html">Washing Machine</a>
                            </li>
                            <li>
                                <a href="shop-left-sidebar.html">Women Fashion</a>
                            </li>
                            <li>
                                <a href="shop-left-sidebar.html">Laptop</a>
                            </li>
                        </ul>
                    </div>
    
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="footer-title">
                            <h4>My Account</h4>
                        </div>
                        <ul class="footer-list">
                            <li>
                                <a href="user-dashboard.html">My Account</a>
                            </li>
                            <li>
                                <a href="shop-left-sidebar.html">My Shop</a>
                            </li>
                            <li>
                                <a href="cart.html">My Cart</a>
                            </li>
                            <li>
                                <a href="checkout.html">Checkout</a>
                            </li>
                            <li>
                                <a href="wishlist.html">My Wishlist</a>
                            </li>
                            <li>
                                <a href="order-tracking.html">Tracking Order</a>
                            </li>
                        </ul>
                    </div>
    
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-4">
                        <div class="footer-title-2">
                            <h4>Get Shopping App</h4>
                        </div>
                        <ul class="footer-list-2">
                            <li>
                                <p>Quick & Direct ordering</p>
                            </li>
                            <li>
                                <p>Shop smarter, save time & Simply</p>
                            </li>
                            <li>
                                <p>Save more on app</p>
                            </li>
                        </ul>
                        <ul class="app-store-link">
                            <li>
                                <a href="https://play.google.com/store/apps" target="_blank">
                                    <img src="../assets/images/google-play.svg" class="img-fluid" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.apple.com/in/app-store/" target="_blank">
                                    <img src="../assets/images/app-store.svg" class="img-fluid" alt="">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
    
            <div class="sub-footer">
                <a href="index.html" class="sub-footer-logo">
                    <img src="../assets/images/logo/1-dark.svg" class="img-fluid" alt="">
                </a>
                <ul class="payment-list">
                    <li>
                        <img src="../assets/images/payment/1.svg" class="img-fluid" alt="">
                    </li>
                    <li>
                        <img src="../assets/images/payment/2.svg" class="img-fluid" alt="">
                    </li>
                    <li>
                        <img src="../assets/images/payment/3.svg" class="img-fluid" alt="">
                    </li>
                    <li>
                        <img src="../assets/images/payment/4.svg" class="img-fluid" alt="">
                    </li>
                    <li>
                        <img src="../assets/images/payment/5.svg" class="img-fluid" alt="">
                    </li>
                    <li>
                        <img src="../assets/images/payment/6.svg" class="img-fluid" alt="">
                    </li>
                    <li>
                        <img src="../assets/images/payment/7.svg" class="img-fluid" alt="">
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Cart Offcanvas Start -->
    <div class="offcanvas offcanvas-end cart-offcanvas" id="cartOffcanvas">
        <div class="offcanvas-header">
            <div class="title-offcanvas">
                <h4>Shopping Cart</h4>
                <button class="btn close-btn" data-bs-dismiss="offcanvas">
                    <i class="ri-close-fill"></i>
                </button>
            </div>
        </div>
        <div class="offcanvas-body">
            <div class="cart-product-box">
                <ul class="product-box-list">
                    <li class="vertical-product-box">
                        <a href="product-color.html" class="product-image">
                            <img src="../assets/images/product/1.png" class="img-fluid" alt="">
                        </a>
                        <div class="product-content">
                            <a href="product-color.html">
                                <h5 class="name title-color">Smart Watch Series X3</h5>
                            </a>
                            <ul class="rating">
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                            </ul>
                            <h5 class="price">$202.34 <del>$250.00</del></h5>
                            <div class="quantity-box qty-container">
                                <button class="btn qty-btn-minus">
                                    <i class=" ri-subtract-line"></i>
                                </button>
                                <button class="btn btn-trash">
                                    <i class=" ri-delete-bin-line"></i>
                                </button>
                                <input type="number" name="qty" disabled class="quantity form-control input-qty"
                                    value="1">
                                <button class="btn qty-btn-plus">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn close-button">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>

                    <li class="vertical-product-box">
                        <a href="product-color.html" class="product-image">
                            <img src="../assets/images/product/2.png" class="img-fluid" alt="">
                        </a>
                        <div class="product-content">
                            <a href="product-color.html">
                                <h5 class="name title-color">Slim 3 Intel Core i5</h5>
                            </a>
                            <ul class="rating">
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill"></i>
                                </li>
                            </ul>
                            <h5 class="price">$700.00 <del>$720.00</del></h5>
                            <div class="quantity-box qty-container">
                                <button class="btn qty-btn-minus">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <button class="btn btn-trash">
                                    <i class=" ri-delete-bin-line"></i>
                                </button>
                                <input type="number" name="qty" disabled class="quantity form-control input-qty"
                                    value="1">
                                <button class="btn qty-btn-plus">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn close-button">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>

                    <li class="vertical-product-box">
                        <a href="product-color.html" class="product-image">
                            <img src="../assets/images/product/3.png" class="img-fluid" alt="">
                        </a>
                        <div class="product-content">
                            <a href="product-color.html">
                                <h5 class="name title-color">Portable Laptop Table</h5>
                            </a>
                            <ul class="rating">
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill"></i>
                                </li>
                            </ul>
                            <h5 class="price">$398.00 <del>$450.00</del></h5>
                            <div class="quantity-box qty-container">
                                <button class="btn qty-btn-minus">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <button class="btn btn-trash">
                                    <i class=" ri-delete-bin-line"></i>
                                </button>
                                <input type="number" name="qty" disabled class="quantity form-control input-qty"
                                    value="1">
                                <button class="btn qty-btn-plus">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn close-button">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>

                    <li class="vertical-product-box">
                        <a href="product-color.html" class="product-image">
                            <img src="../assets/images/product/5.png" class="img-fluid" alt="">
                        </a>
                        <div class="product-content">
                            <a href="product-color.html">
                                <h5 class="name title-color">Kitchen Accessories</h5>
                            </a>
                            <ul class="rating">
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill"></i>
                                </li>
                            </ul>
                            <h5 class="price">$300.00 <del>$312.56</del></h5>
                            <div class="quantity-box qty-container">
                                <button class="btn qty-btn-minus">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <button class="btn btn-trash">
                                    <i class=" ri-delete-bin-line"></i>
                                </button>
                                <input type="number" name="qty" disabled class="quantity form-control input-qty"
                                    value="1">
                                <button class="btn qty-btn-plus">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn close-button">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>

                    <li class="vertical-product-box">
                        <a href="product-color.html" class="product-image">
                            <img src="../assets/images/product/6.png" class="img-fluid" alt="">
                        </a>
                        <div class="product-content">
                            <a href="product-color.html">
                                <h5 class="name title-color">Rockerz Bluetooth Headphone</h5>
                            </a>
                            <ul class="rating">
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                                <li>
                                    <i class="ri-star-fill fill"></i>
                                </li>
                            </ul>
                            <h5 class="price">$100.00 <del>$180.00</del></h5>
                            <div class="quantity-box qty-container">
                                <button class="btn qty-btn-minus">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <button class="btn btn-trash">
                                    <i class=" ri-delete-bin-line"></i>
                                </button>
                                <input type="number" name="qty" disabled class="quantity form-control input-qty"
                                    value="1">
                                <button class="btn qty-btn-plus">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn close-button">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>
                    <li class="empty-cart">
                        <svg>
                            <use xlink:href="../assets/images/inner-page/empty-cart.svg#emptyCart"></use>
                        </svg>
                        <h4>Your cart is empty.</h4>
                    </li>
                </ul>

                <div class="total-price-box">
                    <p class="free">Almost there, add $32.50 more to get <b>FREE SHIPPING!</b></p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 75%">
                            <i class="iconsax" data-icon-name="truck"></i>
                        </div>
                    </div>
                    <h4 class="sub-total">Subtotal <span id="total-price">$1700.00 USD</span></h4>
                    <p class="tax-text">Tax included <span>shipping</span>calculator at checkout.</p>
                    <div class="cart-btn-group">
                        <a href="checkout.html" class="btn check-out-button">Check Out</a>
                        <a href="cart.html" class="btn cart-button">View Cart</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Offcanvas End -->

    <!-- Wishlist Offcanvas Start -->
    <div class="offcanvas offcanvas-end wishlist-offcanvas cart-offcanvas" id="wishlistOffcanvas">
        <div class="offcanvas-header">
            <div class="title-offcanvas">
                <h4>Wishlist</h4>
                <button class="btn close-btn" data-bs-dismiss="offcanvas">
                    <i class="ri-close-fill"></i>
                </button>
            </div>
        </div>
        <div class="offcanvas-body">
            <div class="cart-product-box">
                <ul class="product-box-list">
                    <li>
                        <div class="vertical-product-box">
                            <a href="product-color.html" class="product-image">
                                <img src="../assets/images/product/1.png" class="img-fluid" alt="">
                            </a>
                            <div class="product-content">
                                <a href="product-color.html">
                                    <h5 class="name title-color">Smart Watch Series X3</h5>
                                </a>
                                <h5 class="price">$239.00 <del>$250.00</del></h5>
                                <button class="btn cart-btn" data-bs-target="#cartOffcanvas" data-bs-toggle="offcanvas">
                                    <span>Move to cart</span>
                                </button>
                            </div>
                            <button class="btn wishlist-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>

                    <li>
                        <div class="vertical-product-box">
                            <a href="product-color.html" class="product-image">
                                <img src="../assets/images/product/2.png" class="img-fluid" alt="">
                            </a>
                            <div class="product-content">
                                <a href="product-color.html">
                                    <h5 class="name title-color">Slim 3 Intel Core i5</h5>
                                </a>
                                <h5 class="price">$700.00 <del>$720.00</del></h5>
                                <button class="btn cart-btn" data-bs-target="#cartOffcanvas" data-bs-toggle="offcanvas">
                                    <span>Move to cart</span>
                                </button>
                            </div>
                            <button class="btn wishlist-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>

                    <li>
                        <div class="vertical-product-box">
                            <a href="product-color.html" class="product-image">
                                <img src="../assets/images/product/3.png" class="img-fluid" alt="">
                            </a>
                            <div class="product-content">
                                <a href="product-color.html">
                                    <h5 class="name title-color">Portable Laptop Table</h5>
                                </a>
                                <h5 class="price">$199.00 <del>$200.00</del></h5>
                                <button class="btn cart-btn" data-bs-target="#cartOffcanvas" data-bs-toggle="offcanvas">
                                    <span>Move to cart</span>
                                </button>
                            </div>
                            <button class="btn wishlist-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>

                    <li>
                        <div class="vertical-product-box">
                            <a href="product-color.html" class="product-image">
                                <img src="../assets/images/product/4.png" class="img-fluid" alt="">
                            </a>
                            <div class="product-content">
                                <a href="product-color.html">
                                    <h5 class="name title-color">Kitchen Accessories</h5>
                                </a>
                                <h5 class="price">$300.00 <del>$312.56</del></h5>
                                <button class="btn cart-btn" data-bs-target="#cartOffcanvas" data-bs-toggle="offcanvas">
                                    <span>Move to cart</span>
                                </button>
                            </div>
                            <button class="btn wishlist-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>

                    <li>
                        <div class="vertical-product-box">
                            <a href="product-color.html" class="product-image">
                                <img src="../assets/images/product/5.png" class="img-fluid" alt="">
                            </a>
                            <div class="product-content">
                                <a href="product-color.html">
                                    <h5 class="name title-color">Rockerz 558 Bluetooth</h5>
                                </a>
                                <h5 class="price">$86.00 <del>$96.00</del></h5>
                                <button class="btn cart-btn" data-bs-target="#cartOffcanvas" data-bs-toggle="offcanvas">
                                    <span>Move to cart</span>
                                </button>
                            </div>
                            <button class="btn wishlist-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>
                    <li>
                        <div class="vertical-product-box">
                            <a href="product-color.html" class="product-image">
                                <img src="../assets/images/product/26.png" class="img-fluid" alt="">
                            </a>
                            <div class="product-content">
                                <a href="product-color.html">
                                    <h5 class="name title-color">EvoFox Game Box 32 GB with Asphalt 8</h5>
                                </a>
                                <h5 class="price">$130.00 <del>$153.00</del></h5>
                                <button class="btn cart-btn" data-bs-target="#cartOffcanvas" data-bs-toggle="offcanvas">
                                    <span>Move to cart</span>
                                </button>
                            </div>
                            <button class="btn wishlist-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>
                    <li>
                        <div class="vertical-product-box">
                            <a href="product-color.html" class="product-image">
                                <img src="../assets/images/product/23.png" class="img-fluid" alt="">
                            </a>
                            <div class="product-content">
                                <a href="product-color.html">
                                    <h5 class="name title-color">BlackBerry Keyone BBB100-7 64gb unlocked gSM</h5>
                                </a>
                                <h5 class="price">$1920.36 <del>$2000.95</del></h5>
                                <button class="btn cart-btn" data-bs-target="#cartOffcanvas" data-bs-toggle="offcanvas">
                                    <span>Move to cart</span>
                                </button>
                            </div>
                            <button class="btn wishlist-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>
                </ul>

                <div class="total-price-box">
                    <p class="tax-text">Now that you have products in your wishlist, remember to put them in your cart
                        later.</p>
                    <div class="cart-btn-group">
                        <button onclick="location.href = 'wishlist.html';" class="btn check-out-button">View
                            Wishlist</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wishlist Offcanvas End -->

    <!-- Category Offcanvas Start -->
    <div class="category-fixed-box offcanvas offcanvas-start" id="categoryCanvas">
        <div class="category-header">
            <h5>Category</h5>
            <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas">
                <i class="ri-close-fill"></i>
            </button>
        </div>
        <div class="category-menu-list">
            <ul class="top-menu-list">
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <svg>
                            <use xlink:href="../assets/svg/category.svg#daily"></use>
                        </svg>
                        <h5>Daily Deals</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <svg>
                            <use xlink:href="../assets/svg/category.svg#top"></use>
                        </svg>
                        <h5>Top Promotions</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <svg>
                            <use xlink:href="../assets/svg/category.svg#trending"></use>
                        </svg>
                        <h5>Now Trending <span class="theme-bg-color2 text-white">Hot</span></h5>
                    </a>
                </li>
            </ul>
            <ul class="sub-menu-list">
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-macbook-line"></i>
                        <h5>Mobiles, Computers</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-plug-2-line"></i>
                        <h5>TV, Appliances, Electronics</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-men-line"></i>
                        <h5>Men's Fashion</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-women-line"></i>
                        <h5>Women's Fashion <span class="success-bg-color">New</span></h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-knife-line"></i>
                        <h5>Home, Kitchen, Pets</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-empathize-line"></i>
                        <h5>Beauty, Health, Grocery</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-boxing-line"></i>
                        <h5>Sports, Fitness, Bags, Luggage</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-open-arm-line"></i>
                        <h5>Toys, Baby Products, Kid's Fashion</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-car-line"></i>
                        <h5>Car, Motorbike, Industrial</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-book-open-line"></i>
                        <h5>Books</h5>
                    </a>
                </li>
                <li>
                    <a href="shop-left-sidebar.html" class="sub-category-box">
                        <i class="ri-gamepad-line"></i>
                        <h5>Game Consoles</h5>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Category Offcanvas End -->

    <!-- Authentication Modal Start -->
    <div class="modal authentication-modal fade" id="authenticationModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="ri-close-fill"></i>
                    </button>
                    <div class="authentication-box login-box">
                        <div class="auth-title">
                            <h4>Log in</h4>
                            <p>Welcome! To access your account, please enter your username and password.</p>
                        </div>
                        <form class="auth-form">
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Enter your password">
                            </div>
                            <div class="forgot-box">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Remember for 30
                                        days</label>
                                </div>
                                <a href="#!" class="forgot-pass">Forgot Password</a>
                            </div>
                            <a href="index.html" class="btn btn-bg-theme mt-3 w-100">Log In</a>
                            <div class="divider">
                                <span>OR</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.google.com/" class="btn w-100" target="_blank">
                                    <img src="../assets/images/inner-page/google.png" class="img-fluid" alt="">
                                    <span>Sign in with google</span>
                                </a>
                                <a href="https://www.facebook.com/" class="btn w-100" target="_blank">
                                    <img src="../assets/images/inner-page/facebook.png" class="img-fluid" alt="">
                                    <span>Sign in with facebook</span>
                                </a>
                            </div>
                            <h5 class="sign-up-next">Don't have an account? <button class="next-button btn">Sign up
                                    now</button></h5>
                        </form>
                    </div>

                    <div class="authentication-box signup-box">
                        <div class="auth-title">
                            <h4>Sign Up</h4>
                            <p>Welcome! To access your account, please enter your username and password.</p>
                        </div>
                        <form class="auth-form">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Enter full name">
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" placeholder="Create your password">
                            </div>
                            <div class="forgot-box">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck2">
                                    <label class="form-check-label" for="exampleCheck2">I agree with all text in the
                                        agreement.</label>
                                </div>
                            </div>
                            <a href="index.html" class="btn btn-bg-theme mt-3 w-100">Sing up</a>
                            <div class="divider">
                                <span>OR</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.google.com/" class="btn w-100" target="_blank">
                                    <img src="../assets/images/inner-page/google.png" class="img-fluid" alt="">
                                    <span>Sign up with google</span>
                                </a>
                                <a href="https://www.facebook.com/" class="btn w-100" target="_blank">
                                    <img src="../assets/images/inner-page/facebook.png" class="img-fluid" alt="">
                                    <span>Sign up with facebook</span>
                                </a>
                            </div>
                            <h5 class="sign-up-next">I am already member. <button class="btn next-button2">Sign
                                    in</button>
                            </h5>
                        </form>
                    </div>

                    <div class="authentication-box forgot-password-box">
                        <div class="auth-title">
                            <h4>Reset Password</h4>
                            <p>Have you forgotten your password? Kindly provide your email address. An email with a link
                                to establish a new password will be sent to you.</p>
                        </div>
                        <form class="auth-form">
                            <input type="email" class="form-control" placeholder="Enter Your Email Address">
                            <div class="d-flex flex-wrap-nowrap mt-3 gap-3">
                                <button class="btn btn-bg-theme w-100">Submit</button>
                                <a href="#!" class="btn btn-bg-theme w-100 cancel-btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Authentication Modal End -->

        <!-- Quick View Modal Start -->
        <div class="modal fade quick-view-modal theme-modal" id="quickViewModal">
            <div class="modal-dialog modal-custom-size modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                            <i class="ri-close-line"></i>
                        </button>
                        <div class="row g-sm-4 g-3">
                            <div class="col-md-6">
                                <div class="left-box-contain">
                                    <div class="swiper quick-main-slider quick-slider-product-box">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <div class="view-image">
                                                    <img src="../assets/images/product/15.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="view-image">
                                                    <img src="../assets/images/product/22.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="view-image">
                                                    <img src="../assets/images/product/25.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="view-image">
                                                    <img src="../assets/images/product/28.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="view-image">
                                                    <img src="../assets/images/product/98.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-button-next">
                                            <i class="ri-arrow-right-s-line"></i>
                                        </div>
                                        <div class="swiper-button-prev">
                                            <i class="ri-arrow-left-s-line"></i>
                                        </div>
                                    </div>
        
                                    <div class="swiper quick-thumbnail-product-box quick-thumbnail-slider">
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <div class="image-box">
                                                    <img src="../assets/images/product/15.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="image-box">
                                                    <img src="../assets/images/product/22.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="image-box">
                                                    <img src="../assets/images/product/25.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="image-box">
                                                    <img src="../assets/images/product/28.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="image-box">
                                                    <img src="../assets/images/product/98.png" class="img-fluid" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-md-6">
                                <div class="product-right-box">
                                    <div class="right-box-contain">
                                        <a href="seller-details-2.html" class="offer-top">Visit SmartBuy Store</a>
                                        <h2 class="name">Next-level performance with stunning design & cutting-edge features
                                        </h2>
                                        <div class="price-rating">
                                            <ul class="rating-review-sold-box">
                                                <li>
                                                    <h3><i class="ri-star-fill"></i> 4.8 Ratings</h3>
                                                </li>
                                                <li></li>
                                                <li>
                                                    <h3>4.5M+ Reviews</h3>
                                                </li>
                                                <li></li>
                                                <li>
                                                    <h3>3.5M+ Sold</h3>
                                                </li>
                                            </ul>
                                        </div>
        
                                        <div class="product-contain">
                                            <p>Experience brilliance with the iPhone 14 Pro Max, designed with a sleek
                                                finish and a stunning Super Retina XDR display. Powered by the A16 Bionic
                                                chip, it delivers lightning-fast performance and efficiency. The advanced
                                                triple-camera system captures professional-quality photos and videos in all
                                                lighting conditions.</p>
                                        </div>
        
                                        <div class="product-package product-spacing">
                                            <div class="product-title">
                                                <h4>Choose Color :</h4>
                                            </div>
                                            <form class="select-package color-product">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="6"
                                                        checked style="background-color: #d5dde0;">
                                                    <label class="form-check-label bg-transparent" for="6"></label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="7"
                                                        style="background-color: #edd4d7;">
                                                    <label class="form-check-label bg-transparent" for="7"></label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="8"
                                                        style="background-color: #ece7c9;">
                                                    <label class="form-check-label bg-transparent" for="8"></label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="9"
                                                        style="background-color: #d4ddce;">
                                                    <label class="form-check-label bg-transparent" for="9"></label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="10"
                                                        style="background-color: #4d5154;">
                                                    <label class="form-check-label bg-transparent" for="10"></label>
                                                </div>
                                            </form>
                                        </div>
        
                                        <div class="hurry-up-box">
                                            <h5>There are just <span class="theme-color">5</span> left in stock, so please
                                                act immediately.</h5>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                    style="width: 50%"></div>
                                            </div>
                                        </div>
        
                                        <div class="about-item-box product-spacing border-top-space">
                                            <div class="product-title">
                                                <h4>About Item :</h4>
                                            </div>
        
                                            <ul class="about-item-list">
                                                <li>Brand : <span>Apple</span></li>
                                                <li>Category : <span>Smartphone</span></li>
                                                <li>Condition : <span>Brand New</span></li>
                                                <li>Color : <span>Deep Purple</span></li>
                                                <li>Pattern : <span>Glossy finish</span></li>
                                                <li>Style : <span>Premium</span></li>
                                            </ul>
                                        </div>
        
                                        <div class="qty-stock-box">
                                            <div class="qty-box h-100 qty-container quantity-box-2">
                                                <button class="btn qty-btn qty-btn-minus">
                                                    <i class="ri-subtract-line"></i>
                                                </button>
                                                <input type="number" readonly="" name="qty" value="0"
                                                    class="qty-input form-control input-qty">
                                                <button class="btn qty-btn qty-btn-plus">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                            </div>
        
                                            <button onclick="location.href = 'cart.html';"
                                                class="btn buy-btn theme-border fw-500">
                                                <i class="ri-shopping-bag-line"></i> Add to bag</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Quick View Modal End -->

    <!-- Add Address Modal Start -->
    <div class="modal fade add-address-modal" id="addAddress">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Modal title</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Address Modal End -->

    <!-- Tap To Top Button Start -->
    <div class="tap-top-button">
        <button class="btn">
            <i class="iconsax" data-icon-name="arrow-up"></i>
        </button>
    </div>
    <!-- Tap To Top Button End -->

    <!-- Recent Product View Box Start -->
    <div class="recently-product-box">
        <button class="btn recent-close">
            <i class="iconsax" data-icon-name="add"></i>
        </button>
        <a href="product-color.html">
            <img src="../assets/images/product/1.png" class="img-fluid" alt="Product Image">
        </a>
        <div class="recent-content">
            <a href="product-color.html">Smart Watch Series X3</a>
            <h3 class="price h5">$239.00 <del>$250.00</del></h3>
            <h4 class="timer h5">1 minutes ago</h4>
        </div>
    </div>
    <!-- Recent Product View Box End -->

    <!-- Bg Overlay Start -->
    <div id="overlay" class="bg-overlay"></div>
    <!-- Bg Overlay End -->

   