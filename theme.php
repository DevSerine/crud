<?php
@include 'config.php';

// Vérification de la connexion à la base de données
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Déclaration du tableau de messages
$message = [];

// Ajouter un thème
if (isset($_POST['submit'])) {
    $theme = $_POST['theme'];

    // Insertion du thème dans la base de données
    $insert_query = mysqli_query($conn, "INSERT INTO `theme`(nom) VALUES('$theme')");
    
    if ($insert_query) {
        $message[] = "Le thème '$theme' a été ajouté avec succès.";
    } else {
        $message[] = "Erreur lors de l'ajout du thème: " . mysqli_error($conn);
    }
}

// Supprimer un thème
if (isset($_POST['delete'])) {
    $theme_id = $_POST['theme_id'];

    // Suppression du thème sélectionné
    $delete_query = mysqli_query($conn, "DELETE FROM `theme` WHERE id = '$theme_id'");
    
    if ($delete_query) {
        $message[] = "Le thème a été supprimé avec succès.";
    } else {
        $message[] = "Erreur lors de la suppression du thème: " . mysqli_error($conn);
    }
}

// Sélectionner tous les thèmes
$select_themes = mysqli_query($conn, "SELECT * FROM `theme`");
if (!$select_themes) {
    die("Erreur de sélection des thèmes: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Thèmes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Style pour le formulaire */
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }

        form {
            margin: 20px 0;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form input[type="text"], form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        .message {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .message i {
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '<div class="message"><span>' . $msg . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
        }
    }
    ?>

    <!-- Formulaire pour ajouter un thème -->
    <section>
        <h2>Ajouter un Thème</h2>
        <form action="theme.php" method="POST">
            <input type="text" name="theme" required placeholder="Nom du thème">
            <button type="submit" name="submit">Ajouter</button>
        </form>
    </section>

    <!-- Formulaire pour supprimer un thème -->
    <section>
        <h2>Supprimer un Thème</h2>
        <form action="theme.php" method="POST">
            <select name="theme_id" required>
                <?php
                // Vérification si des thèmes existent et affichage
                if (mysqli_num_rows($select_themes) > 0) {
                    while ($row = mysqli_fetch_assoc($select_themes)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nom'] . "</option>";
                    }
                } else {
                    echo "<option disabled>Aucun thème disponible</option>";
                }
                ?>
            </select>
            <button type="submit" name="delete">Supprimer</button>
        </form>
    </section>

</div>

<script src="js/script.js"></script>
</body>
</html>
