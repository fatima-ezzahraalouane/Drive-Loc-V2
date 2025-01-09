<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';

$database = new Database();
$conn = $database->getConnection();

try {
    $categorie_query = "SELECT id_categorie, nom FROM categorie";
    $categorie_stmt = $conn->prepare($categorie_query);
    $categorie_stmt->execute();
    $categorie_result = $categorie_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des catégories : " . $e->getMessage());
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Location']) && !empty($_POST['Location'])) {
        $categorie_id = $_POST['Location'];
        $query = "SELECT * FROM vehicule WHERE id_categorie = :categorie_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':categorie_id', $categorie_id, PDO::PARAM_INT);
    } else {
        $query = "SELECT * FROM vehicule";
        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $vehicules_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des véhicules : " . $e->getMessage());
}


// Pagination Logic
$items_per_page = 6; // Show 6 cards per page
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Fetch paginated data directly from the database
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Location']) && !empty($_POST['Location'])) {
        $categorie_id = $_POST['Location'];
        $query = "SELECT * FROM vehicule WHERE id_categorie = :categorie_id LIMIT :offset, :items_per_page";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':categorie_id', $categorie_id, PDO::PARAM_INT);
    } else {
        $query = "SELECT * FROM vehicule LIMIT :offset, :items_per_page";
        $stmt = $conn->prepare($query);
    }

    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);

    $stmt->execute();
    $paginated_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total vehicle count for pagination
    $total_query = "SELECT COUNT(*) AS total FROM vehicule";
    $total_stmt = $conn->prepare($total_query);
    $total_stmt->execute();
    $total_items = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_items / $items_per_page);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des véhicules : " . $e->getMessage());
}



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

    <style>
        /* Filter and Search Bar */
        .filter-search-container {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-bar .filter-select {
            padding: 8px 12px;
            border: 1px solid var(--bs-secondary);
            border-radius: 5px;
        }

        .filter-bar .search-input {
            padding: 8px 12px;
            border: 1px solid var(--bs-secondary);
            border-radius: 5px;
            width: 200px;
        }

        .filter-bar .btn {
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
        }

        /* Vehicules List */
        .vehicules-list {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .vehicule-item {
            border: 1px solid var(--bs-secondary);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: box-shadow 0.3s;
        }

        .vehicule-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
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
                        <a href="vehicule.php" class="nav-item nav-link active">Véhicule</a>
                        <a href="blog.php" class="nav-item nav-link">Blog</a>
                        <a href="article.php" class="nav-item nav-link">Articles</a>
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
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Nos Véhicules</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="accueil.php">Accueil</a></li>
                <!-- <li class="breadcrumb-item"><a href="#">Pages</a></li> -->
                <li class="breadcrumb-item active text-primary">Véhicule</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Car categories Start -->
    <div class="container-fluid categories py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize mb-3">Catégories de <span class="text-primary">Véhicules</span></h1>
                <p class="mb-0">Chez Drive & Loc, nous offrons une gamme variée de véhicules pour répondre à tous vos besoins.</p>
            </div>

            <!-- Filter and Search Bar -->
            <div class="filter-search-container mb-4">
                <form method="GET" action="vehicule.php">
                    <div class="filter-bar">
                        <select name="Location" class="filter-select" id="chooseLocation">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categorie_result as $categorie) { ?>
                                <option value="<?= htmlspecialchars($categorie['id_categorie']) ?>">
                                    <?= htmlspecialchars($categorie['nom']) ?>
                                </option>
                            <?php } ?>
                        </select>
                        <input type="text" name="search" class="search-input" placeholder="Rechercher un véhicule...">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                    </div>
                </form>
            </div>

            <!-- Vehicles Grid -->
            <div class="row g-4">
                <?php if (!empty($vehicules_result)) {
                    foreach ($vehicules_result as $vehicule) { ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="categories-item p-4">
                                <div class="categories-img rounded-top">
                                    <img src="<?= htmlspecialchars($vehicule['imageUrl']) ?>" class="img-fluid w-100 rounded-top" alt="image vehicule">
                                </div>
                                <div class="categories-content rounded-bottom p-4 text-center">
                                    <h4><?= htmlspecialchars($vehicule['modele']) ?></h4>
                                    <h6 class="text-body"><?= htmlspecialchars($vehicule['marque']) ?></h6>
                                    <h5 class="bg-white text-primary rounded-pill py-2 px-4 mb-3"><?= htmlspecialchars($vehicule['prix_par_jour']) ?> DH/Jour</h5>
                                    <a href="accueil.php?modele=<?php echo $vehicule['modele'] ?>" class="btn btn-primary rounded-pill justify-content-center py-2">Réservez maintenant</a>
                                    <a href="details.php" class="btn btn-primary rounded-pill justify-content-center py-2">Détails</a>
                                </div>
                            </div>
                        </div>
                <?php }
                } else {
                    echo "<p class='text-danger text-center'>Aucun véhicule trouvé. Essayez une autre catégorie.</p>";
                } ?>
            </div>

            <!-- Pagination Controls -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mt-4">
                    <li class="page-item <?= $current_page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $current_page - 1 ?>">Précédent</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php } ?>
                    <li class="page-item <?= $current_page == $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $current_page + 1 ?>">Suivant</a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>

    <!-- Car categories End -->

    <!-- Car Steps Start -->
    <!-- <div class="container-fluid steps py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                <h1 class="display-5 text-capitalize text-white mb-3">Cental<span class="text-primary"> Process</span></h1>
                <p class="mb-0 text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ut amet nemo expedita asperiores commodi accusantium at cum harum, excepturi, quia tempora cupiditate! Adipisci facilis modi quisquam quia distinctio,
                </p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="steps-item p-4 mb-4">
                        <h4>Come In Contact</h4>
                        <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad, dolorem!</p>
                        <div class="setps-number">01.</div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="steps-item p-4 mb-4">
                        <h4>Choose A Car</h4>
                        <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad, dolorem!</p>
                        <div class="setps-number">02.</div>
                    </div>
                </div>
                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="steps-item p-4 mb-4">
                        <h4>Enjoy Driving</h4>
                        <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad, dolorem!</p>
                        <div class="setps-number">03.</div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Car Steps End -->

    <!-- Banner Start -->
    <!-- <div class="container-fluid banner py-5 wow zoomInDown" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="banner-item rounded">
                <img src="img/banner-1.jpg" class="img-fluid rounded w-100" alt="">
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
    </div> -->
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
    <script src="../assets/lib/wow/wow.min.js"></script>
    <script src="../assets/lib/easing/easing.min.js"></script>
    <script src="../assets/lib/waypoints/waypoints.min.js"></script>
    <script src="../assets/lib/counterup/counterup.min.js"></script>
    <script src="../assets/lib/owlcarousel/owl.carousel.min.js"></script>


    <!-- Template Javascript -->
    <script src="../assets/js/main.js"></script>
</body>

</html>