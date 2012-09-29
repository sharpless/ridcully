<!doctype html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title><?=$r_title?></title>
  <link rel="stylesheet" href="<?=$r_stylesheet?>">
</head>
<body>
  <div id="header">
    <?=$r_header?>
  </div>
  <div id="main" role="main">
    <?=$r_main?>
    <?=get_debug()?>
  </div>
  <div id="footer">
    <?=$r_footer?>
  </div>
</body>
</html>