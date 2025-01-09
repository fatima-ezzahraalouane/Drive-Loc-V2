<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Article.php';

$database = new Database();
$conn = $database->getConnection();

$article = new Article($conn);

$articles = $article->getAllArticlesWithComments();
?>




<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Drive & Loc</title>
    <link rel="icon" href="../assets/img/loclogo-removebg-preview.png">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link rel="stylesheet" href="../assets/lib/animate/animate.min.css">
    <link rel="stylesheet" href="../assets/lib/owlcarousel/assets/owl.carousel.min.css">


    <!-- Customized Bootstrap Stylesheet -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <!-- Template Stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <div class="container-fluid topbar bg-secondary d-none d-xl-block w-100">
        <div class="container">
            <div class="row gx-0 align-items-center" style="height: 45px;">
                <div class="col-lg-6 text-center text-lg-start mb-lg-0">
                    <div class="d-flex flex-wrap">
                        <a href="#" class="text-muted me-4"><i class="fas fa-map-marker-alt text-primary me-2"></i>Trouver un emplacement</a>
                        <a href="tel:+212 654-917320" class="text-muted me-4"><i class="fas fa-phone-alt text-primary me-2"></i>+212 654-917320</a>
                        <a href="mailto:driveloc@gmail.com" class="text-muted me-0"><i class="fas fa-envelope text-primary me-2"></i>driveloc@gmail.com</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center text-lg-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-light btn-sm-square rounded-circle me-0"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Hero Start -->
    <div class="container-fluid nav-bar sticky-top px-0 px-lg-4 py-2 py-lg-0">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a href="accueil.php" class="navbar-brand p-0">
                    <!-- <h1 class="display-6 text-primary"><i class="fas fa-car-alt me-3"></i></i>Cental</h1> -->
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                    <img src="../assets/img/loclogo-removebg-preview.png" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav mx-auto py-0">
                        <a href="accueil.php" class="nav-item nav-link">Accueil</a>
                        <a href="vehicule.php" class="nav-item nav-link">Véhicule</a>
                        <a href="blog.php" class="nav-item nav-link">Blog</a>
                        <a href="article.php" class="nav-item nav-link active">Articles</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="service.html" class="nav-item nav-link">Service</a>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0">
                                <a href="feature.html" class="dropdown-item">Our Feature</a>
                                <a href="cars.html" class="dropdown-item">Our Cars</a>
                                <a href="team.html" class="dropdown-item">Our Team</a>
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                <a href="404.html" class="dropdown-item">404 Page</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                    </div>
                    <a href="logout.php" class="btn btn-primary rounded-pill py-2 px-4">Se déconnecter</a>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Our Blog & News</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-primary">Blog & News</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Blog Start -->
    <div class="container-fluid blog py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Articles<span class="text-primary"> Drive & Loc</span></h1>
                <p class="mb-0">Explorez nos articles soigneusement sélectionnés pour enrichir vos expériences de location de voiture</p>
                </p>
            </div>
            <div class="row g-4">
                <?php foreach ($articles as $a): ?>
                    <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="<?= htmlspecialchars($a['image_url']) ?>" class="img-fluid rounded-top w-100" alt="Image">
                            </div>
                            <div class="blog-content rounded-bottom p-4">
                                <div class="blog-date"><?= date('d M Y', strtotime($a['date_creation'])) ?></div>
                                <div class="blog-comment my-3">
                                    <div class="small"><span class="fa fa-user text-primary"></span><span class="ms-2"><?= htmlspecialchars($a['id_user']) ?></span></div>
                                    <div class="small"><span class="fa fa-comment-alt text-primary"></span><span class="ms-2"><?= $a['comment_count'] ?> Comments</span></div>
                                </div>
                                <a href="#" class="h4 d-block mb-3"><?= htmlspecialchars($a['titre']) ?></a>
                                <p class="mb-3"><?= substr(htmlspecialchars($a['contenu']), 0, 50) ?>...</p>
                                <a href="view_article.php?id=<?= $a['id_article'] ?>" class="">Read More <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="blog-item">
                        <div class="blog-img">
                            <img src="../assets/img/blog-2.jpg" class="img-fluid rounded-top w-100" alt="Image">
                        </div>
                        <div class="blog-content rounded-bottom p-4">
                            <div class="blog-date">25 Dec 2025</div>
                            <div class="blog-comment my-3">
                                <div class="small"><span class="fa fa-user text-primary"></span><span class="ms-2">Martin.C</span></div>
                                <div class="small"><span class="fa fa-comment-alt text-primary"></span><span class="ms-2">6 Comments</span></div>
                            </div>
                            <a href="#" class="h4 d-block mb-3">Rental cost of sport and other cars</a>
                            <p class="mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eius libero soluta impedit eligendi? Quibusdam, laudantium.</p>
                            <a href="#" class="">Read More <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="blog-item">
                        <div class="blog-img">
                            <img src="../assets/img/blog-3.jpg" class="img-fluid rounded-top w-100" alt="Image">
                        </div>
                        <div class="blog-content rounded-bottom p-4">
                            <div class="blog-date">27 Dec 2025</div>
                            <div class="blog-comment my-3">
                                <div class="small"><span class="fa fa-user text-primary"></span><span class="ms-2">Martin.C</span></div>
                                <div class="small"><span class="fa fa-comment-alt text-primary"></span><span class="ms-2">6 Comments</span></div>
                            </div>
                            <a href="#" class="h4 d-block mb-3">Document required for car rental</a>
                            <p class="mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eius libero soluta impedit eligendi? Quibusdam, laudantium.</p>
                            <a href="#" class="">Read More <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!-- Blog End -->

    <!-- Fact Counter -->
    <div class="container-fluid counter py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-thumbs-up fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">829</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Happy Clients</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-car-alt fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">56</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Number of Cars</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">127</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Car Center</h4>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="counter-item text-center">
                        <div class="counter-item-icon mx-auto">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="counter-counting my-3">
                            <span class="text-white fs-2 fw-bold" data-toggle="counter-up">589</span>
                            <span class="h1 fw-bold text-white">+</span>
                        </div>
                        <h4 class="text-white mb-0">Total kilometers</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fact Counter -->

    <!-- Banner Start -->
    <div class="container-fluid banner py-5 wow zoomInDown" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="banner-item rounded">
                <img src="../assets/img/banner-1.jpg" class="img-fluid rounded w-100" alt="">
                <div class="banner-content">
                    <h2 class="text-primary">Rent Your Car</h2>
                    <h1 class="text-white">Interested in Renting?</h1>
                    <p class="text-white">Don't hesitate and send us a message.</p>
                    <div class="banner-btn">
                        <a href="#" class="btn btn-secondary rounded-pill py-3 px-4 px-md-5 me-2">WhatchApp</a>
                        <a href="#" class="btn btn-primary rounded-pill py-3 px-4 px-md-5 ms-2">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Footer Start -->
    <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <div class="footer-item">
                            <h4 class="text-white mb-4">About Us</h4>
                            <p class="mb-3">Dolor amet sit justo amet elitr clita ipsum elitr est.Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.</p>
                        </div>
                        <div class="position-relative">
                            <input class="form-control rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
                            <button type="button" class="btn btn-secondary rounded-pill position-absolute top-0 end-0 py-2 mt-2 me-2">Subscribe</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-white mb-4">Quick Links</h4>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> About</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Cars</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Car Types</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Team</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Contact us</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Terms & Conditions</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-white mb-4">Business Hours</h4>
                        <div class="mb-3">
                            <h6 class="text-muted mb-0">Mon - Friday:</h6>
                            <p class="text-white mb-0">09.00 am to 07.00 pm</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-0">Saturday:</h6>
                            <p class="text-white mb-0">10.00 am to 05.00 pm</p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-0">Vacation:</h6>
                            <p class="text-white mb-0">All Sunday is our vacation</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="text-white mb-4">Contact Info</h4>
                        <a href="#"><i class="fa fa-map-marker-alt me-2"></i> 123 Street, New York, USA</a>
                        <a href="mailto:info@example.com"><i class="fas fa-envelope me-2"></i> info@example.com</a>
                        <a href="tel:+012 345 67890"><i class="fas fa-phone me-2"></i> +012 345 67890</a>
                        <a href="tel:+012 345 67890" class="mb-3"><i class="fas fa-print me-2"></i> +012 345 67890</a>
                        <div class="d-flex">
                            <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-facebook-f text-white"></i></a>
                            <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-twitter text-white"></i></a>
                            <a class="btn btn-secondary btn-md-square rounded-circle me-3" href=""><i class="fab fa-instagram text-white"></i></a>
                            <a class="btn btn-secondary btn-md-square rounded-circle me-0" href=""><i class="fab fa-linkedin-in text-white"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-start mb-md-0">
                    <span class="text-body"><a href="#" class="border-bottom text-white"><i class="fas fa-copyright text-light me-2"></i>Div & Loc</a>, Tous droits réservés.</span>
                </div>
                <div class="col-md-6 text-center text-md-end text-body">
                    <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                    <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                    <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                    Conçu par <a class="border-bottom text-white" href="https://github.com/fatima-ezzahraalouane">Fatima-Ezzahra Alouane</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-secondary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="lib/wow/wow.min.js"></script> -->
    <!-- <script src="lib/easing/easing.min.js"></script> -->
    <!-- <script src="lib/waypoints/waypoints.min.js"></script> -->
    <!-- <script src="lib/counterup/counterup.min.js"></script> -->
    <!-- <script src="lib/owlcarousel/owl.carousel.min.js"></script> -->

    <script src="../assets/lib/wow/wow.min.js"></script>
    <script src="../assets/lib/easing/easing.min.js"></script>
    <script src="../assets/lib/waypoints/waypoints.min.js"></script>
    <script src="../assets/lib/counterup/counterup.min.js"></script>
    <script src="../assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <!-- <script src="js/main.js"></script> -->
    <script src="../assets/js/main.js"></script>
</body>

</html>