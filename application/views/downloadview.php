<?php
// urgh let's have a few helpers
function href($f){return site_url('download/get/' . $f['filename']);}
function size($f){return round($f['size']/1024);}
function sortfunc($a, $b){ return strcmp($a['format'], $b['format']);}
function sortfiles($f){ usort($f['files'], 'sortfunc'); return $f['files'];}
?>
<!-- Download view //-->
<h1><span>Download Luminous</span></h1>

<?php if(empty($releases)): ?>
<h2><span>Oops!</span></h2>
A server-side error has occurred here and I can't give you a list of downloads. Please visit <a href='https://github.com/markwatkinson/luminous/archives/master'>GitHub's list of downloads</a> to download a package.
<?php else:
  $release = $releases[0];?>
<h2><span>Current Version</span></h2>
The latest stable release is <?= $release['release_number'] ?>, 
released <?= $release['release_date'] ?>. <a href='<?=assets_url('luminous/CHANGES.markdown')?>'>Changelog</a>.
<p>
<table class='downloads'>
<tr class='header'>
  <td><span class='format-help'>Format</span></td>
  <td></td>
  <td>File size (KiB)</td>
  <td><span class='hash-help'>SHA1 checksum</span></td>
</tr>

<?php

$files = sortfiles($release);
foreach($files as $f): ?>
<tr>
  <td><?= $f['format']?></td>
  <td>
    <strong>
      <a href='<?=href($f)?>' title='Download current release (<?=$f['format']?>)'><?=$f['filename']?></a>
    </strong>
  </td> 
  <td> <?=size($f)?> </td>
  <td> <?= $f['sha1']?> </td>
</tr>
<?php endforeach;?>
</table>

<p>
You've downloaded a distribution: what now? Visit the <a href='<?= site_url('/docs/show/index')?>'>documentation</a>.


<?php if(count($releases) > 1): ?>
<h2><span>Old Versions</span></h2>

<table class='downloads old-downloads'>
<tr class='header'>
  <td>Version</td>
  <td><span class='format-help'>Format</span></td>
  <td></td>
  <td>File size (KiB)</td>
  <td><span class='hash-help'>SHA1 checksum</span></td>
</tr>

<?php foreach($releases as $i=>$release): 
  if ($i === 0) continue; ?>
  <tr class='sub-header'>
    <td><?= $release['release_number']?> - <?= $release['release_date'] ?></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>

<?php $files = sortfiles($release);
foreach($files as $i=>$f): ?>
  <tr> 
    <td></td>
    <td><?= $f['format']?></td>
    <td> <a href='<?=href($f)?>'><?=$f['filename']?></a></td> 
    <td> <?=size($f)?> </td>
    <td> <?= $f['sha1']?> </td>
  </tr>
<?php endforeach; ?>
<?php endforeach; ?>
</table>
<?php endif; ?>
<p>Releases prior to 0.6.0 can be found on the old <a href='http://code.google.com/p/luminous'>Google Code page</a>.
<?php endif; ?>

<h2><span>Development Versions</span></h2>
Looking for the development version? Check out our Git repository on <a href='https://github.com/markwatkinson/luminous/'>GitHub</a>.


<script type='text/javascript'>
$(document).ready(function() {
  $('.format-help, .hash-help').each(function() {
    var $helper = $(this);
    var $el = $('<a>').addClass('help-hint').text('?');
    $helper.append(' ');
    $helper.append($el);
    $el.click(function() {
      var msg = '', title = '';
      if ($helper.hasClass('format-help')) {
        msg = 'The different formats contain the same data, but one may be \
more convenient to you than another. The compressed tarballs generally achieve \
smaller file sizes than the zip archives, but Windows users may not have \
software installed to handle tarballs.';
        title = 'Package Formats';
      } else if ($helper.hasClass('hash-help')) {
        title = 'Checksums';
        msg = 'Checksums can be used to verify the download you have received \
is not corrupted and has not been tampered with.';
      }
      $.jKnotify(msg, {title: title, icon:'<?=assets_url('/img/system-help.png')?>'});
    });
  });
});
</script>
