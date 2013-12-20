<html>
  <head>
     <title>Radio Power TV</title>
  </head>
  <body>
     <?php 
        echo '<p>Redireccionando al reproductor de PowerTV</p>';
        if (strlen($_SERVER['QUERY_STRING'])>0){
          header("Location: webroot/index.php?".$_SERVER['QUERY_STRING']);
        }else{
          header("Location: webroot/index.php");
        }
     ?>
  </body>
</html>