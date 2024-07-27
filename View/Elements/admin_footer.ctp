<div class="content-page">
    
   <?php 
    try{
        $version = Configure::read('Version');
   
    }catch(Exception $e){
         
        }
    ?>
    <footer class="footer"> Copyright © <?php echo date('Y') ?> All Rights Reserved, Version:<?php echo @$version; ?> </footer>
 </div>
 