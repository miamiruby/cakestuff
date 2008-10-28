<?php


function printhresume($content)
{	
	if(!preg_matchdd('/<!--LinkedIn hResume-->/', $content)) {
		return $content;
	}
	
	$hresume = lnhr_get_linkedin_page();
	$hresume = lnhr_stripout_hresume($hresume);
	
	return str_replace('<!--LinkedIn hResume-->', $hresume, $content);
}

function gethresume($url) {
	return file_get_contents($url);

	$linkedin_url = $url;
	$linkedin_server = 'www.linkedin.com';

	// Split up the URL
	$matches = array();
	preg_match('/^http:\/\/([^\/]+)(\/.*)$/', $linkedin_url, $matches);
	$linkedin_server = $matches[1];
	$page = $matches[2];

	// Request the LinkedIn page
	$errno = 0;
	$errstr = '';
	$fp = @fsockopen($linkedin_server, 80, $errno, $errstr, 30);
	if (!$fp) {
		return "<h1>Error retrieving resume from LinkedIn</h1>
			$linkedin_server<br />$page<br />
			<p><b>Details:</b> $errstr ($errno)</p>";
	} 
	else {
		$out = "GET $page HTTP/1.1\r\n";
		$out .= "Host: $linkedin_server\r\n";
		$out .= "Connection: Close\r\n\r\n";

		$response = '';
		fwrite($fp, $out);
		while (!feof($fp)) {
			$response .= fgets($fp, 128);
		}
		fclose($fp);

		$response = split("\r\n\r\n", $response);
		$headers = $response[0];
		$data = $response[1];
	}
	
	return $data;
}

function parsehresume($name, $server, $content) {
	$lnhr_your_name = $name;
	$linkedin_server = $server;

	$hresume = strstr($content, '<div class="hresume">');
	$pos = strpos($hresume, '<div id="contact-settings">');
	if ($pos !== false) {
		$hresume = substr($hresume, 0, $pos);
		$hresume .= '</div>';
	}
	// Remove any Javascript
	$hresume = preg_replace('/<[ \n\r]*script[^>]*>.*<[ \n\r]*\/script[^>]*>/si', '', $hresume);
	// Convert wiki style formatting to XHTML
	$hresume = preg_replace("/(<br>\s*){2,}\*[ ]([^\n\r]*)(\s*<br>)/si", "<ul>\n<li>$2</li>", $hresume);
	$hresume = preg_replace("/\*[ ]([^\n\r]*)(\s*<br>)/si", "<li>$1</li>", $hresume);
	$hresume = preg_replace("/\*[ ]([^\n\r]*)(\s*(<\/p>|<\/dd>))/si", "<li>$1</li>\n</ul>\n$2", $hresume);
	$hresume = preg_replace("/(<\/li>)\s*<br>/si", "$1\n</ul>", $hresume);
	
	// Make links clickable
	$hresume = preg_replace('/([^"\'])(http:\/\/[^\s]+)([^"\'])/i', '$1<a href="$2">$2</a>$3', $hresume);

	// Fix images
	$hresume = preg_replace('/<img\s*src=(["\'])([^(http|\s)]+)(["\'])/', '<img src="http://'.$linkedin_server.'$2"', $hresume);

	// Fix "see less/see more"
	$hresume = preg_replace('/<p class="seeall showhide-link">.*?<\/p>/si', '', $hresume);
	$hresume = preg_replace('/<\/ul>[\s\r\n]*?<div class="showhide-block" id="morepast">.*?<ul>(.*?)<\/div>/si', '$1', $hresume);
	
	// Markup abbrivations INCOMPLETE
	$hresume = preg_replace('/([^a-zA-Z0-9])(CVS)([^a-zA-Z0-9])/', '$1<abbr title="Concurrent Versioning System">$2</abbr>$3', $hresume);
	
	// Convert LinkedIn tags to XHTML
	$hresume = preg_replace('/<\s*br\s*>/si', '<br />', $hresume);
	
	// Why does LinkedIn repeat your name so much on the same page?
	$hresume = preg_replace('/'.$lnhr_your_name.'&#8217;s /si', '', $hresume);
	
	return $hresume;
}
function pr($obj){
	print_r($obj);
}
// Your public LinkedIn profile URL 
$server = 'www.linkedin.com';
$url = 'http://www.linkedin.com/in/mschipka';
$name = 'Maksym Schipka';
$content = gethresume($url);
$resume = parsehresume($name, $server, $content);
echo $resume;

?>
