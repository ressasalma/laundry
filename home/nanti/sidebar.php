<?php
include 'koneksi.php';
session_start();
if (isset($_SESSION["username"])) {
    ?>
    <div class="header2-area">
        <div class="header-top-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="header-top-left">
                            <ul>
                                <li><i class="fa fa-phone" aria-hidden="true"></i><a href="Tel:+6282213831085">
                                        +62 822 1383 1085 </a></li>
                                <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="#">ressasalma12@gmail.com</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="header-top-right jtv-user-info">
                            <ul>
                                <li class="dropdown"> <a class="current-open" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" href="#"><span><i class="fa fa-user" aria-hidden="true"></i>
                                            My Account </span> <i class="fa fa-angle-down"></i></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="login.php"><i class="fa fa-user" aria-hidden="true"></i> Login</a></li>
                                        <li><a href="logout.php"><i class="fa fa-user" aria-hidden="true"></i> Logout</a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom-area" id="sticker">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="logo-area">
                            <a href="index.php"><img class="img-responsive" width="100px" src="img/dicuciin.png"
                                    alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="main-menu-area">
                            <nav>
                                <ul>
                                    <li><a href="index.php">Home</a>
                                    </li>
                                    <!-- <li><a href="#">All Pages <i class="fa fa-angle-down"></i></a>
    <ul>
        <li><a href="index.php">Home</a></li>

        <li><a href="our-recipes.html">Our Recipes</a></li>
        <li><a href="food-menu4.html">Food Menu</a></li>
        <li><a href="food_2.html">Food Menu</a></li>
        <li><a href="food-menu-details.html">Food Menu Detail</a></li>
        <li><a href="reservation.html">Reservation</a></li>
        <li><a href="cart.html">Cart</a></li>
        <li><a href="checkout.html">Checkout</a></li>
        <li><a href="accountpage.html">Account Page</a></li>

    </ul>
</li> -->
                                    <li><a href="layanan-member.php">Paket</a></li>
                                    <li><a href="outlet.php">Outlet</a></li>
                                    <li><a href="lacak-member.php">Lacak Laundry</a></li>
                                    <li><a href="about.php">Tentang Kami</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>

    <div class="header2-area">
        <div class="header-top-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="header-top-left">
                            <ul>
                                <li><i class="fa fa-phone" aria-hidden="true"></i><a href="Tel:+6282213831085">
                                        +62 822 1383 1085 </a></li>
                                <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="#">ressasalma12@gmail.com</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="header-top-right jtv-user-info">
                            <ul>
                                <li><a href="login.php"><i class="fa fa-user" aria-hidden="true"></i> Login</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom-area" id="sticker">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <div class="logo-area">
                            <a href="index.php"><img class="img-responsive" width="100px" src="img/dicuciin.png"
                                    alt="logo"></a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="main-menu-area">
                            <nav>
                                <ul>
                                    <li><a href="index.php">Home</a>
                                    </li>
                                    <!-- <li><a href="#">All Pages <i class="fa fa-angle-down"></i></a>
    <ul>
        <li><a href="index.php">Home</a></li>

        <li><a href="our-recipes.html">Our Recipes</a></li>
        <li><a href="food-menu4.html">Food Menu</a></li>
        <li><a href="food_2.html">Food Menu</a></li>
        <li><a href="food-menu-details.html">Food Menu Detail</a></li>
        <li><a href="reservation.html">Reservation</a></li>
        <li><a href="cart.html">Cart</a></li>
        <li><a href="checkout.html">Checkout</a></li>
        <li><a href="accountpage.html">Account Page</a></li>

    </ul>
</li> -->
                                    <li><a href="layanan.php">Paket</a></li>
                                    <li><a href="outlet.php">Outlet</a></li>
                                    <li><a href="lacak.php">Lacak Laundry</a></li>
                                    <li><a href="about.php">Tentang Kami</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>