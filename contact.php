<?php
// Include PHPMailer classes and database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is autoloaded
include('db_connect.php'); // Include your database connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve and sanitize form data
  $nom = isset($_POST['nom']) ? htmlspecialchars(trim($_POST['nom'])) : '';
  $prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : '';
  $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
  $telephone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
  $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Adresse e-mail invalide.";
    exit;
  }

  try {
    // Insert contact info into the database
    $conn = ConnexionBase(); // Connect to your database
    $sql = "INSERT INTO utilisateur (nom, prenom, email, telephone, message) 
                VALUES (:nom, :prenom, :email, :telephone, :message)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':telephone', $telephone, PDO::PARAM_STR);
    $stmt->bindValue(':message', $message, PDO::PARAM_STR);

    // Execute the query
    if ($stmt->execute()) {
      echo "Votre message a été envoyé avec succès!";

      // Send confirmation email using PHPMailer
      $mail = new PHPMailer(true);
      try {
        // SMTP settings for Mailhog (localhost, port 1025)
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;  // No authentication required for Mailhog

        // Set sender and recipient
        $mail->setFrom('contact@yourdomain.com', 'The District');
        $mail->addAddress($email, $prenom . ' ' . $nom); // To user's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre contact';
        $mail->Body    = '<h1>Merci pour votre message!</h1>' .
          '<p>Bonjour ' . $prenom . ' ' . $nom . ',</p>' .
          '<p>Nous avons bien reçu votre message :</p>' .
          '<p><strong>Téléphone:</strong> ' . $telephone . '</p>' .
          '<p><strong>Message:</strong> ' . $message . '</p>' .
          '<p>Nous vous contacterons sous peu.</p>';

        // Send the email
        $mail->send();
        echo 'Un email de confirmation a été envoyé!';
      } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
      }
    } else {
      echo "Erreur lors de l'enregistrement du message.";
    }
  } catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
  } catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Contactez-Nous</title>
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

  <main class="container my-5">
    <h2>Contactez-Nous</h2>
    <hr />
    <form
      action="contact.php"
      method="post">

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="firstName" class="form-label">Nom</label>
          <input
            type="text"
            class="form-control"
            id="nom"
            name="nom"
            placeholder="Votre nom"
            required />
        </div>
        <div class="col-md-6 mb-3">
          <label for="lastName" class="form-label">Prénom</label>
          <input
            type="text"
            class="form-control"
            id="prenom"
            name="prenom"
            placeholder="Votre prenom"
            required />
        </div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="text"
          class="form-control"
          id="email"
          name="email"
          placeholder="Votre email"
          required />
      </div>
      <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input
          type="text"
          class="form-control"
          id="telephone"
          name="phone"
          placeholder="Votre numéro de téléphone"
          required />
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea
          class="form-control"
          id="message"
          name="message"
          rows="5"
          placeholder="Décrivez votre demande"
          required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
  </main>

</body>

</html>