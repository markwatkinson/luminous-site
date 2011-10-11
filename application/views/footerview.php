</div> <!-- close page //-->
  </div> <!-- close content //-->

  <!-- footer //-->
  <div class='footer'>
    <?php if(isset($modified)): ?>
    Last modified: <?=pretty_date($modified)?>
    <?php endif ?>
    <div id='license'>
    Luminous is a PHP syntax highlighter and is free software (GPLv3). <a href='https://github.com/markwatkinson/luminous' target=_blank>Get involved!</a>
    </div>
    <div id='ci'>
    Site is fuelled by <a href='http://www.codeigniter.com' target=_blank>CodeIgniter</a>. <a href='http://validator.w3.org/check?uri=<?= urlencode(current_url()); ?>' target=_blank>
        Valid HTML5</a>. Probably. Most pages should be.
    </div>
    <div id='stats'>
    Loaded in: {elapsed_time} of your earth-seconds, and {memory_usage}
    </div>
  </div>
</body>
</html>
