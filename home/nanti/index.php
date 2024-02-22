<!doctype html>
<html class="no-js" lang="">

<head>
    <?php include 'head.php'; ?>
</head>

<body>


    <!-- Add your site or application content here -->
    <div class="wrapper">
        <!-- Header Area Start Here -->
        <header>
            <?php include 'sidebar.php'; ?>
        </header>
        <!-- Header Area End Here -->
        <!-- Slider Area Start Here -->
        <div class="slider1-area">
            <div class="bend niceties preview-1">
                <div id="ensign-nivoslider-3" class="slides">
                    <img src="img/1708574899394.jpg" alt="slider" title="#slider-direction-1" />
                    <img src="img/1708574899411.jpg" alt="slider" title="#slider-direction-2" />
                    <img src="img/1708574899479.jpg" alt="slider" title="#slider-direction-3" />
                </div>
                <div id="slider-direction-1" class="t-cn slider-direction">
                    <div class="slider-content s-tb slide-1">
                        <div class="title-container s-tb-c">
                            <h1 class="title1">Dicuciin</h1>
                            <p>Solusi mencuci wangi, bersih dan rapi.</p>
                        </div>
                    </div>
                </div>
                <div id="slider-direction-2" class="t-cn slider-direction">
                    <div class="slider-content s-tb slide-2">
                        <div class="title-container s-tb-c">
                            <h1 class="title1">Dicuciin</h1>
                            <p>Tersebar di beberapa wilayah Bahar Utara.</p>
                        </div>
                    </div>
                </div>
                <div id="slider-direction-3" class="t-cn slider-direction">
                    <div class="slider-content s-tb slide-3">
                        <div class="title-container s-tb-c">
                            <h1 class="title1">Dicuciin</h1>
                            <p>Teman terbaik untuk tugas terpenting dalam keseharianmu.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slider Area End Here -->
        <!-- About 1 Area Start Here -->
        <div class="about1-area section-space">
            <img class="img-responsive section-back" src="img/section-back-1.png" alt="Sestion Back">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 wow fadeInRight">
                        <div class="about1-area-top">
                            <h2>Dicuciin</h2>
                        </div>
                        <h3 class="title-bar-big-left">kenapa Dicuciin?</h3>
                        <p>Dicuciin merupakan jasa laundry yang memiliki banyak outlet yang tersebar di bahar utara. Di
                            sini kami bukan hanya sekedar tempat mencuci, tapi juga merupakan kemitraan yang dapat
                            diandalkan untuk membantu mengelola aspek penting dari rutinitas harian Anda</p>
                        <a href="about.php" class="ghost-color-btn">Read More<i class="fa fa-chevron-right"
                                aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Area Start Here -->
        <footer>
            <?php include 'footer.php'; ?>
        </footer>
        <!-- Footer Area End Here -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Restaurant_signup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title model_center" id="myModalLabel">Sign Up</h4>
                </div>

                <div class="modal-body">
                    <div class="col-lg-12 col-md-12">

                        <div class="alert alert-danger" role="alert">This is an Alert box for Showing Warnings!</div>

                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label for="">Email address</label>
                            <input type="email" class="form-control" id="" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" id="" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Password</label>
                            <input type="password" class="form-control" id="" placeholder="Confirm Password">
                        </div>

                        <div class="form-group">
                            <label for="">Mobile No.</label>
                            <input type="tel" class="form-control" id="" placeholder="Mobile No.">
                        </div>

                        <div class="form-group">
                            <label for="">Address</label>
                            <textarea class="form-control" cols="3" rows="3" placeholder="Address"></textarea>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Terms & Condition Apply
                            </label>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-lg btn-success">Submit</button>
                    <a type="submit" class="" style="margin-left:10px;cursor:pointer;">Forget Password ?</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="Restaurant_login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title model_center" id="myModalLabel">Login</h4>
                </div>

                <div class="modal-body">
                    <div class="col-lg-12 col-md-12">

                        <div class="alert alert-danger" role="alert">This is an Alert box for Showing Warnings!</div>


                        <div class="form-group">
                            <label for="">Email address</label>
                            <input type="email" class="form-control" id="" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" id="" placeholder="Password">
                        </div>


                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Terms & Condition Apply
                            </label>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-lg btn-success">Login</button>
                    <a type="submit" class="" style="margin-left:10px;cursor:pointer;" data-toggle="modal"
                        data-target="#Restaurant_forgetpas">Forget Password ?</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="Restaurant_forgetpas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title model_center" id="myModalLabel">Forget Password</h4>
                </div>

                <div class="modal-body">
                    <div class="col-lg-12 col-md-12">

                        <div class="alert alert-danger" role="alert">This is an Alert box for Showing Warnings!</div>


                        <div class="form-group">
                            <label for="">Email address</label>
                            <input type="email" class="form-control" id="" placeholder="Email">
                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-lg btn-success">Submit</button>
                    <button type="submit" class="btn btn-lg btn-danger">Back</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->


    <!-- jquery-->
    <script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>

    <!-- Plugins js -->
    <script src="js/plugins.js" type="text/javascript"></script>

    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js" type="text/javascript"></script>

    <!-- WOW JS -->
    <script src="js/wow.min.js"></script>

    <!-- Nivo slider js -->
    <script src="vendor/slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
    <script src="vendor/slider/js/home.js" type="text/javascript"></script>

    <!-- Owl Cauosel JS -->
    <script src="vendor/OwlCarousel/owl.carousel.min.js" type="text/javascript"></script>

    <!-- Meanmenu Js -->
    <script src="js/jquery.meanmenu.min.js" type="text/javascript"></script>

    <!-- Srollup js -->
    <script src="js/jquery.scrollUp.min.js" type="text/javascript"></script>

    <!-- jquery.counterup js -->
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/waypoints.min.js"></script>

    <!-- Date Time Picker Js -->
    <script src="js/jquery.datetimepicker.full.min.js" type="text/javascript"></script>

    <!-- Validator js -->
    <script src="js/validator.min.js" type="text/javascript"></script>

    <!-- Custom Js -->
    <script src="js/main.js" type="text/javascript"></script>

    <script type="text/javascript">

    </script>

</body>

</html>