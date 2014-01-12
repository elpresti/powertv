<html>
  <head>
      <meta name="google-site-verification" content="UX_dPlVXATai6F1wD9p2vIGyCUhl_6_P_AnJnvc2UQ4" />
      <title>Radio Power TV</title>
      <meta name="description" content="PowerTV - Desde nuestro sitio podes miranos en HD las 24hs, videoclips, clima, móviles, bosque+playa=Pinamar!">
      <meta name="keywords" content="PowerTV,Radio Power Pinamar,Videoclips online,Musica en vivo, musica, Radio en vivo, Radio HD, Pinamar">
      <meta property="og:title" content="Radio Power Pinamar - PowerTV">
      <meta property="og:type" content="website">
      <meta property="og:url" content="http://www.powerhd.com.ar/">
      <meta property="og:image" content="http://radiopower.com.ar/images/facebookShareLogo.png">
      <meta property="og:description" content="Desde Pinamar y para todo el mundo podes vernos en HD y escucharnos las 24hs a traves de nuestro sitio. No te lo pierdas!">
      <meta property="og:site_name" content="PowerTV">
  </head>
  <body>
     <?php 
        echo '<p>Redireccionando al reproductor de PowerTV</p>';
        if (strlen($_SERVER['QUERY_STRING'])>0){
          header("Location: webroot/index.html?".$_SERVER['QUERY_STRING']);
        }else{
          header("Location: webroot/index.html");
        }
     ?>
  </body>
</html>