<?php

   if(isset($_POST)){
      if(isset($_POST['cssEditor'])){
         $file = $_POST['fileLocation'];
         file_put_contents($file, $_POST['cssEditor']);
         header("Location: " . $_SERVER["HTTP_REFERER"]);
         die();
      }
}