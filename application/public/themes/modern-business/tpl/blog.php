<?php
   $filename = "http://www.noticias.com/rss/barcelona";
   header("Content-type:text/xml");
   readfile ($filename);
?>