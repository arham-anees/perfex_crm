<?php 
 
class Asynclibrary
{
 
    public function __construct()
    {
        $this->ci =& get_instance();
    }
 
    function do_in_background($url, $params)
    {
        $post_string = http_build_query($params);
        $parts = parse_url($url);
            $errno = 0;
        $errstr = "";
        $out = '';
        //Use SSL & port 443 for secure servers
       //Use otherwise for localhost and non-secure servers
       //For secure server
        //For localhost and un-secure server
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        if (isset($_SERVER['HTTPS'])) {
            $fp = fsockopen('ssl://' . $parts['host'], isset($parts['port']) ? $parts['port'] : 443, $errno, $errstr, 30);
        }
        {
        if(!$fp)
            //log_message('error',"Some thing Problem:".$errstr);   
            echo  "Some thing Problem:";
        }
        $out = "GET ".$parts['path']." HTTP/1.1\r\n";
        $out.= "Host: ".$parts['host']."\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: ".strlen($post_string)."\r\n";
        $out.= "Connection: close\r\n\r\n";
        if (isset($post_string)) $out.= $post_string;
        fwrite($fp, $out);
        header("Content-type: text/plain");
        while (!feof($fp)) {
            echo fgets($fp, 1024);
        }
        fclose($fp);
  }
}
?>
