<?php
function insert_user($conn,$data){
  $sql = "INSERT INTO users (username , email, password_hash) VALUES (?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->execute($data);
}

function setting($conn,$data){
  $sql = "INSERT INTO setting (theme , email, password_hash) VALUES (?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->execute($data);
}

function get_all_users($conn){
  $sql = "SELECT * FROM users ";
  $stmt = $conn->prepare($sql);
  $stmt->execute([]);
if($stmt->rowCount() > 0){
  $users = $stmt->fetchAll();
}else  $users =0;
return $users;
}


function get_user_by_email($conn,$email){
  $sql = "SELECT * FROM users WHERE email =? ";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$email]);

if($stmt->rowCount() > 0){
  $user = $stmt->fetch();
}else  $user =0;
return $user;
}




?>