<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta property="description" name="description" content="">
    <meta name="keywords" content="">
    <!-- <meta name="robots" content="index, follow"> -->
    <link rel="icon" href="assets/images/logo.png" type="image/x-icon">

    <title>Dar Al Hay</title>

    <!-- Fonts Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Lato:wght@400;700&family=Poppins:wght@300;400;500;600;700;800;900&family=Roboto:wght@400;500;700;900&display=swap&family=Alexandria:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Fonts Google -->

    <!-- Select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css">
    <!-- Select -->

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <!-- FontAwesome -->

    <!-- Slick Slider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.8/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <!-- Slick Slider -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" defer>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">

    <link rel="stylesheet" href="assets/style/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="assets/style/style.min.css">
    <link rel="stylesheet" href="assets/style/responsive.min.css">
</head>

<body>

    <!-- Remove transparent-header For White Header -->
    <header <?php if(isset($addHeaderClass) && $addHeaderClass) echo 'class="transparent-header"'; ?> id="header">
        <div class="header-top ff-gilroy-medium">
            <div class="container-1820">
                <div class="flex-justify-align">
                    <div class="track-flex ml-auto">
                        <div class="track-my-order">
                            <a class="flex-justify-align" href="#">
                                <img src="assets/images/icons/location-marker-red.png" alt="">
                                <h3 class="fa-18 text-uppercase">Track my Order</h3>
                            </a>
                        </div>
                        <div class="flex-justify-align last-child-select-ml-0 mx-auto mx-md-0">
                            <div class="flex-justify-align icon-uae ">

                                <div class="custom-select-mine">
                                    <div class="select-selected px-0">
                                        <span class="ff-alexandria">العربية</span>
                                    </div>
                                    <div class="select-items">
                                        <div>
                                            <!-- <span><img src="assets/images/icons/uk.svg" alt=""></span> -->
                                            <span>English</span>
                                        </div>
                                        <div class="same-as-selected">
                                            <!-- <span><img src="assets/images/icons/uae.png" alt=""></span> -->
                                            <span class="ff-alexandria">العربية</span>
                                        </div>
                                    </div>
                                    <!-- <i class="fa-solid fa-chevron-down"></i> -->
                                    <!-- <img src="assets/images/icons/arrowdown.svg" alt=""> -->
                                </div>
                            </div>
                            <span class="divider-select">|</span>
                            <div class="last-child-select-ml-0">
                                <div class="custom-select-mine">
                                    <div class="select-selected">AED</div>
                                    <div class="select-items">
                                        <div><span>USD</span></div>
                                        <div><span>GBP</span></div>
                                        <div><span>Euro</span></div>
                                        <div class="same-as-selected"><span>AED</span></div>
                                    </div>
                                    <!-- <i class="fa-solid fa-chevron-down"></i> -->
                                    <img src="assets/images/icons/arrowdownred.svg" alt="">
                                </div>
                            </div>
                        </div>
                        <a class="sign-in-btn fa-18" href="#">
                            Sign In / Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="icon-links-for-bg">
            <div class="container-1820 position-relative">
                <div class="flex-justify-align">
                    <a href="#" class="mx-auto main-logo">
                        <img src="assets/images/logo-transparent.png" alt="">
                    </a>
                    <div class="items-shopping m-0">
                        <div class="flex-justify-align justify-content-end">
                            <div class="position-relative custom-search-parent">
                            <div class="customsearch">
                                <form action="#">
                                    <input type="text" id="autocomplete" placeholder="Search Products" autocomplete="off">
                                    <button class="btn-header-icons search-icon">
                                        <img src="assets/images/icons/search.png" alt="">
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- <a class="btn-header-icons profile" href="#">
                        <img src="assets/images/icons/profile.png" alt="">
                    </a> -->
                        <a class="btn-header-icons wishlist" href="#">
                            <img src="assets/images/icons/wishtlist.png" alt="">
                            <span class="items-selected">20</span>
                        </a>
                        <a class="btn-header-icons cart mr-inner-span" href="#">
                            <img src="assets/images/icons/bagicon.png" alt="">
                            <span class="items-selected">30</span>
                        </a>
                        <div class="icon-sub-menu-button d-lg-none">
                            <button>
                                <span class="lines-sub-menu top-line"></span>
                                <span class="lines-sub-menu center-line"></span>
                                <span class="lines-sub-menu bottom-line"></span>
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-links-bg">
            <div class="container-1820">
                <div class="flex-justify-align header-settings">
                    <div class="links-header">
                        <!-- <div class="d-lg-none logoimage">
                            <a href="#">
                                <img src="assets/images/logo-transparent.png" alt="">
                            </a>
                        </div> -->
                        <ul class="flex-justify-align">
                            <div class="icon-sub-menu-button cross-btn-inside-li d-lg-none">
                                <button>
                                    <span class="lines-sub-menu top-line"></span>
                                    <span class="lines-sub-menu center-line"></span>
                                    <span class="lines-sub-menu bottom-line"></span>
                                </button>
                            </div>
                            <li class="sub-menu-container">
                                <div class="sub-menu-opener ff-gilroy-semibold">
                                    <a href="#">Customize Kandora</a>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="#">Arabic</a>
                                    </li>
                                    <li>
                                        <a href="#">Kuwaiti</a>
                                    </li>
                                    <li>
                                        <a href="#">Omani</a>
                                    </li>
                                    <li>
                                        <a href="#">Qatari</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sub-menu-container">
                                <div class="sub-menu-opener">
                                    <a href="#">Kandora Collections</a>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <!-- <a href="#">Kandora Collections<i class="fa-solid fa-chevron-down"></i></a> -->
                                <ul class="sub-menu sub-menu-bigger">
                                    <li>
                                        <div class="sub-menu-biiger-item">
                                            <div class="flex-justify">
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Arabic</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Arabic Adults</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Arabic Kids</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Kuwaiti</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Kuwaiti Adults</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Kuwaiti Kids</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Omani</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Omani Adults</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Omani Kids</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Qatari</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Qatari Adults</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Qatari Kids</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="advertisement">
                                                <img src="assets/images/icons/greyimage.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Perfumes</a>
                            </li>
                            <li class="sub-menu-container">
                                <div class="sub-menu-opener">
                                    <a href="#">Footwear</a>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <!-- <a href="#">Footwear<i class="fa-solid fa-chevron-down"></i></a> -->
                                <ul class="sub-menu">
                                    <li>
                                        <a href="#">Sandals</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sub-menu-container">
                                <div class="sub-menu-opener">
                                    <a href="#">Accessories</a>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <!-- <a href="#">Accessories<i class="fa-solid fa-chevron-down"></i></a> -->
                                <ul class="sub-menu sub-menu-bigger">
                                    <li>
                                        <div class="sub-menu-biiger-item">
                                            <div class="flex-justify">
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Caps</h3>
                                                    <h3>Bisht</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Machine Made Embroidery</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Handmade</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Machine Made</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Ghafiya</h3>
                                                    <h3>Ghatra</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Japan</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Swiss</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Special</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Plain</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Readymade Jalabiya</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Kids</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Adults</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="item-bigger-sub-menu">
                                                    <h3>Inner Wear</h3>
                                                    <ul>
                                                        <li>
                                                            <a href="#">Pants</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Shorts</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Wezars</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Wolves & Lions</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">2 Sets</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="advertisement">
                                                <img src="assets/images/icons/greyimage.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Fabric</a>
                            </li>
                            <li>
                                <a href="#">Onsale</a>
                            </li>
                            <li class="sub-menu-container">
                                <div class="sub-menu-opener">
                                    <a href="#">Raffle Draw</a>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <!-- <a href="#">Raffle Draw<i class="fa-solid fa-chevron-down"></i></a> -->
                                <ul class="sub-menu left-auto-right-0">
                                    <li>
                                        <a href="#">About Contest</a>
                                    </li>
                                    <li>
                                        <a href="#">Winners</a>
                                    </li>
                                    <li>
                                        <a href="#">Live Streaming</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="overflow-hidden">