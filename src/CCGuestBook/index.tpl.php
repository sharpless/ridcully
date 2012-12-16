<h1>Gästbook</h1>
 <form action="<?=$formAction?>" method="post">
   <label for="message">Meddelande:<br>
    <textarea id="message" cols="40" rows="10" name="message"></textarea>
   </label><br>
   <label for="author">Författare:<br>
    <input type="text" id="author" name="author">
   </label><br>
   <label for="password">Vad är tjugo minus sju (skriv med siffror)?<br>
    <input type="text" id="password" name="password">
   </label><br>
   <input type="submit" name="submit" id="submit" value="Skicka">
   <input type="submit" name="clear" id="clear" value="Rensa">
   <input type="submit" name="create" id="create" value="Skapa tabell">
 </form>
<?php foreach ($posts as $post): ?>
<article class="post">
  <p class="message"><?=$post["post_message"]?></p>
  <p class="author">Skrivet av: <?=$post["post_author"]?> @ <?=$post["post_time"]?></p>
</article>
<?php endforeach; ?>
