<?php foreach($pages as $p): ?>
<a href='<?= site_url('/docs/show/' . $p); ?>'><?= str_replace('-', ' ', $p) ?></a>
<?php endforeach; ?>
