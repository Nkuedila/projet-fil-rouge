<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Autoloader for PHPMailer
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupérer et valider les données POST
    $id_plat    = isset($_POST['id_plat']) ? intval($_POST['id_plat']) : 1;
    $quantite   = isset($_POST['quantite']) ? intval($_POST['quantite']) : 1;
    $first_name = isset($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : '';
    $last_name  = isset($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : '';
    $email      = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone      = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $address    = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';

    // Vérification du format de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse e-mail invalide.";
        exit;
    }

    try {
        // Connexion à la base de données
        $conn = new PDO('mysql:host=localhost;charset=utf8;dbname=the_district', 'admin', 'Afpa1234');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer le prix du plat
        $price_sql = "SELECT prix FROM plat WHERE id = :id_plat";
        $price_stmt = $conn->prepare($price_sql);
        $price_stmt->bindValue(':id_plat', $id_plat, PDO::PARAM_INT);
        $price_stmt->execute();
        $plat = $price_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$plat) {
            echo "Le plat sélectionné n'existe pas.";
            exit;
        }

        // Calculer le total en fonction de la quantité
        $prix_unitaire = floatval($plat['prix']);
        $total = $quantite * $prix_unitaire;
        echo "Le total de la commande est : " . number_format($total, 2) . " €<br>";

        // Insertion dans la table commandes
        $sql = "INSERT INTO commandes (id_plat, quantite, total, date_commande, etat, nom_client, telephone_client, email_client, adresse_client) 
                VALUES (:id_plat, :quantite, :total, :date_commande, 'livrée', :nom_client, :telephone_client, :email_client, :adresse_client)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_plat', $id_plat, PDO::PARAM_INT);
        $stmt->bindValue(':quantite', $quantite, PDO::PARAM_INT);
        $stmt->bindValue(':total', $total, PDO::PARAM_STR);
        $stmt->bindValue(':date_commande', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':nom_client', $first_name . ' ' . $last_name, PDO::PARAM_STR);
        $stmt->bindValue(':telephone_client', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':email_client', $email, PDO::PARAM_STR);
        $stmt->bindValue(':adresse_client', $address, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Commande enregistrée avec succès!";

            // Envoi d'email de confirmation
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'localhost';
                $mail->Port       = 1025;
                $mail->SMTPAuth   = false;

                // Destinataire et contenu
                $mail->setFrom('your-email@example.com', 'The District');
                $mail->addAddress($email, $first_name . ' ' . $last_name);
                $mail->isHTML(true);
                $mail->Subject = 'Confirmation de votre commande';
                $mail->Body    = '<h1>Merci pour votre commande!</h1>' .
                    '<p><strong>Nom:</strong>' . $first_name . '</p>' .
                    '<p><strong>Prenom:</strong>' . $last_name . '</p>' .
                    '<p><strong>Email:</strong>' . $email . '</p>' .
                    '<p>Votre commande pour ' . $quantite . ' plat(s) a été enregistrée avec succès.</p>' .
                    '<p>Adresse de livraison : ' . $address . '</p>' .
                    '<p><strong>Total:</strong> ' . number_format($total, 2) . ' €</p>';

                $mail->send();
                echo 'Le message de confirmation a été envoyé!';
            } catch (Exception $e) {
                echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
            }
        } else {
            echo "Erreur lors de l'enregistrement de la commande.";
        }
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
