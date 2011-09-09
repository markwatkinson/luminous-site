<?php
function assets_url($asset) {
  return rtrim(base_url(), '/') . '/assets/' . ltrim(htmlentities($asset), '/');
} 
