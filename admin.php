<?php

@include 'config.php';

if(isset($_POST['add_formation'])){
    $f_nom = mysqli_real_escape_string($conn, $_POST['f_nom']);
    $f_theme_nom = mysqli_real_escape_string($conn, $_POST['f_theme_nom']);
    $f_description = mysqli_real_escape_string($conn, $_POST['f_description']);
    $f_duree = mysqli_real_escape_string($conn, $_POST['f_duree']);
    $f_code = mysqli_real_escape_string($conn, $_POST['f_code']);

    $f_image = $_FILES['f_image']['name'];
    $f_image_tmp_name = $_FILES['f_image']['tmp_name'];
    $f_image_folder = 'uploaded_img/' . $f_image;

    if (!empty($f_image) && move_uploaded_file($f_image_tmp_name, $f_image_folder)) {
        $insert_query = mysqli_query($conn, "INSERT INTO `formation`(nom, theme_nom, description, image, duree, code) 
                                             VALUES('$f_nom', '$f_theme_nom', '$f_description', '$f_image', '$f_duree', '$f_code')") 
                                             or die('Query failed: ' . mysqli_error($conn));

        if($insert_query){
            $message[] = 'Formation ajoutée avec succès.';
        } else {
            $message[] = 'Impossible d\'ajouter la formation.';
        }
    } else {
        $message[] = 'Erreur lors du téléchargement de l\'image.';
    }
}


if(isset($_GET['delete'])){
    $delete_id = intval($_GET['delete']);
    $delete_query = mysqli_query($conn, "DELETE FROM `formation` WHERE id = $delete_id") or die('Query failed: ' . mysqli_error($conn));

    if($delete_query){
        $message[] = 'Formation supprimée avec succès.';
    } else {
        $message[] = 'Impossible de supprimer la formation.';
    }
    header('location:admin.php');
    exit;
}

if(isset($_POST['update_formation'])){
    $update_f_id = intval($_POST['update_f_id']);
    $update_f_nom = mysqli_real_escape_string($conn, $_POST['update_f_nom']);
    $update_f_theme_nom = mysqli_real_escape_string($conn, $_POST['update_f_theme_nom']);
    $update_f_description = mysqli_real_escape_string($conn, $_POST['update_f_description']);
    $update_f_duree = mysqli_real_escape_string($conn, $_POST['update_f_duree']);
    $update_f_code = mysqli_real_escape_string($conn, $_POST['update_f_code']);

    $update_f_image = $_FILES['update_f_image']['name'];
    $update_f_image_tmp_name = $_FILES['update_f_image']['tmp_name'];
    $update_f_image_folder = 'uploaded_img/' . $update_f_image;

    if (!empty($update_f_image) && move_uploaded_file($update_f_image_tmp_name, $update_f_image_folder)) {
        $update_query = mysqli_query($conn, "UPDATE `formation` SET nom = '$update_f_nom', theme_nom = '$update_f_theme_nom', description = '$update_f_description', image = '$update_f_image', duree = '$update_f_duree', code = '$update_f_code' WHERE id = $update_f_id") or die('Query failed: ' . mysqli_error($conn));

        if($update_query){
            $message[] = 'Formation mise à jour avec succès.';
        } else {
            $message[] = 'Impossible de mettre à jour la formation.';
        }
    } else {
        $message[] = 'Erreur lors du téléchargement de l\'image.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php if (isset($message)): ?>
    <?php foreach ($message as $msg): ?>
        <div class="message">
            <span><?= $msg; ?></span>
            <i class="fas fa-times" onclick="this.parentElement.style.display = 'none';"></i>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'header.php'; ?>

<div class="container">

    <section>
    <form action="" method="post" class="add-product-form" enctype="multipart/form-data">
    <h3>Ajouter une nouvelle formation</h3>
    <input type="text" name="f_nom" placeholder="Nom de la formation" class="box" required>
    <select name="f_theme_nom" class="box" required>
        <option value="">Sélectionner un thème</option>
        <?php
        $themes_query = mysqli_query($conn, "SELECT * FROM theme");
        while ($theme = mysqli_fetch_assoc($themes_query)) {
            echo "<option value='" . htmlspecialchars($theme['nom']) . "'>" . htmlspecialchars($theme['nom']) . "</option>";
        }
        ?>
    </select>
    <textarea name="f_description" placeholder="Description de la formation" class="box" required></textarea>
    <input type="file" name="f_image" accept="image/png, image/jpg, image/jpeg" class="box" required>
    <input type="text" name="f_duree" class="box" placeholder="Durée (ex : 5 semaines)" required>
    <input type="text" name="f_code" class="box" placeholder="Code de la formation" required>
    <input type="submit" value="Ajouter la formation" name="add_formation" class="btn">
</form>

    </section>

    <section class="display-product-table">
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Nom</th>
            <th>Thème</th>
            <th>Description</th>
            <th>Durée</th>
            <th>Code</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $select_products = mysqli_query($conn, "SELECT * FROM formation") or die('Query failed: ' . mysqli_error($conn));
    if (mysqli_num_rows($select_products) > 0) {
        while ($row = mysqli_fetch_assoc($select_products)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td><img src='uploaded_img/" . htmlspecialchars($row['image']) . "' height='100' alt=''></td>
                    <td>" . htmlspecialchars($row['nom']) . "</td>
                    <td>" . htmlspecialchars($row['theme_nom']) . "</td>
                    <td>" . htmlspecialchars($row['description']) . "</td>
                    <td>" . htmlspecialchars($row['duree']) . "</td>
                    <td>" . htmlspecialchars($row['code']) . "</td>
                    <td>
                        <a href='admin.php?delete=" . $row['id'] . "' class='delete-btn' onclick=\"return confirm('Voulez-vous vraiment supprimer ?');\"><i class='fas fa-trash'></i> Supprimer</a>
                        <a href='admin.php?edit=" . $row['id'] . "' class='option-btn'><i class='fas fa-edit'></i> Modifier</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align: center;'>Aucune formation trouvée.</td></tr>";
    }
    ?>
    </tbody>
</table>

</section>

<?php if(isset($_GET['edit'])): ?>
        <?php
        $edit_id = intval($_GET['edit']);
        $edit_query = mysqli_query($conn, "SELECT * FROM formation WHERE id = $edit_id");
        $fetch_edit = mysqli_fetch_assoc($edit_query);
        ?>
     <section class="edit-form-container" style="display: flex; flex-direction: column; max-height: 500px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
    <form action="" method="post" enctype="multipart/form-data">
        <img src="uploaded_img/<?= htmlspecialchars($fetch_edit['image']); ?>" height="100" alt="">
        <input type="hidden" name="update_f_id" value="<?= $fetch_edit['id']; ?>">
        <input type="text" class="box" required name="update_f_nom" value="<?= htmlspecialchars($fetch_edit['nom']); ?>">
        <select name="update_f_theme_nom" class="box" required>
    <option value="">Sélectionner un thème</option>
    <?php
    $themes_query = mysqli_query($conn, "SELECT * FROM theme");
    while ($theme = mysqli_fetch_assoc($themes_query)) {
        // Vérifiez si le thème actuel correspond au thème sélectionné
        $selected = ($fetch_edit['theme_nom'] === $theme['nom']) ? 'selected' : '';
        echo "<option value='" . htmlspecialchars($theme['nom']) . "' $selected>" . htmlspecialchars($theme['nom']) . "</option>";
    }
    ?>
</select>
<textarea name="update_f_description" class="box" required><?= htmlspecialchars($fetch_edit['description']); ?></textarea>
<input type="file" class="box" required name="update_f_image" accept="image/png, image/jpg, image/jpeg">
<input type="text" class="box" required name="update_f_duree" value="<?= htmlspecialchars($fetch_edit['duree']); ?>">
<input type="text" class="box" required name="update_f_code" value="<?= htmlspecialchars($fetch_edit['code']); ?>" placeholder="Code de la formation">
<input type="submit" value="Mettre à jour la formation" name="update_formation" class="btn">
<a href="admin.php" class="option-btn">Annuler</a>

    </form>
</section>

    <?php endif; ?>

    

</div>

<script src="js/script.js"></script>

</body>
</html>
