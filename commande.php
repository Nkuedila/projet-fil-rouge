<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Passer une commande</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <script>
    function showAlert(event) {
      event.preventDefault(); // Empêcher la soumission par défaut du formulaire
      alert("Votre commande a été enregistrée !");

      // Vous pouvez soumettre le formulaire après l'alerte si nécessaire :
      document.querySelector('.order-form').submit();
    }
  </script>
</head>

<body>
  <?php include('navbar.php'); ?>
  <?php include('db_connect.php');
  $db = ConnexionBase();

  // Récupération des données passées via l'URL (s'il y en a)
  $id = isset($_GET['id']) ? $_GET['id'] : 'Id du plat';
  /* 
  $name = isset($_GET['name']) ? $_GET['name'] : 'Nom du plat';
  $description = isset($_GET['description']) ? $_GET['description'] : 'Description du plat';
  $img = isset($_GET['img']) ? $_GET['img'] : 'placeholder.jpg';
  $prix = isset($_GET['prix']) ? $_GET['prix'] : 'pri'; */

  $requete = $db->query("SELECT * FROM plat where id =$id");
  $tableau = $requete->fetchAll(PDO::FETCH_OBJ);
  $requete->closeCursor();



  ?>

  <main class="container my-5">
    <h2>Passer une commande</h2>
    <hr>

    <!-- Formulaire de commande -->
    <form class="order-form" onsubmit="showAlert(event)" method="post" action="submit_order.php">
      <div class="row justify-content-center">
        <div class="col-md-6 mb-4">
          <div class="card dish-card text-center">
            <?php foreach ($tableau as $plat): ?>
              <img id="dish-img" src="/images_the_district/food/<?= $plat->image; ?>" class="card-img-top mx-auto" alt="Image du plat">
              <div class="card-body">
                <h5 id="dish-title" class="card-title"><?= $plat->libelle; ?></h5>
                <p id="dish-description" class="card-text"><?= $plat->description; ?></p>
                <p id="dish-price" class="card-text"><strong>Prix: <?= number_format($plat->prix, 2) ?> €</strong></p>

              <?php endforeach ?>

              <div class="mb-3">

                <label for="quantite" class="form-label">Quantité</label>
                <input type="number" class="form-control" id="quantite" name="quantite" min="1" required>

                <!-- Utilisation de hidden pour passer l'ID du plat -->
                <h2><?= htmlspecialchars($id); ?></h2>
                <input type="hidden" class="form-control" id="id_plat" name="id_plat" value="<?= $plat->id; ?>">


              </div>
              </div>
          </div>
        </div>
      </div>
      </div>

      <!-- Section d'information personnelle -->
      <h3>Informations Personnelles</h3>
      <div class="mb-3">
        <label for="first-name" class="form-label">Nom</label>
        <input type="text" class="form-control" id="first-name" name="first_name" required>
      </div>
      <div class="mb-3">
        <label for="last-name" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="last-name" name="last_name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Téléphone</label>
        <input type="tel" class="form-control" id="phone" name="phone" required>
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Votre Adresse</label>
        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
      </div>

      <!-- Bouton de soumission -->
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
  </main>

  <?php include('footer.php'); ?>
</body>

</html>