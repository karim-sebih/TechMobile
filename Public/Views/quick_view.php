<?php
include '../config/database.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

// include 'wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="auth.css">
</head>

<body>

   <!-- <?php include 'user_header.php'; ?> -->

   <section class="quick-view">
      <h1 class="heading">Aperçu</h1>

      <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE product_id = ?");
      $select_products->execute([$pid]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <form action="" method="post" class="box">
               <input type="hidden" name="pid" value="<?= $fetch_product['product_id']; ?>">
               <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_product['product_name']); ?>">
               <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
               <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_product['image_url']); ?>">
               <div class="row">
                  <div class="image-container">
                     <div class="main-image">
                        <img src="<?= htmlspecialchars($fetch_product['image_url']); ?>" alt="<?= htmlspecialchars($fetch_product['product_name']); ?>">
                     </div>
                     <!-- Removed sub-images since we now have single image per product -->
                  </div>
                  <div class="content">
                     <div class="name"><?= htmlspecialchars($fetch_product['product_name']); ?></div>
                     <div class="flex">
                        <div class="price"><span>€</span><?= $fetch_product['price']; ?><span></span></div>
                        <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                     </div>
                     <div class="details"><?= htmlspecialchars($fetch_product['description'] ?? 'No description available'); ?></div>
                     <div class="flex-btn">
                        <input type="submit" value="Ajouter au panier" class="btn" name="add_to_cart">
                        <input class="option-btn" type="submit" name="add_to_wishlist" value="Ajouter aux favoris">
                     </div>
                  </div>
               </div>
            </form>

            <section class="quick-view">
               <h1 class="heading">Détails du téléphone</h1>
               <form action="cart.php" method="post">
                  <div class="phone-details">
                     <!-- <div class="heading">Phone Details</div> -->
                     <div class="details">Autonomie : <?= htmlspecialchars($fetch_product['battery_capacity'] ?? 'No description available'); ?></div>
                         <div class="details">Appareil photo : <?= htmlspecialchars($fetch_product['camera_details'] ?? 'No description available'); ?></div>
                     <div class="details">Connectivité : <?= htmlspecialchars($fetch_product['connectivity'] ?? 'No description available'); ?></div>
                     <div class="details">Système d'exploitation : <?= htmlspecialchars($fetch_product['operating_system'] ?? 'No description available'); ?></div>
                     <div class="details">Taille de l'écran : <?= htmlspecialchars($fetch_product['display_size'] ?? 'No description available'); ?></div>
                     <div class="details">Résolution : <?= htmlspecialchars($fetch_product['resolution'] ?? 'No description available'); ?></div>
                     <div class="details">Capacité de stockage : <?= htmlspecialchars($fetch_product['storage_capacity'] ?? 'No description available'); ?></div>
                     <div class="details">Mémoire RAM : <?= htmlspecialchars($fetch_product['ram'] ?? 'No description available'); ?></div>
                     <div class="details">Poids : <?= htmlspecialchars($fetch_product['weight_grams'] ?? 'No description available'); ?></div>
                     <div class="details">Dimensions : <?= htmlspecialchars($fetch_product['dimensions'] ?? 'No description available'); ?></div>
                  </div>
               </form>
            </section>


            <section class="home-products">
               <h1 class="heading">Derniers produits</h1>
               <div class="swiper products-slider">
                  <div class="swiper-wrapper">
                     <?php
                     $select_products = $conn->prepare("SELECT * FROM `products` WHERE is_active = 1 ORDER BY created_at DESC LIMIT 6");
                     $select_products->execute();
                     if ($select_products->rowCount() > 0) {
                        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                     ?>
                           <form action="" method="post" class="swiper-slide slide">
                              <input type="hidden" name="pid" value="<?= $fetch_product['product_id']; ?>">
                              <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_product['product_name']); ?>">
                              <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                              <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_product['image_url']); ?>">
                              <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                              <a href="quick_view.php?pid=<?= $fetch_product['product_id']; ?>" class="fas fa-eye"></a>
                              <img src="<?= htmlspecialchars($fetch_product['image_url']); ?>" alt="<?= htmlspecialchars($fetch_product['product_name']); ?>">
                              <div class="name"><?= htmlspecialchars($fetch_product['product_name']); ?></div>
                              <div class="flex">
                                 <div class="price"><span>€</span><?= $fetch_product['price']; ?><span></span></div>
                                 <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                              </div>
                              <input type="submit" value="Ajouter au panier" class="btn" name="add_to_cart">
                           </form>
                     <?php
                        }
                     } else {
                        echo '<p class="empty">no products added yet!</p>';
                     }
                     ?>
                  </div>
                  <div class="swiper-pagination"></div>
               </div>
            </section>



      <?php
         }
      } else {
         echo '<p class="empty">no products found!</p>';
      }
      ?>
   </section>

   <!-- <?php include 'footer.php'; ?> -->
   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
   <script src="script.js"></script>

   <script>
      var swiper = new Swiper(".featured-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });

      var swiper = new Swiper(".category-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 2,
            },
            650: {
               slidesPerView: 3,
            },
            768: {
               slidesPerView: 4,
            },
            1024: {
               slidesPerView: 5,
            },
         },
      });

      var swiper = new Swiper(".products-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            550: {
               slidesPerView: 2,
            },
            768: {
               slidesPerView: 2,
            },
            1024: {
               slidesPerView: 3,
            },
         },
      });
   </script>


</body>

</html>