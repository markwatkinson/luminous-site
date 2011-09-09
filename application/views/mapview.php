<!-- Start map view //-->
<h1><?=isset($title)?$title:'Map'?></h1>
<?php

function clean_name($name) {
  return ucwords(str_replace('-', ' ', $name));
}
function show_map($node, $prefix, $indent=0) {
  $url = site_url($prefix . $node['name']);
  $name = clean_name($node['name']);
  echo <<<EOF
<div style='margin-left:{$indent}em;'>
  <a href='$url'>$name</a>
</div>

EOF;

  foreach($node['children'] as $c) {
    show_map($c, $prefix, $indent+2);
  }
}
foreach($map as $m) 
  show_map($m, $url_prefix);
?>