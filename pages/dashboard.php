<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: signup.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive & Loc - Admin Dashboard</title>
    <link rel="icon" href="../assets/img/loclogo-removebg-preview.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, rgb(119, 13, 13), rgb(230, 128, 128));
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg bg-gradient-primary navbar-dark shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Drive & Loc</a>
            <div class="d-flex align-items-center">
                <!-- <span class="text-white me-3">Admin Name</span> -->
                <img src="../assets/img/admin.jpg" alt="" style="width: 38px; height: 38px; border-radius: 20px; margin-right: 5px;">
                <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="text-white text-center py-5 bg-gradient-primary">
        <div class="container">
            <h1 class="display-4 fw-bold">Tableau de bord d'administration</h1>
            <p class="lead">Gérer les catégories, les véhicules et les réservations</p>
        </div>
    </section>

    <div class="container my-5">

        <!-- Category Management -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Gestion des catégories</h2>
                <button class="btn text-white" style="background: linear-gradient(135deg,rgb(119, 13, 13),rgb(230, 128, 128));
" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-1"></i> Ajouter une catégorie
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-danger">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>SUV</td>
                            <td>Spacious and comfortable</td>
                            <td>
                                <button class="btn btn-warning btn-sm">Modifier</button>
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Sedan</td>
                            <td>Elegant and efficient</td>
                            <td>
                                <button class="btn btn-warning btn-sm">Modifier</button>
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vehicle Management -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Gestion des véhicules</h2>
                <button class="btn text-white" style="background: linear-gradient(135deg,rgb(119, 13, 13),rgb(230, 128, 128));
" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                    <i class="fas fa-plus me-1"></i> Ajouter un véhicule
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-danger">
                        <tr>
                            <th>ID</th>
                            <th>Model</th>
                            <th>Marque</th>
                            <th>Category</th>
                            <th>Price/Day</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Model X</td>
                            <td>Tesla</td>
                            <td>SUV</td>
                            <td>$100</td>
                            <td><span class="badge bg-success">Disponible</span></td>
                            <td>
                                <button class="btn btn-warning btn-sm">Modifier</button>
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Civic</td>
                            <td>Honda</td>
                            <td>Sedan</td>
                            <td>$80</td>
                            <td><span class="badge bg-danger">Indisponible</span></td>
                            <td>
                                <button class="btn btn-warning btn-sm">Modifier</button>
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reservations Management -->
        <div class="mb-5">
            <h2>Gestion des réservations</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-danger">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Vehicle</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>Model X</td>
                            <td>2024-06-01</td>
                            <td>2024-06-05</td>
                            <td><span class="badge bg-warning">En attente</span></td>
                            <td>
                                <button class="btn btn-success btn-sm">Approuver</button>
                                <button class="btn btn-danger btn-sm">Refuser</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5>Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control mb-2" placeholder="Category Name">
                        <textarea class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5>Add Vehicle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control mb-2" placeholder="Vehicle Model">
                        <input type="text" class="form-control mb-2" placeholder="Marque">
                        <input type="number" class="form-control mb-2" placeholder="Price per Day">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>