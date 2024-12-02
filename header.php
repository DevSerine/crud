<header class="header">

   <div class="flex">

      <a href="admin.php" class="logo">Admin-UMC² </a>

      <nav class="navbar">
         <a href="admin.php">add formations</a>
         <a href="theme.php">ajouter un theme</a>
         <a href="products.php">Demandes</a>
      </nav>

      <?php
      
      $select_rows = mysqli_query($conn, "SELECT * FROM `cart`") or die('query failed');
      $row_count = mysqli_num_rows($select_rows);

      ?>

      <a href="cart.php" class="cart">Nb Demandes <span><?php echo $row_count; ?></span> </a>

      <div id="menu-btn" class="fas fa-bars"></div>

   </div>

</header>