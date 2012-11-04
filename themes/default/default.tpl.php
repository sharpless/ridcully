<!doctype html>
<html lang="sv">
<head>
  <meta charset="utf-8">
  <title><?=$title?></title>
  <link rel="stylesheet" href="<?=$stylesheet?>">
</head>
<body>
  <div id="header">
    <?=$header?>
  </div>
  <div id="main" role="main">
    <?=get_messages_from_session()?>
    <?=$main?>
    <?=render_views()?>
    <?=get_debug()?>
  </div>
  <div id="footer">
    <?=$footer?>
  </div>
</body>
</html>