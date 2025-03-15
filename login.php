<?php
// Start session to store user data after login
session_start();

if(isset($_POST['password']) && isset($_POST['email']) ){
  include "../dbConfig.php";
  include "./DB_FUNCTION/User.php";


  $email = $_POST['email'];
  $password = $_POST['password'];
  $user = get_user_by_email($conn,$_POST['email']);
  
 if( $password == $user['password_hash'] && $email == $user['email']){
   header("Location:../home.html");
   exit();
 }else{}
 
 $rrro = "Incorrect Email or password";
  header("Location:../login.php?error= $rrro");
   exit();
}
?>
