<?php

// Connexion à la base de données
include "db_connect.php";
$db = ConnexionBase(); // On suppose que la fonction ConnexionBase() retourne une connexion PDO

// Récupération des plats depuis la base de données
$requete = $db->query("SELECT * FROM plat");
$plats = $requete->fetchAll(PDO::FETCH_OBJ);
$requete->closeCursor();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Tous les Plats</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que style.css existe -->
</head>

<body>
  <?php include('navbar.php'); ?>
  
  <div class="search-container">
    <img src="/images_the_district/fosyyyy.jpg" alt="Background Image" style="height: 650px;">
  </div>

  <main class="container my-5">
    <h2>COMMANDER MAINTENANT</h2>
    <hr>

    <div class="row">
      <?php foreach ($plats as $plat): ?>
        <div class="col-md-4">
          <div class="card dish-card">
            <img src="images_the_district/food/<?= $plat->image ?>" class="card-img-top" alt="<?= htmlspecialchars($plat->nom) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($plat->nom) ?></h5>
              <p class="card-text"><?= htmlspecialchars($plat->description) ?></p>
              <p class="card-text"><strong>Prix: <?= number_format($plat->prix, 2) ?> €</strong></p>
              <a href="commande.php?name=<?= urlencode($plat->nom) ?>&id=<?= urlencode($plat->id) ?>"
                class="btn btn-primary order-button">Commander</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </main>

  <?php include('footer.php'); ?>

</body>

</html>