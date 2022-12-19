<?php
/*db connectors*/
//include('dbconfig.php');
function jarray($j)
{
    $d = @file_get_contents($j);
    return @json_decode($d, true);
}

/*function to get file extension.*/
function ext($f)
{
    $e = explode("?", $f);
    $e = explode(".", $e[0]);
    return end($e);
}

/*function to get file name.*/
function name($f)
{
    $f = basename($f);
    $e = explode("?", $f);
    $e = explode(".", $e[0]);
    return $e[0];
}

/*function to set your files*/
function output_file($file, $name, $mime_type = "")
{
    if (!is_readable($file))
    {
        die("File not found or inaccessible!");
    }
    $size = filesize($file);
    $name = rawurldecode($name);
    $known_mime_types = ["htm" => "text/html", "exe" => "application/octet-stream", "zip" => "application/zip", "doc" => "application/msword", "jpg" => "image/jpg", "php" => "text/plain", "xls" => "application/vnd.ms-excel", "ppt" => "application/vnd.ms-powerpoint", "gif" => "image/gif", "pdf" => "application/pdf", "txt" => "text/plain", "html" => "text/html", "png" => "image/png", "jpeg" => "image/jpg", "mp4" => "video/mp4", "jpeg" => "image/jpg", "jpeg" => "image/jpg", "jpeg" => "image/jpg", "jpeg" => "image/jpg", "jpeg" => "image/jpg", "jpeg" => "image/jpg", "jpeg" => "image/jpg", ];

    if ($mime_type == "")
    {
        $file_extension = strtolower(substr(strrchr($file, ".") , 1));
        if (array_key_exists($file_extension, $known_mime_types))
        {
            $mime_type = $known_mime_types[$file_extension];
        }
        else
        {
            $mime_type = "application/force-download";
        }
    }
    @ob_end_clean();
    if (ini_get("zlib.output_compression"))
    {
        ini_set("zlib.output_compression", "Off");
    }
    header("Content-Type: " . $mime_type);
    header('Content-Disposition: attachment; filename="' . $name . '"');
    header("Content-Transfer-Encoding: binary");
    header("Accept-Ranges: bytes");

    if (isset($_SERVER["HTTP_RANGE"]))
    {
        list($a, $range) = explode("=", $_SERVER["HTTP_RANGE"], 2);
        list($range) = explode(",", $range, 2);
        list($range, $range_end) = explode("-", $range);
        $range = intval($range);
        if (!$range_end)
        {
            $range_end = $size - 1;
        }
        else
        {
            $range_end = intval($range_end);
        }

        $new_length = $range_end - $range + 1;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Length: $new_length");
        header("Content-Range: bytes $range-$range_end/$size");
    }
    else
    {
        $new_length = $size;
        header("Content-Length: " . $size);
    }

    $chunksize = 1 * (1024 * 1024);
    $bytes_send = 0;
    if ($file = fopen($file, "r"))
    {
        if (isset($_SERVER["HTTP_RANGE"]))
        {
            fseek($file, $range);
        }

        while (!feof($file) && !connection_aborted() && $bytes_send < $new_length)
        {
            $buffer = fread($file, $chunksize);
            echo $buffer;
            flush();
            $bytes_send += strlen($buffer);
        }
        fclose($file);
    }
    else
    {
        die("Error - can not open file.");
    }
    die();
}

/*function to get urls*/
function get_url($url, $file, $mime_type = "")
{

    /*
    $context = stream_context_create(
    array (
    'ssl' => array (
      'verify_peer' => false,
      'verify_peer_name' => false
    )
    )
    );
    $context = stream_context_create(array('ssl'=>array(
    'verify_peer' => true,
    'cafile' => '/storage/emulated/0/www/config/cacert.pem'
    )));
    if (!is_writable($file)) {
      //  die("$path is not found or writable!");
    }
    $start=-1;
    if(file_exists($file)){
    $start = filesize($file);
    }
    //$url = rawurldecode($url);
    
    
    $fh = fopen($file,"w+");
    $uh = fopen($url,"r", false, $context);
    if(fseek($uh,$start)!==false){
    // Read first line
    while($data=fgets($uh, 1024*10)){
    // Move back to beginning of file
    fwrite($fh, $data);
    
    }}
    else{
    $data=fgets($uh);
    // Move back to beginning of file
    fwrite($fh, $data);
    }
    
    fclose($fh);
    fclose($uh);
    
    die();*/
}


