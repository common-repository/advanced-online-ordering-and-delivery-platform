<?php
/**
 * © Copyright 2021  Website Experts Inc./ DBA Buy Now Depot®
 * Trademark Registered Number:  5,442,029
 *
 * @since 1.0.0
 * @package Bnd_Flex_Order_Delivery
 * @subpackage Bnd_Flex_Order_Delivery/includes
 * @author <?php echo get_bloginfo("name");?>
 */
$theme_url = buynowdepot_get_theme_url();
$model = Bnd_Flex_Order_Delivery_Container::instance()->getDb();
$result = $model->getCurrentMerchant();
?>
<div class="bnd-profile">
	<div class="d-none">
		<div class="bg-primary border-bottom p-3 d-flex align-items-center">
			<a class="toggle togglew toggle-2" href="#"><span></span></a>
			<h4 class="font-weight-bold m-0 text-white">Profile</h4>
		</div>
	</div>
	<!-- profile -->
	<div class="container position-relative">
		<div class="py-5 bnd-profile row">
			<div class="col-md-4 mb-3">
				<?php include_once 'profile-left.php';?>
			</div>
			<div class="col-md-8 mb-3">
				<div class="rounded shadow-sm">
					<div class="bnd-privacy bg-white rounded shadow-sm p-4">
						<div id="intro" class="mb-4">
							<!-- Title -->
							<div class="mb-3">
								<h2 class="h5">Welcome to <?php echo get_bloginfo("name");?></h2>
							</div>
							<!-- End Title -->
							<p>Thanks for using our products and services ("Services"). The
								Services are provided by <b><?php echo get_bloginfo("name");?></b>. ("<b><?php echo get_bloginfo("name");?></b>"), located at
								<?php echo $result->address1." ".$result->city." ".$result->state." ".$result->zip." ".$result->country ?>.</p>
							<p>By using our Services, you are agreeing to these terms. Please
								read them carefully.</p>
						</div>
						<div id="services" class="mb-4">
							<!-- Title -->
							<div class="mb-3">
								<h3 class="h5">1. Using our services</h3>
							</div>
							<!-- End Title -->
							<p>You must follow any policies made available to you within the
								Services.</p>
							<p>Don't misuse our Services. For example, don't interfere with
								our Services or try to access them using a method other than the
								interface and the instructions that we provide. You may use our
								Services only as permitted by law, including applicable export
								and re-export control laws and regulations. We may suspend or
								stop providing our Services to you if you do not comply with our
								terms or policies or if we are investigating suspected
								misconduct.</p>
							<!-- Title -->
							<div id="personal-data" class="mb-3 active">
								<h4 class="h6">A. Personal Data that we collect about you.</h4>
							</div>
							<!-- End Title -->
							<p>Personal Data is any information that relates to an identified
								or identifiable individual. The Personal Data that you provide
								directly to us through our Sites will be apparent from the
								context in which you provide the data. In particular:</p>
							<ul class="text-secondary">
								<li class="pb-2">When you register for a <b><?php echo get_bloginfo("name");?></b> account we
									collect your first name, last name, email address, mobile number and account log-in
									credentials.</li>
								<li class="pb-2">When you fill-in our online form to contact our
									sales team, we collect your full name, email, and
									anything else you tell us about your project, needs and
									timeline.</li>
							</ul>
							<p>When you respond to <?php echo get_bloginfo("name");?> emails or surveys we collect
								your email address, name and any other information you choose to
								include in the body of your email or responses. If you contact
								us by phone, we will collect the phone number you use to call
								<?php echo get_bloginfo("name");?>. If you contact us by phone as a <?php echo get_bloginfo("name");?> User,
								we may collect additional information in order to verify your
								identity.</p>
							<!-- Title -->
							<div id="information" class="mb-3 active">
								<h4 class="h6">B. Information that we collect automatically on
									our Sites.</h4>
							</div>
							<!-- End Title -->
							<p>We also may collect information about your online activities
								on websites and connected devices over time and across
								third-party websites, devices, apps and other online features
								and services. We use Google Analytics on our Sites to help us
								analyze Your use of our Sites and diagnose technical issues.</p>
							<p>To learn more about the cookies that may be served through our
								Sites and how You can control our use of cookies and third-party
								analytics, please see our Cookie Policy.</p>
						</div>
						<div id="privacy" class="mb-4">
							<!-- Title -->
							<div class="mb-3">
								<h3 class="h5">2. Privacy and copyright protection</h3>
							</div>
							<!-- End Title -->
							<p><?php echo get_bloginfo("name");?>'s privacy policies explain how we treat your
								personal data and protect your privacy when you use our
								Services. By using our Services, you agree that <?php echo get_bloginfo("name");?> can use such data in
								accordance with our privacy policies.</p>
							<p>We respond to notices of alleged copyright infringement and
								terminate accounts of repeat infringers according to the process
								set out in the U.S. Digital Millennium Copyright Act.</p>
							<p>
								We provide information to help copyright holders manage their
								intellectual property online. If you think somebody is violating
								your copyrights and want to notify us please send a mail to sales@buynowdepot.com.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>