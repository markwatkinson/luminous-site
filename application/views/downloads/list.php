<?=
  breadcrumb(array(
    array('Main', site_url('download')),
    array('Archive', site_url('download/archive'))
  ), 'Download');
  ?>
<h1> Download Archive </h1>

<p>
<img class='middle left' src='<?=assets_url('img/archive32.png')?>' alt='Archive'>
Old versions and alternate formats.

<table class='downloads old-downloads'>
  <tr class='header'>
    <td>Date</td>
    <td>Filename</td>
    <td>Size (KiB)</td>
    <td><span class='help-hint hash-help'>SHA1</span></td>
  </tr>

  <?php foreach($this->Download_model->get_current() as $release): ?>
    <?php foreach(download_sortfiles($release) as $f) : ?>
      <tr>
        <td> <?=pretty_date($release['release_date'])?> </td>
        <td> <a href='<?=download_href($f)?>'><?=$f['filename']?></a> </td>
        <td> <?=(int)($f['size']/1024)?></td>
        <td> <?=$f['sha1']?> </td>
      </tr>
    <?php endforeach;?>
  <?php endforeach;?>
</table>
