$(document).ready(function() {
  $('.hash-help').click(function() {
    var title = 'Checksums',
      msg = 'Checksums can be used to verify the download you have received '
        + 'is not corrupted and has not been tampered with.',
      img = BASE_URL + 'assets/img/system-help.png'
    $.jKnotify(msg, {title: title, icon: img});  
  });
});