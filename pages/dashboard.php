<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: signup.php');
    exit();
}

require '../config/Database.php';
require '../classes/Article.php';
require '../classes/Theme.php';
require '../classes/Tags.php';

$database = new Database();
$conn = $database->getConnection();

$theme = new Theme($conn);
$themes = $theme->getAllThemes();

$tag = new Tag($conn);
$tags = $tag->getAllTags();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drive & Loc - Admin Dashboard</title>
    <link rel="icon" href="../assets/img/loclogo-removebg-preview.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, rgb(119, 13, 13), rgb(230, 128, 128));
        }

        .add-input-btn,
        .remove-input-btn {
            width: 40px;
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





    <!-- Gestion des Thèmes -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Gestion des Thèmes</h2>
            <button class="btn text-white" style="background: linear-gradient(135deg,rgb(119, 13, 13),rgb(230, 128, 128));
" data-bs-toggle="modal" data-bs-target="#addThemeModal">
                <i class="fas fa-plus me-1"></i> Ajouter un thème
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
                    <?php

                    foreach ($themes as $theme): ?>
                        <tr>
                            <td><?= $theme['id_theme'] ?></td>
                            <td><?= htmlspecialchars($theme['nom']) ?></td>
                            <td><?= htmlspecialchars($theme['description']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editThemeModal<?= $theme['id_theme'] ?>">Modifier</button>
                                <form method="POST" action="delete_theme.php" class="d-inline">
                                    <input type="hidden" name="id_theme" value="<?= $theme['id_theme'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editThemeModal<?= $theme['id_theme'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="edit_theme.php">
                                        <div class="modal-header">
                                            <h5>Modifier le Thème</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_theme" value="<?= $theme['id_theme'] ?>">
                                            <div class="mb-3">
                                                <label>Nom</label>
                                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($theme['nom']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control" required><?= htmlspecialchars($theme['description']) ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label>Image URL</label>
                                                <input type="text" name="imgUrl" class="form-control" value="<?= htmlspecialchars($theme['imgUrl']) ?>">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajouter un Thème -->
    <div class="modal fade" id="addThemeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="add_theme.php">
                    <div class="modal-header">
                        <h5>Ajouter un Thème</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Image URL</label>
                            <input type="text" name="imgUrl" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Gestion des Tags</h2>
            <button class="btn text-white" style="background: linear-gradient(135deg,rgb(119, 13, 13),rgb(230, 128, 128));
" data-bs-toggle="modal" data-bs-target="#addTagModal">
                <i class="fas fa-plus me-1"></i> Ajouter des Tags
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-danger">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($tags as $tag): ?>
                        <tr>
                            <td><?= $tag['id_tag'] ?></td>
                            <td><?= htmlspecialchars($tag['nom']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTagModal<?= $tag['id_tag'] ?>">Modifier</button>

                                <form method="POST" action="delete_tag.php" class="d-inline">
                                    <input type="hidden" name="id_tag" value="<?= $tag['id_tag'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editTagModal<?= $tag['id_tag'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="edit_tag.php">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier le Tag</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_tag" value="<?= $tag['id_tag'] ?>">
                                            <div class="mb-3">
                                                <label for="nom_tag_<?= $tag['id_tag'] ?>">Nom</label>
                                                <input type="text" name="nom_tag" id="nom_tag_<?= $tag['id_tag'] ?>" class="form-control" value="<?= htmlspecialchars($tag['nom']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajouter des Tags -->
    <div class="modal fade" id="addTagModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="add_tags.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter des Tags</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="tagInputsContainer">
                            <div class="input-group mb-2">
                                <input type="text" name="tags[]" class="form-control" placeholder="Entrez un tag" required>
                                <button type="button" class="btn btn-outline-secondary add-input-btn">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
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
                        à
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('tagInputsContainer');

            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('add-input-btn')) {
                    e.preventDefault();

                    const newInputGroup = document.createElement('div');
                    newInputGroup.classList.add('input-group', 'mb-2');

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'tags[]';
                    input.classList.add('form-control');
                    input.placeholder = 'Entrez un tag';
                    input.required = true;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.classList.add('btn', 'btn-outline-secondary', 'remove-input-btn');
                    button.textContent = '-';

                    newInputGroup.appendChild(input);
                    newInputGroup.appendChild(button);

                    container.appendChild(newInputGroup);
                }

                if (e.target && e.target.classList.contains('remove-input-btn')) {
                    e.preventDefault();
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>