<h1>Supported Languages And Output Formats</h1>
<!-- <h2>Contents</h2> -->
<!--<ul>
  <li> <a href='#languages'>Languages</a> </li>
  <li> <a href='#formats'>Output Formats</a> </li>
</ul>-->


<h2 id='languages'>Highlightable languages</h2>

<p>The following list shows which languages Luminous supports, and which
language-codes Luminous will recognise as specifying the language

<p><strong>NOTE</strong>: This list is generated from a recent development
copy of Luminous, therefore it may <em>occasionally</em> contain a language
which is not yet in a stable release.
</p>

<dl class='languages'>
<?php foreach(luminous::scanners() as $language=>$codes): ?>
  <dt><?=$language?></dt>
  <dd><?=join(', ',$codes)?></dd>
<?php endforeach;?>
</dl>


<p> Is your language not listed here? Luminous is easy to extend and writing
your own syntax highlighting rules is straightforward for many languages.
See the documentation on
<a href='<?= site_url('docs/show/Writing-a-language-scanner') ?>'>writing
a scanner</a>.

<h2 id='formats'>Output formats</h2>
The following strings can be specified as an output format.
<?php luminous::set('format', 'html-inline'); ?>
<dl class='formats'>
  <dt>html</dt>
  <dd>A HTML widget - highlighted code is wrapped in a
  <?= luminous::highlight('html', '<div class=\'luminous\'> ... </div>'); ?>
  element. Syntax elements are labelled using CSS classes and a styling theme
  must be included in your page.
  <dt>html-full</dt>
  <dd>The output is a full HTML5 document which includes embedded CSS.</dd>
  <dt>html-inline</dt>
  <dd>The output is the same as 'html', but it is wrapped in an inline-block element.</dd>
  <dt>latex</dt>
  <dd>The output is a full LaTeX document with embedded syntax highlighting.</dd>
</dl>

<?php luminous::set('format', 'html'); ?>