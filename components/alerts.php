<?php

if(isset($success_msg)){
   foreach($success_msg as $success_msg){
      // echo '<script>swal("'.$success_msg.'", "", "success");</script>';
      echo '<script>';
      echo 'document.addEventListener("DOMContentLoaded", function() {';
      echo ' Swal.fire("'.$success_msg.'","", "success");';
      echo '});';
      echo '</script>';
   }
}

if(isset($warning_msg)){
   foreach($warning_msg as $warning_msg){
      // echo '<script>swal("'.$warning_msg.'", "", "warning");</script>';
      echo '<script>';
      echo 'document.addEventListener("DOMContentLoaded", function() {';
      echo ' Swal.fire("'.$warning_msg.'","", "warning");';
      echo '});';
      echo '</script>';
   }
}

if(isset($error_msg)){
   foreach($error_msg as $error_msg){
      // echo '<script>swal("'.$error_msg.'", "", "error");</script>';
      echo '<script>';
      echo 'document.addEventListener("DOMContentLoaded", function() {';
      echo ' Swal.fire("'.$error_msg.'","", "error");';
      echo '});';
      echo '</script>';
   }
}

if(isset($info_msg)){
   foreach($info_msg as $info_msg){
      // echo '<script>swal("'.$info_msg.'", "", "info");</script>';
      echo '<script>';
      echo 'document.addEventListener("DOMContentLoaded", function() {';
      echo ' Swal.fire("'.$info_msg.'","", "info");';
      echo '});';
      echo '</script>';
   }
}

?>