<h3>Controllers and methods</h3>
<p>This is what you can do</p>

<?php foreach($menu as $val): ?>
<li><a href='<?=create_uri($val)?>'><?=$val?></a>
<?php endforeach; ?>	