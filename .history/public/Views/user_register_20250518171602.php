
<?php
include '/../config/database.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = "L'email existe déjà !";
   }else{
      if($pass != $cpass){
         $message[] = 'Le mot de passe de confirmation ne correspond pas !';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(username, email, password, created_at) VALUES(?,?,?, NOW())");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'Inscription réussie, veuillez vous connecter maintenant !';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inscription</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="auth.css">
</head>
<body>
   

<?php include 'user_message.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Inscription</h3>
      <input type="text" name="name" required placeholder="Nom d'utilisateur" maxlength="20" class="box">
      <input type="email" name="email" required placeholder="Email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirmer le mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="S'inscrire" class="btn" name="submit">
      <p>Déjà un compte ?</p>
      <a href="user_login.php" class="option-btn">Se connecter</a>
   </form>
</section>

<!-- <?php include 'footer.php'; ?> -->
<script src="script.js"></script>
</body>
</html>