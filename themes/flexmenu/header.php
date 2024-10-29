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
<html>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Advanced Online Ordering and Delivery Platform">
<meta name="author" content="BuyNowDepot">
<link rel="icon" type="image/png" href="<?php echo $theme_url;?>/img/fav.png">
<title><?php echo get_bloginfo("name"); ?> - <?php echo get_bloginfo("description"); ?></title>
<?php wp_head();?>
</head>
<body <?php body_class('default-theme');?>>
<div class="section-header">
        <div class="header-main shadow-sm bg-white">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4">
                    	<div class="d-flex">
                    		<?php echo buynowdepot_custom_logo_link(); ?>
                        	<a href="<?php echo esc_url( buynowdepot_get_page_url( 'bnd-menuitems' ) ); ?>" rel="home">
                        		<div class="brand-header">
                        			<?php echo get_bloginfo("name"); ?>
                        		</div>
                        		<div class="brand-description">
                        			<?php echo get_bloginfo("description"); ?>
                        		</div>
                        	</a>
                    	</div>
                    </div>
                    <!-- col.// -->
                    <div class="col-md-8">
                        <div class="d-flex align-items-right justify-content-end">
                            <!-- signin -->
                            <?php if (!is_user_logged_in()) :?>
                            <a href="<?php echo buynowdepot_get_page_url('bnd-login'); ?>" class="mr-4 text-dark m-none">
                                <div class="icon d-flex align-items-center">
                                    <i class="fas fa-user h6 mr-2 mb-0"></i> <span>Sign in</span>
                                </div>
                            </a>
                            <?php endif ?>
                            <!-- my account -->
                            <?php if (is_user_logged_in()) :?>
                            <a href="<?php echo buynowdepot_get_page_url('bnd-profile'); ?>" class="text-dark mr-4 m-none">
                            	<div class="icon d-flex align-items-center">
                                    <i class="fas fa-user h6 mr-2 mb-0"></i> <span>Welcome <?php echo wp_get_current_user()->display_name;?> </span>
                                </div>
                            </a>
                            <?php endif ?>
                            <!-- signin -->
                            <a href="<?php echo buynowdepot_get_page_url('bnd-cart-display'); ?>" class="mr-4 text-dark">
                                <div class="icon d-flex align-items-center">
                                    <i class="fas fa-shopping-cart h6 mr-2 mb-0"></i> <span>Cart</span>
                                </div>
                                <span id="cart-item-count" class="badge badge-success rounded cart-count-badge"></span>
                            </a>
                            <div class=" mr-4 text-dark">
                            	<div class="icon d-flex align-items-center">
                                	<a class="toggle" href="#">
                                		<span></span>
                                	</a>
                                </div>
                            </div>        
                        </div>
                        <!-- widgets-wrap.// -->
                    </div>
                    <!-- col.// -->
                </div>
                <!-- row.// -->
            </div>
            <!-- container.// -->
        </div>
        <!-- header-main .// -->
    </div>
    <div class="container">
    	<div class="alert alert-primary" role="alert" id="global_message" style="display:none">  
    </div>
    </div>