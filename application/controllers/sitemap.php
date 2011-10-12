<?php

class Sitemap extends CI_Controller {

  private function _page($url, $last_change, $freq) {
    return <<<EOF
  <url>
    <loc>$url</loc>
    <lastmod>$last_change</lastmod>
    <changefreq>$freq</changefreq>
  </url>
EOF;
  }

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
      echo $this->_page("$page_url/$page", $change, 'monthly');
    }
    foreach($this->Docs_model->index() as $doc) {
      $change = date('Y-m-d', $this->Docs_model->change_time($doc));
      echo $this->_page("$docs_url/$doc", $change, 'monthly');
    }

    $this->load->model('Download_model');
    $current_release = $this->Download_model->current_release();
    $change = date('Y-m-d', strtotime($current_release['release_date']));
    echo $this->_page(site_url('download'), $change, 'monthly');
    echo $this->_page(site_url('download/archive'), $change, 'monthly');

    echo '</urlset>';

    
  }
}
