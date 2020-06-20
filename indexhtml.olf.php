<?php 
    include_once 'include/functions.php';
      	$functions = new Functions();
      	$sliderDetails = $functions->getSliderbBanner();
      	$homePageCms = $functions->getHomePageCms();
    
    ?>
<!DOCTYPE>
<html>
    <head>
        <title>Arvind Sanitary</title>
        <?php include("include/header-link.php");?>
    </head>
    <body class="home">
        <!--Top start menu head-->       
        <?php include("include/header.php");?>
        <section class="banners">
            <div class="banner-slider">
                <div class="slide">
                   <a href="listing.php"> <img src="images/design-1.png" alt=""></a>
                </div>
                <div class="slide">
                   <a href="listing.php"> <img src="images/design-1.png" alt=""></a>
                </div>
            </div>
        </section>
        <section class="wel-arvind">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="wel-text">
                            <h2>Welcome To arvind sanitary</h2>
                            <p>
                                Established in 2014, Arvind Sanitary has its base in Palghar, Maharashtra and is a leader in the field of manufacturing of different designer basins, wash basins and many more attractive products. <br>
                                The USP of our products lies in the fact that they are procured from the most reliable sources and utmost care is taken to ensure that they are of the best and the most highest quality. We dedicate all our resources towards ensuring that our products meet the quality standards.Our constant dedication to quality has enabled us to ensure that we make a name for ourselves in this domain.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="img-box">
                            <img src="images/welimg.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="trending">
            <div class="container">
                <div class="title-main">
                    <h2>TRENDING PRODUCTS</h2>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#home" role="tab" aria-controls="home">Latest Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-controls="profile">Featured Product</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="home" role="tabpanel">
                        <div class="trend-slider">
                          
                       
                        <div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
						
							<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
							<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
							<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="tab-pane" id="profile" role="tabpanel">
                        <div class="trend-slider1">
						<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
							<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
							<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
							<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
							<div class="slide">
								<div class="slidebox">
									<img src="images/latest1.png" alt="">
									<div class="prize-box">
										<h4>Wash Basins</h4>
										<h5><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<span>3,500</span></h5>
									</div>
									<div class="onhover">
										<a href="#"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-external-link" aria-hidden="true"></i></a>
										<a href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i></a>
										<a href="#">Buy Now</a>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="testii">
            <div class="container">
                <div class="title-main">
                    <h2>testimonials</h2>
                </div>
                <div class="test-slider">
                    <div class="slides">
                        <div class="test-text">
                            <div class="quotess">
                                <img src="images/quotess.png" alt="">
                            </div>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500
                            </p>
                            <h3>Shweta Rane</h3>
                        </div>
                        <div class="testimgg">
                            <div class="imgg">
                                <img src="images/testimg.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="slides reverse">
                        <div class="test-text">
                            <div class="quotess">
                                <img src="images/quotess.png" alt="">
                            </div>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500
                            </p>
                            <h3>Shweta Rane</h3>
                        </div>
                        <div class="testimgg">
                            <div class="imgg">
                                <img src="images/testimg.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="slides">
                        <div class="test-text">
                            <div class="quotess">
                                <img src="images/quotess.png" alt="">
                            </div>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500
                            </p>
                            <h3>Shweta Rane</h3>
                        </div>
                        <div class="testimgg">
                            <div class="imgg">
                                <img src="images/testimg.png" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="slides reverse">
                        <div class="test-text">
                            <div class="quotess">
                                <img src="images/quotess.png" alt="">
                            </div>
                            <p>
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500
                            </p>
                            <h3>Shweta Rane</h3>
                        </div>
                        <div class="testimgg">
                            <div class="imgg">
                                <img src="images/testimg.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="dell">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="dellll">
                            <div class="imggg">
                                <img src="images/free.png" alt="">
                            </div>
                            <h3>Free Delivery</h3>
                            <p>Lorem Ipsum is simply dummy text printing</p>
                        </div>
                    </div>
                    <div class="col-md-4 borderr">
                        <div class="dellll">
                            <div class="imggg">
                                <img src="images/free1.png" alt="">
                            </div>
                            <h3>24/7 Customer Support</h3>
                            <p>Lorem Ipsum is simply dummy text printing</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dellll">
                            <div class="imggg">
                                <img src="images/free2.png" alt="">
                            </div>
                            <h3>Return of Goods</h3>
                            <p>Lorem Ipsum is simply dummy text printing</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Main End Code Here-->
        <!--footer start menu head-->
        <?php include("include/footer.php");?> 
        <!--footer end menu head-->
        <?php include("include/footer-link.php");?>
        <script></script>
    </body>
</html>