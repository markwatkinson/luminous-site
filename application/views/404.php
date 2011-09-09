<h1>Oops</h1>
<?php if (isset($message)):echo $message;
else: ?>
<!-- <div style='text-align:center'> -->
<h2 style='text-align:center'>The page you requested has been eaten by a giant duck</h2>
<img style='display:block; margin: 1em auto'
  src='<?= rtrim(base_url(), '/')?>/assets/img/duck.jpg'
  alt='quack'
  title='quack'>
<!-- </div> -->
<?php endif;?>
