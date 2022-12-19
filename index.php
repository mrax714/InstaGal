<?
if (isset($_GET['u']))
{
    $u = $_GET['u'];
    $ud = "img/$u";
    if (!is_dir($ud))
    {
        mkdir($ud, 0777, true);
        header("location: ./");
        //exit;
        
    }

    $title = $u;
}
else
{
    $title = 'Instagal';
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
 <h1><a href='./'>Users</a><?php if (isset($_GET['u']))
{
    echo " - " . $_GET['u'];
} ?></h1>


<form method='get'>
					<label for="fname">@</label>
					<input type="text" id="u" name="u" value="<?php echo $u; ?>" onfocus="this.value=''"/>
					<input type="submit" id="lname" name="lname" value="Scrape">
					<a class="button" href='./'>Reset</a>
					<p>Enter a tiktok profile link or username.</p>


				</form>


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
    $media = glob("img/$u/*.{jpg,jpeg,gif,png,mp4,json,txt}", GLOB_BRACE);
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
            if (in_array($ex, array(
                'jpg',
                'mp4'
            )))
            {
                $sets[$us][$id][$ex] = $i;
            }
        }
    }
    $cnt = count($sets[$us]);
    $l = 10;
    $ps = ceil($cnt / $l);
    $start = ($page - 1) * $l;
    $stop = $start + $l;
    $ls = '';
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
    foreach ($sets[$u] as $id => $item)
    {
        if ($i > $start && $i < $stop)
        {
            echo "<div class='col_12 center'>";

            foreach ($item as $k2 => $v2)
            {
                //echo"$k2=$v2";
                $$k2 = $v2;
            }
            $txt = str_replace(array(
                '.mp4',
                '.jpeg',
                '.jpg'
            ) , '.txt', $jpg);
            $a = array();
            if (isset($json))
            {
                $a = jarray($json);
            }

            $t = @file_get_contents($txt);
            if (isset($item['mp4']))
            {
                echo ("<video poster='$jpg' src='$mp4' controls width='100%'  preload='metadata'></video><p>$t</p>");

            }
            elseif (isset($item['jpg']))
            {
            	$pic='';
                if (isset($a['node']['display_resources']))
                {
                    $pic = end($a['node']['display_resources'])['src'];
                }
                $full = $pic;
                $full = $jpg;
                echo ("<div class='col_12 center'><a href='dl.php?u=$full'><img style='height:' title='$t' alt='$t' src='$full'><p>$t</p></a>");
            }

            echo '<pre class="left">';

            echo '</pre>';
            echo '</div>';

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
        $p = $ps[0];
        if (!file_exists($p))
        {
            $p = 'empty.webp';
        }
        printf("<div class='col_12 center'><a href='?u=$u'><img class='full-width' src='%s'><h1>$u</h1></a></div>", $p);

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
