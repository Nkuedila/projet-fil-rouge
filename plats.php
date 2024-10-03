<?php

include "db_connect.php";
$db= ConnexionBase();


// Retrieve the search query if it exists
$search_query = isset($_GET['search_query']) ? htmlspecialchars(trim($_GET['search_query'])) : '';

if (!empty($search_query)) {
    // If a search query exists, fetch dishes that match the search term
    $requete = $db->prepare("SELECT * FROM plat WHERE libelle LIKE :search_query");
    $requete->execute([':search_query' => '%' . $search_query . '%']);
    $tableau1 = $requete->fetchAll(PDO::FETCH_OBJ);
    $requete->closeCursor();
} else {
    // If no search query, fetch all dishes
    $requete = $db->query("SELECT * FROM plat");
    $tableau1 = $requete->fetchAll(PDO::FETCH_OBJ);
    $requete->closeCursor();
}


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
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
  <?php include('navbar.php'); ?>
   
  <div class="search-container">
      <img src="images_the_district/ibg.jpg" alt="Background Image" style="height: 650px" />
    </div>
    <main class="container my-5">
      <h2>tous les plats</h2>
      <br />
      <hr />
      <div class="row" id="category-container">
        <?php 
          foreach ($tableau1 as $plat) {
            echo '<div class="col-md-4 mb-4 d-none" id="card-' . $plat->id. '">';
            echo '  <div class="card category-card">';
            echo '    <a href="tplats.php"><img src="images_the_district/food/' . $plat->image . '" class="card-img-top" alt="' . $plat->libelle . '" /></a>';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . $plat->libelle . '</h5>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
           }; 


        ?>
      </div>

      
      <div class="d-flex justify-content-between my-4">
        <a
          class="btn btn-primary"
          id="prev-btn"
          role="button"
          aria-label="Previous"
          disabled
          >Previous</a
        >
        <a class="btn btn-primary" id="next-btn" role="button" aria-label="Next"
          >Next</a
        >
      </div>
    </main>

    <script>
      document.addEventListener("DOMContentLoaded", (event) => {
        const cards = Array.from(
          document.querySelectorAll("#category-container .col-md-4")
        );
        const numCardsToShow = 3;
        let currentStartIndex = 0;

        function showCards(startIndex) {
          cards.forEach((card, index) => {
            card.classList.toggle(
              "d-none",
              index < startIndex || index >= startIndex + numCardsToShow
            );
          });
          document.getElementById("prev-btn").disabled = startIndex === 0;
          document.getElementById("next-btn").disabled =
            startIndex + numCardsToShow >= cards.length;
        }

        document.getElementById("next-btn").addEventListener("click", () => {
          if (currentStartIndex + numCardsToShow < cards.length) {
            currentStartIndex += numCardsToShow;
            showCards(currentStartIndex);
          }
        });

        document.getElementById("prev-btn").addEventListener("click", () => {
          if (currentStartIndex - numCardsToShow >= 0) {
            currentStartIndex -= numCardsToShow;
            showCards(currentStartIndex);
          }
        });

        // Initially show the first set of cards
        showCards(currentStartIndex);
      });
    </script>
 <?php include('footer.php'); ?>
  </body>
</html>
