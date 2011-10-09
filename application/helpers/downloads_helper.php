<?php

function download_href($f){
  return site_url('download/get/' . $f['filename']);
}
function download_size($f){
  return round($f['size']/1024);
}
function download_sortfunc($a, $b){
  return strcmp($a['format'], $b['format']);
}
function download_sortfiles($f){
  usort($f['files'], 'download_sortfunc'); return $f['files'];
}

function download_get_file($download, $format) {
  foreach($download['files'] as $f) {
    if ($f['format'] === $format) return $f;
  }
  return null;
}

function download_format($releases) {
  $str = '<dl class="downloads">';
//   return <<<EOF

}