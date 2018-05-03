<?php
error_reporting(E_ALL);
require_once('./resize.php');
?><DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/style.css"/>
</head>
<body>
<div id="main">
<h1>My Photography Portfolio</h1>
<p>The following are a sample of some of my favorite photos I've taken
    to date. Click on any of the thumbnails to see the full-size image
    (and not cropped to a convenient square).</p>
<?php
$glob = glob('./img/full/*.jpg');
$photos = array();
foreach($glob as $name) {
    $photos[] = basename($name);
    if(!file_exists('./img/small/'.basename($name))) {
        $error = '';
        $img = resize($name, 250, 250, $error);
        if(!$img) {
            echo "<p class='error'>$error</p>\n";
        }
        imagejpeg($img, "./img/small/".basename($name), 90);
    }
}
foreach($photos as $photo) {
    echo <<<EOD
<div class="photo"><a href="/img/full/$photo"><img
src="/img/small/$photo" alt="thumbnail" title="click for full-sized image"></a></div>
EOD;
}
?>
</div>
</body>
</html>