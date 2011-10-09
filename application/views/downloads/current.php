<h1>Download Luminous</h1>

<h2><span>Current Version</span></h2>
The latest stable release is <?= $release['release_number'] ?>,
released <?= pretty_date($release['release_date']) ?>. <a href='<?=assets_url('luminous/CHANGES.markdown')?>' target=_blank>Changelog</a>.
<p>
<div class='centre'>
<?php
$file = download_get_file($release, 'zip');

echo button('Download Luminous ' . $release['release_number'] , download_href($file),
  '', assets_url('img/download.png'))?>
<p>
<strong>SHA1</strong>: <span class='help-hint hash-help'><?=$file['sha1'];?> </span>
</div>

<img class='middle left' src='<?=assets_url('img/system-help32.png')?>' alt='Guide'>

<strong>What now?</strong> See the <a href='<?=site_url('docs/show/index')?>'>get started guide</a>.

<h2>CodeIgniter Syntax Highlighting Hook</h2>

Looking for quality syntax highlighting for CodeIgniter? Look no further:
<a href='<?=site_url('download/get/ci-syntax-highlight.zip')?>'>ci-syntax-highlight.zip</a>.

See <a href='<?=site_url('page/codeigniter-syntax-highlight-hook')?>'>usage instructions</a>.

<h2>Old Versions and Alternate Formats</h2>

<img class='middle left' src='<?=assets_url('img/archive32.png')?>' alt='Archive'>

Check out the <a href='<?=site_url('download/archive')?>' >archive</a>.
