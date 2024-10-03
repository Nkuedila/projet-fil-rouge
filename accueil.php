<?php

include "db_connect.php";
$db = ConnexionBase();
if ($db) {
  //echo("db connect OK");
}

$requete = $db->query("SELECT * FROM categorie");
$tableau = $requete->fetchAll(PDO::FETCH_OBJ);
$requete->closeCursor();

$requete = $db->query("SELECT * FROM plat");
$tableau1 = $requete->fetchAll(PDO::FETCH_OBJ);
$requete->closeCursor();


?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>The District</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <?php include('navbar.php'); ?>
  <!-- nav class="navbar navbar-expand-sm bg-dark navbar-dark">
      <div class="container-fluid"> <div class="row">
        <a class="navbar-brand me-3" href="#">
          <img
            src="logo_transparent.png"
            alt="Logo"
            style="width: 120px; object-fit: cover"
            class="rounded-pill"
          />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#mynavbar"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mynavbar">
          <ul class="navbar-nav mx-auto">
            <li class="nav-item">
              <a class="nav-link" href="accueil.php">Accueil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="categorie.php">Catégorie</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="plats.php">Plats</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
 -->
  <div class="search-container">
    <img src="images_the_district/bg3.jpeg" alt="Background Image" style="height: 650px" />
    <div class="search-bar">
      <form class="d-flex" method="GET" action="tplats.php" ?name=<?= urlencode($plat->nom) ?>&id=<?= urlencode($plat->id) ?>><!-- Use GET and set action to plats.php -->
        <input class="form-control" type="text" name="search_query" placeholder="Search for a dish" value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>" />
        <button class="btn btn-primary" type="submit">Search</button>
      </form>
    </div>
  </div>

  <hr />

  <main class="container my-5">
    <h2>Catégories</h2>
    <br />
    <hr />
    <div class="row" id="category-container">
      <?php

      foreach ($tableau as $category) {
        echo '<div class="col-md-4 mb-4 " id="card-' . $category->id . '">';
        echo '  <div class="card category-card">';
        echo '    <a href="tplats.php"><img src="images_the_district/category/' . $category->image . '" class="card-img-top" alt="' . $category->libelle . '" /></a>';
        echo '    <div class="card-body">';
        echo '      <h5 class="card-title">' . $category->libelle . '</h5>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
      };
      ?>

      <hr />
      <h2>Plats</h2>
      <hr />
      <div class="row" id="category-container">

        <?php
        if (count($tableau1) > 0) {
          foreach ($tableau1 as $plat) {
            echo '<div class="col-md-4 mb-4" id="card-' . $plat->id . '">';
            echo '  <div class="card category-card">';
            echo '    <a href="tplats.php"><img src="images_the_district/food/' . $plat->image . '" class="card-img-top" alt="' . $plat->libelle . '" /></a>';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . $plat->libelle . '</h5>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
          }
        } else {
          echo '<p>No dishes found for "' . htmlspecialchars($search_query) . '".</p>';
        }
        ?>
      </div>
  </main>

  <?php include('footer.php'); ?>

  <!-- <?php
        $conn->close(); // Fermer la connexion à la base de données
        ?>
 -->
</body>

</html>