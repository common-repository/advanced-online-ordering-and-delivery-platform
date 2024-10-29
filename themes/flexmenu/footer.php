<?php 
/**
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 *
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author BuyNowDepot
 */
$theme_url =  buynowdepot_get_theme_url();
?>
<footer class="footer-section border-top bg-dark">
    <div class="container">
        <div class="py-5">
            <div class="row">
                <div class="col-sm-3 footer-about">
                    <article class="d-flex pb-3">
                        <div class="custom-logo-footer"><?php echo the_custom_logo(); ?></div>
                        <div>
                            <h6 class="title text-white">About Us</h6>
                            <p class="text-white" style="margin-left:0.5rem"><?php echo get_bloginfo("description"); ?></p>
                            <div class="d-flex align-items-center" style="margin-left:0.5rem">
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Facebook" target="_blank" href="#"><i class="fab fa-facebook-square"></i></a>
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Instagram" target="_blank" href="#"><i class="fab fa-instagram-square"></i></a>
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Youtube" target="_blank" href="#"><i class="fab fa-youtube-square"></i></a>
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Twitter" target="_blank" href="#"><i class="fab fa-twitter-square"></i></a>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-sm-3 text-white">
                    <h6 class="title">Services</h6>
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-faq'); ?>" class="">Help & Support</a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-contact-us'); ?>" class="">Contact Us</a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-terms'); ?>" class="">Terms of use</a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-privacy'); ?>" class="">Privacy policy</a></li>
                    </ul>
                </div>
                <div class="col-sm-3  text-white">
                    <h6 class="title">For users</h6>
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-login'); ?>" class=""> User Login </a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-signup'); ?>" class=""> User register </a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-forgot-password'); ?>" class=""> Forgot Password </a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-profile'); ?>" class=""> Account Setting </a></li>
                    </ul>
                </div>
                <div class="col-sm-3  text-white">
                    <h6 class="title">More Pages</h6>
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-order-status'); ?>" class=""> Order Status </a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>" class=""> Restaurant Menu </a></li>
                        <li> <a href="<?php echo buynowdepot_get_page_url('bnd-terms'); ?>" class=""> More details </a></li>
                    </ul>
                </div>
            </div>
            <!-- row.// -->
        </div>
    </div>
    <!-- //container -->
    <div class="footer-copyright border-top py-3 bg-light">
        <div class="container d-flex align-items-center">
            <p class="mb-0">&copy; 2021 <?php echo get_bloginfo("name");?>. All rights reserved</p>
            <div class=" mb-0 ml-auto d-flex align-items-center">
            	<a href="//www.clover.com/appmarket/apps/GZT9M27Z4NBQ8" target="_blank" rel="noopener noreferrer"><img src="//www.clover.com/assets/images/clover-app-market-button.svg" alt="Install From Clover App Market"/></a>
                <a href="#" class="d-block"><img alt="#" src="<?php echo $theme_url?>/img/appstore.png" class="image-footer"></a>
                <a href="#" class="d-block ml-3"><img alt="#" src="<?php echo $theme_url?>/img/playmarket.png"class="image-footer"></a>
            </div>
        </div>
    </div>
</footer>
<nav id="main-nav">
    <ul class="second-nav" style="font-size:1.2rem">
        <li><a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>"><i class="fas fa-home mr-2 text-primary link-icon-small"></i> Homepage</a></li>
        <li><a href="<?php echo buynowdepot_get_page_url('bnd-my-order'); ?>"><i class="fas fa-file-invoice link-icon-small mr-2 text-primary"></i> My Orders</a></li>
        <li><a href="<?php echo buynowdepot_get_page_url('bnd-checkout'); ?>"><i class="fas fa-list link-icon-small mr-2 text-primary"></i> Checkout</a></li>
        <li><a href="<?php echo buynowdepot_get_page_url('bnd-trackorder'); ?>"><i class="fas fa-map-marker-alt link-icon-small mr-2 text-primary"></i> Track Order</a></li>
        <?php if (is_user_logged_in()) {?>
        <li>
            <a href="<?php echo buynowdepot_get_page_url('bnd-profile'); ?>"><i class="fas fa-user link-icon-small mr-2 text-primary"></i> My Profile</a>
            <a href="<?php echo wp_logout_url(buynowdepot_get_page_url('bnd-logout')); ?>"><i class="fas fa-sign-out-alt link-icon-small mr-2 text-primary"></i> Logout</a>
        </li>
        <?php } else { ?>
        <li>
            <a href="#"><i class="fas fa-lock  link-icon-small mr-2 text-primary"></i> Authentication</a>
            <ul>
                <li><a href="<?php echo buynowdepot_get_page_url('bnd-login'); ?>">Login</a></li>
                <li><a href="<?php echo buynowdepot_get_page_url('bnd-signup'); ?>">Register</a></li>
                <li><a href="<?php echo buynowdepot_get_page_url('bnd-forgot-password'); ?>">Forgot Password</a></li>
            </ul>
        </li>
        <?php } ?>
    </ul>
    <ul class="bottom-nav">
        <li>
            <a href="<?php echo buynowdepot_get_page_url('bnd-menuitems'); ?>">
                <p class="h5 m-0"><i class="fas fa-home"></i></p>
                Home
            </a>
        </li>
        <li>
            <a href="<?php echo buynowdepot_get_page_url('bnd-faq'); ?>">
                <p class="h5 m-0"><i class="fas fa-question-circle"></i></p>
                FAQ
            </a>
        </li>
        <li>
            <a href="<?php echo buynowdepot_get_page_url('bnd-contact-us'); ?>">
                <p class="h5 m-0"><i class="fas fa-phone"></i></p>
                Help
            </a>
        </li>
    </ul>
</nav>
<?php wp_footer(); ?>
</body>
</html>