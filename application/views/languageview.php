<h1>Supported Languages</h1>
<h2>Highlight-able languages</h2>
<p> Is your language not listed here? Luminous is easy to extend and writing 
your own syntax highlighting rules is straightforward for many languages. 
See the documentation on 
<a href='<?= site_url('docs/show/Writing-a-language-scanner') ?>'>writing 
a scanner</a>.
<table class='languages'>
  <tr class='header'><td></td><td>Language</td>
  <td id='code-helper'>Codes</td></tr>
  <?php $i=0; foreach($content as $language=>$codes): ?>
    <tr>
      <td><?=''?></td><td><?=$language?></td><td><?=join(', ',$codes)?></td>
    </tr>
<?php endforeach;?>
</table>

<h2>Output formats</h2>
<table class='languages' style='width:80%'>
  <tr class='header'>
    <td>Formatter Code</td>
    <td>Description</td>
  </tr>
<?php foreach(
  array(
    'html' => 'HTML widget, highlighted code is wrapped in a <div class=\'luminous\'>, syntax elements are identified using CSS classes, and a stylesheet must be included in your page\'s <head>',
    'html-full' => 'Full HTML5 document including embedded CSS',
    'html-inline' => 'The same as \'html\', but highlighted code is wrapped in an inline-block element',
    'latex' => 'Full LaTeX document'
  ) as $code=>$desc): ?>
  <tr>
    <td><?=$code?></td> <td><?=htmlspecialchars($desc);?> </td>
  </tr>
<?php endforeach; ?>
</table>
