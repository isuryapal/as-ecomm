	
	<div class="newsletter">
			<div class="container">
				<div class="row">
					<div class="col-md-7 col-sm-6 match">
						<div class="newww">
							<h1>newsletter</h1>
							<p>Receive our latest updates about our products and promotions. </p>
						</div>
					</div>
					<div class="col-md-5 col-sm-6 match">
						<form action="" method="POST" id="news_letterSub">
							<div class="inpss">
									<input type="email" name="email" placeholder="Enter Your Email">
									<button name="addSubscription" type="submit">Subscribe</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<footer id="footer">
			<div class="container">
					<div class="footer-flex">
						<div class="flex-box match">
							<h4>Quick Links</h4>
							<ul>
								<li><a href="index.php">Home</a></li>
								<li><a href="about.php">About Us</a></li>
								<?php
								$rowForCat = $functions->fetch($functions->query("SELECT * FROM ".PREFIX."category_master"));
								?>
								<li><a href="<?php echo $rowForCat['permalink']; ?>">Our Products</a></li>
								<li><a href="contact.php">Contact Us</a></li>
							</ul>
						</div>
				
						<div class="flex-box match">
							<h4>Our Products</h4>
							<ul class="colmcnt">
								<?php
								$catSql = "SELECT * FROM ".PREFIX."category_master WHERE active='Yes' order by category_name LIMIT 8";
								$catRes = $functions->query($catSql);
								if($functions->num_rows($catRes)){
									while($catRow = $functions->fetch($catRes)){
								?>
								<li><a href="<?php echo $catRow['permalink']; ?>" class="dropdown-toggle" ><?php echo $catRow['category_name']; ?></a></li>
								
							<?php 	} 
								} ?>
							</ul>
						</div>
						<div class="flex-box match">
							<h4>Useful Links</h4>
							<ul>
								<li><a href="<?php echo BASE_URL; ?>/terms.php">Terms and Conditions</a></li>
								<li><a href="<?php echo BASE_URL; ?>/privacypolicy.php">Privacy Policy</a></li>
								<li><a href="<?php echo BASE_URL; ?>/disclaimer.php">Disclaimer</a></li>
								<li><a href="<?php echo BASE_URL; ?>/refundpolicy.php">Return and Refund Policy</a></li>
								<li><a href="<?php echo BASE_URL; ?>/copyright.php">Copyright</a></li>
								<li><a href="<?php echo BASE_URL; ?>/faq.php">FAQ's</a></li>
							</ul>
						</div>
						<div class="flex-box match">
							<h4>Get in Touch</h4>
								<ul class="address">
									<li><i class="fa fa-user" aria-hidden="true"></i><a>Arvind Sanitary, <br> Laxmi Ranaut (Director)</a></li>
									<li><i class="fa fa-map-marker" aria-hidden="true"></i><a>AK Compound, Sai Wardha Estate,<br> Nalasopara - E, Thane - 401208, MH, India</a></li>
									<li><i class="fa fa-phone" aria-hidden="true"></i><a href="tel:+918048006399">+91 8048006399</a></li>
									<li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:info@sanitaryarvind.com">info@sanitaryarvind.com</li>
								</ul>
						</div>
				</div> <hr class="footerrr">
				<div class="copyyri">
					<p>© Copyright 2020 Arvind Sanitary. All Rights Reserved. | Design & Developed by - Suryapal Rao</p>
					<div class="soccc">
						<a href="#"><img src="<?php echo BASE_URL; ?>/images/fb.png" alt=""></a>
						<a href="#"><img src="<?php echo BASE_URL; ?>/images/tw.png" alt=""></a>
						<a href="#"><img src="<?php echo BASE_URL; ?>/images/gg.png" alt=""></a>
						<a href="#"><img src="<?php echo BASE_URL; ?>/images/yt.png" alt=""></a>
					</div>
				</div>
			
			</div>
		</footer>
</div>