<?php
if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['confirm_password'])){
  include "../dbConfig.php";
  include "./DB_FUNCTION/User.php";

  // input data
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  
 if( $password === $confirm_password ){
   $data = array($username,$email,$password);
   insert_user($conn,$data);
   header("Location:../home.html");
   exit();
 }
}
?>