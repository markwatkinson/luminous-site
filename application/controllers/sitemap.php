<?php

class Sitemap extends CI_Controller {


  function index() {
    header ('content-type: text/xml');
    $this->load->helper('url');
    $this->load->model('Docs_model');
    $this->load->model('Text_model');
    
    echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $docs_url = site_url('docs/show');
    $page_url = site_url('page');

    foreach($this->Text_model->index() as $page) {
      $change = date('Y-m-d', $this->Text_model->change_time($page));
      echo <<<EOF
  <url>
    <loc>$page_url/$page</loc>
    <lastmod>$change</lastmod>
    <changefreq>monthly</changefreq>
  </url>
EOF;
    }

    
    foreach($this->Docs_model->index() as $doc) {
      $change = date('Y-m-d', $this->Docs_model->change_time($doc));
      echo <<<EOF
  <url>
    <loc>$docs_url/$doc</loc>
    <lastmod>$change</lastmod>
    <changefreq>monthly</changefreq>
  </url>

EOF;
    }

    echo '</urlset>';

    
  }
}
