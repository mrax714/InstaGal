<?php
$u = '';
$title = 'Instagal';
$ls = '';
if (isset($_GET['u']))
{
    $u = $_GET['u'];
    $ud = "img/$u";
    if (!is_dir($ud))
    {
        mkdir($ud, 0777, true);
        header("location: ./");
        exit;
    }
    $title = $u;
}
else
{

}

$media = 'jpg';
if (isset($_GET['media']))
{
    $media = $_GET['media'];
}

?>
<!DOCTYPE html>
<html>

	<head>
		<title><?php echo $title; ?></title>
		<script src="/js/jquery.min.js"></script>
		<script src="/js/kickstart.js"></script> <!-- KICKSTART -->
		<link rel="stylesheet" href="/css/kickstart.css" media="all" /> <!-- KICKSTART -->
		<link rel="stylesheet" href="/style.css" media="all" /> <!-- KICKSTART -->
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>

	<body>
<div class="grid">
<div class="col_12 center">
 <h1><a href='./'>Home</a> <?php if (isset($_GET['u']))
{
    echo " - " . $_GET['u'];
} ?></h1>

<div id="addUser" style="display:none;">
<form method='get'>
					<label for="fname">@</label>
					<input type="text" id="u" name="u" value="<?php echo $u; ?>" onfocus="this.value=''"/>
					<input type="submit" id="lname" name="lname" value="Scrape">
					<a class="button" href='./'>Reset</a>
					<p>Enter a tiktok profile link or username.</p>


				</form>
</div>

<?php
if (isset($_GET['u']))
{
    require ('func.php');

    $page = 1;
    if (isset($_GET['p']))
    {
        $page = $_GET['p'];
    }
    $u = $_GET['u'];
    $d = "img/$u";
    // (B) GET IMAGES IN GALLERY FOLDER
    $dir = __DIR__ . DIRECTORY_SEPARATOR . "$d" . DIRECTORY_SEPARATOR;

    // (C) OUTPUT IMAGES
    // (A) GET IMAGES & VIDEOS
    $media = glob("img/$u/*.$media", GLOB_BRACE);
    foreach ($media as $i)
    {
        //echo"$i<br>";
        preg_match('/img\/([0-9a-z\-\_\.]+)\/([0-9a-z\-\_]+)\.([0-9a-z]+)/i', $i, $match);

        if (isset($match[3]))
        {
            //print_r($match);
            $us = $match[1];
            $id = $match[2];
            $ex = $match[3];

            $sets[] = $i;

        }

    }
    $cnt = count($sets);
    $l = 10;
    $ps = ceil($cnt / $l);
    $start = ($page - 1) * $l;
    $stop = $start + $l;
    $ls .= "<a class='button' href='?u=$u&media=jpg'>Images</a> ";
    $ls .= "<a class='button' href='?u=$u&media=mp4'>Videos</a> ";
    $i = 1;
    while ($i <= $ps)
    {
        $ls .= "<a class='button' href='?u=$u&p=$i'>$i</a> ";
        $i++;
    }
    echo $ls;
?><div class="col_12"><?php
    $i = 0;
    // (B) OUTPUT HTML
    $media = 'jpg';
    if (isset($_GET['media']))
    {
        $media = $_GET['media'];
    }

    foreach ($sets as $id => $item)
    {
        if ($i > $start && $i < $stop)
        {
            echo "<div class='col_12 center'>";
            if ($media == 'jpg')
            {
                echo ("<a href='dl.php?u=$item'><img style='height:' title='' alt='' src='$item'><p></p></a>");
            }
            else
            {
                echo ("<video src='$item' controls width='100%'  preload='metadata'></video><p></p>");

            }
        }
        $i++;
    }
}
else
{
    foreach (glob('img/*/') as $i)
    {
        $ps = glob($i . '*profile_pic*');
        $u = basename($i);

        if (isset($ps[0]))
        {
            $p = $ps[0];
            printf("<div class='col_12 center'><a href='?u=$u'><img class='full-width' src='%s'><h1>$u</h1></a></div>", $p);
        }

    }
}
?></div>
<?php
echo $ls;
?>
<script>
$('video').bind('play', function (e) 
{
    var video = $('video');
    for(var i=0;i<video.length;i++)
    {
        if(video[i] != e.target)
        {
           video[i].pause();
        }
    }
});
</script>
