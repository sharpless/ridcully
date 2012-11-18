<h1>Index Controller</h1>
<p>This is what you can do for now.</p>

<?php foreach($menu as $val): ?>
<li><a href='<?=create_uri($val)?>'><?=$val?></a>
<?php endforeach; ?>	