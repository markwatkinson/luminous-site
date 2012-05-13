<h1>Embedding</h1>

<p>
An experimental API is provided to allow embedding the code snippets posted
here into other pages via JavaScript. It is experimental in the sense that
it may be changed or retracted entirely if it becomes a big drain on resources,
so you shouldn't really rely on it yet.

<p>
The API uses JSON. It also supports JSONP, which as you may or may not be aware,
means that you embed on your page a &lt;script&gt; element the API returns,
which executes a function you specify. The function is then passed a JSON object,
and it is up to the function exactly what is performed. To use JSONP, append
'?callback=:yourfunction' to the request.

<p>
To call the API, use the following url:
<pre>http://luminous.asgaard.co.uk/index.php/demo/embed/:id/:theme.css</pre>

or JSONP (where you must first define a :something function):
<pre>&lt;script type="text/javascript"
  src="http://luminous.asgaard.co.uk/index.php/demo/embed/:id/:theme.css?callback=:something"&gt;
&lt;/script&gt;</pre>

yes this has the unfortunate side effect that it looks like you're fetching
a .css file, but that's not the case.

<p>
The JSON received will look something like this:

<?= luminous::highlight('json', '{
  language : "Python",
  code : "<the highlighted code, as HTML>",
  layout: "<a path to the layout stylesheet>",
  theme : "<a path to the theme stylesheet>"
}')?>


<p>
Putting it all together, an example usage (with jQuery) is thus:

<?= luminous::highlight('html', ltrim('
<!DOCTYPE html>
<html>
  <head>
    <script>
      $(document).ready(function() {
        $.getJSON("http://luminous.asgaard.co.uk/index.php/demo/embed/12/kimono.css",
          function(data) {
            $("head").append($("<link rel=\'stylesheet\' type=\'text/css\'>").attr("href", data.layout));
            $("head").append($("<link rel=\'stylesheet\' type=\'text/css\'>").attr("href", data.theme));
            $("body").append("Language: " + data.language);
            $("body").append($(data.code));
        });
      });
    </script>
  </head>
  <body>
  </body>
</html>'))?>
