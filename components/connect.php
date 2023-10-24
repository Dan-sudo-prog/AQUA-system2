<?php
$db_host = 'localhost';
$db_name = 'aqua_db';
$db_user_name = 'root';
$db_user_pass = '';

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user_name, $db_user_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

function create_unique_id($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[mt_rand(0, $characters_length - 1)];
    }
    return $random_string;
}

function generate_unique_id() {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $characters_lenght = strlen($characters);
      $unique_id = '';
      for($i = 0; $i < 20; $i++){
         $unique_id .= $characters[mt_rand(0, $characters_lenght - 1)];
      }
      return $unique_id;
}

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : '';
?>
