<?
	header("Content-type: text/css");
	
	include_once("../inc/connect.php");
	include_once("../inc/function.inc.php");
	include_once("../inc/login.inc.php");

	if ($company_id>0) {
		$cache_enable = true;
		//$cache_enable = false;
		
		$cache_time = 86400;
		$cache_folder = '../cache/';
		$cache_filename = $cache_folder.$company_id.".css";
		clearstatcache();
		$cache_created  = @filemtime($cache_filename);
		
		if ($cache_enable && ($cache_created > (time() - $cache_time))) { 
			readfile($cache_filename);
			return;
		} else {
			ob_start();	
		}
	
	
		$sql="SELECT * FROM `company` WHERE company_id = '$company_id' ";		
		$result=mysql_query($sql);
		if ($row = mysql_fetch_array($result)) {
			$hd_caption = $row[header_caption];		
			$main_color = $row[main_color];		
			$pulldown_color = $row[pulldown_color];		
			$pulldown_color2 = $row[pulldown_color2];		
			$cross_color = $row[cross_color];		
		}
		if ($main_color=="") {
			$hd_caption = "#3e8a1f";
			$menu_bg = "#3e8a1f";	
			$menu_bg2 = "#ff6600";	
			$menu_bg2 = lighter(invert_colour($menu_bg), 20);	
		//$menu_bg2 = "#ff6600";	
		//$menu_bg2 = lighter(invert_colour($menu_bg), 20);	
		}

		
		$screen_width = 1010;
		$menu_bg = "#".$main_color;	
		$menu_bg2 = "#".$pulldown_color;
		$menu_bg4 = "#".$pulldown_color2;
		$menu_bg2_border = darker($menu_bg2);	
		$menu_bg2_hover = lighter($menu_bg2, 45);
		$menu_bg2_hover_text = "#555555";
		
		$logo = "#75A54B";
		$logo = lighter($menu_bg, 45);
		$menu_bg3 = lighter($menu_bg, 100);

		$link = darker($menu_bg, 50);
		$link_hover = lighter($link, 40);
	
?>

html {
    -webkit-filter: grayscale(100%); /* Chrome, Safari, Opera */
    filter: grayscale(100%);
}


body {
	margin: 0; padding: 0;
	font-family: Verdana, Tahoma, Arial,sans-serif;
	font-size:11px;
	color: #333; 
	background: #FFFFFF;	
	text-align: center; 
	word-wrap:break-word;
}

TD, TH,  FONT {
font-family: verdana;
font-size:11px;
word-wrap:break-word;
}

.menu_h1 {
	margin:0px 0px 0px 0px;
	font-size:11px; 
	font-weight:bold; 
	color:#555555; 
	padding-left:10px; 
	padding-top:0px; 
	padding-bottom:0px;
	margin: 0px;
	background-color:<?=$menu_bg4?>;
}

.main_color {
	background: <?=$menu_bg?>;
	color: #ffffff;
}

.main_color2 {
	background: <?=$menu_bg3?>;
}

.main_color3 {
	background: <?=$link_hover?>;
}

.main_color4 {
	background: <?=$menu_bg4?>;
}

.cross_color {
	background: #<?=$cross_color?>;
}

a {
	color: <?=$link?>; 
	text-decoration: none;
}
a:hover {
	color: <?=$link_hover?>;
}

h1, h2, h3 {
	font-family: Verdana, Tahoma, Arial,sans-serif;
	font-weight: Bold; 		
	padding: 0px;		
	margin: 0px;	
}
h1 {
	font-size: 140%;	
}
h2 {
	font-size: 120%;
}
h3 {
	font-size: 100%;	
}

h2, h3, p {
	padding: 10px;		
	margin: 0;
}

/* images */
img {
	border: 0px solid #D5D5D5;
}
img.b {
	border: 3px solid #D5D5D5;
}
img.float-right {
  margin: 5px 0px 5px 10px;  
}
img.float-left {
  margin: 5px 10px 5px 0px;
}

#sidebar h1, 
#sidebar p {
	padding-left: 0;
}

ul, ol {
	margin: 10px 20px;
	padding: 0 20px;
}

code {
  margin: 5px 0;
  padding: 10px;
  text-align: left;
  display: block;
  overflow: auto;  
  font: 500 1em/1.5em 'Lucida Console', 'courier new', monospace;
  /* white-space: pre; */
  background: #FAFAFA;
  border: 1px solid #f2f2f2;  
  border-left: 4px solid #FF9966;
}
acronym {
  cursor: help;
  border-bottom: 1px solid #777;
}
blockquote {
	margin: 10px;
 	padding: 0 0 0 32px;  	
  	background: #FAFAFA url(../images/quote.gif) no-repeat 5px 10px !important; 
	background-position: 8px 10px;
	border: 1px solid #f2f2f2; 
	border-left: 4px solid #FF9966;   
}

/* form elements */
form {
	margin:10px; padding: 5px;
	border: 1px solid #f2f2f2; 
	background-color: #FAFAFA; 
}
label {
}
input, select {
	overflow: visible;
	padding: 3px;
	border:1px solid #bbbbbb;
	font: normal 11px Verdana, sans-serif;
	color:#000000;
}

input.checkbox {
	padding: 3px;
	border:none;
	font: normal 1em Verdana, sans-serif;
	color:#000000;
}


textarea {
	padding:3px;
	font: normal 1em Verdana, sans-serif;
	border:1px solid #bbbbbb;
	display:block;
	color:#000000;
}
input.button { 
	margin: 0; 
	font: bolder 12px Verdana, Sans-serif; 
	border: 1px solid #CCC; 
	padding: 2px 3px; 
	background: #FFF;
	color: #75A54B;
}
/* search form */
form.search {
	position: absolute;
	top: 15px; right: 5px;
	padding: 0; margin: 0;
	border: none;
	background-color: transparent; 
}
form.search input.textbox { 
	margin: 0; 
	width: 120px;
	border: 1px solid #CCC; 
	background: #FFF;
	color: #333; 	
	vertical-align: top;
}
form.search input.button {
	width: 60px;
	vertical-align: top;
}

/**************************************
   LAYOUT 
***************************************/	
#wrap {
	margin: 0 auto; 
	padding: 0; 
	width: <?=$screen_width?>px;
	text-align: left;
}

/* header */
#header { 
	position: relative;
	height: 60px; 
	margin: 0; padding: 0;
	color: #808080; 		
}
#header h1#logo {
	position: absolute;	
	font: bold 3.9em "trebuchet MS", Arial, Tahoma, Sans-Serif;
	margin: 0; padding:0;
	color: <?=$logo?>;
	letter-spacing: -2px;	
	border: none;	
	
	/* change the values of top and Left to adjust the position of the logo*/
	top: 0; left: 2px;		
}
#header h1#logo span { color: #F18359; }

#header h2#slogan { 
	position: absolute;
	margin: 0; padding: 0;	
	font: bold 12px Arial, Tahoma, Sans-Serif;	
	text-transform: none;
	
	/* change the values of top and Left to adjust the position of the slogan*/
	top: 43px; left: 45px;
}

/* sidebar */
#sidebar {
	float: left;
	width: 21%; 
	margin: 0;	padding: 0; 
	display: inline;
}
#sidebar ul.sidemenu {
	list-style: none;
	text-align: left;
	margin: 0 0 7px 0; padding: 0;
	text-decoration: none;	
}
#sidebar ul.sidemenu li {
	border-bottom: 1px solid #EFF0F1;	
	background: url(../images/arrow.gif) no-repeat 3px 6px;	
	padding: 2px 5px 2px 20px;
}

* html body #sidebar ul.sidemenu li { height: 1%; }

#sidebar ul.sidemenu li a {
	font-weight: bolder;
	background-image: none;
	text-decoration: none;	
}

#rightbar {
	float: right;
	width: 21%;
	padding: 0;
	margin: 0; 			
}

/* main column */
#main {
	float: left;
	margin: 0 0 0 15px;
	padding: 0;
	width: 54%;	
}

.post-footer {
	background-color: #FAFAFA;
	padding: 5px; margin: 15px 10px 10px 10px;
	border: 1px solid #f2f2f2; 
	font-size: 95%;
}
.post-footer .date {
	background: url(../images/clock.gif) no-repeat left center;
	padding-left: 20px; margin: 0 10px 0 5px;
}
.post-footer .comments {
	background: url(../images/comment.gif) no-repeat left center;
	padding-left: 20px; margin: 0 10px 0 5px;
}
.post-footer .readmore {
	background: url(../images/page.gif) no-repeat left center;
	padding-left: 20px; margin: 0 10px 0 5px;
}

/* footer */
#footer { 
	clear: both; 	
	color: #666666; 	
	padding: 0;	 
	text-align: center; 
	height: 60px
}
#footer a { 
	text-decoration: none; 
	font-weight: bold;
}
#footer-content {
	background: <?=$menu_bg2?>;
	margin: 0 auto;
	width: 800px
}
#footer-content #footer-left {
	padding: 10px;
	width: 60%;
	float: left;
	text-align: left;
}
#footer-content #footer-right {
	padding: 10px;
	width: 33%;
	float: right;
	text-align: right;
}

/* alignment classes */
.float-left  { float: left; }
.float-right { float: right; }
.align-left  { text-align: left; }
.align-right { text-align: right; }

/* additional classes */
.clear  { clear: both; }
.green  { color: #75A54B; }


#sddm {
	z-index: 99999;
}

#sddm li {	
	list-style: none;
	float: left;
}

#sddm li a {	
	display: block;
}

#sddm li a:hover {
	background: #49A3FF
}

#sddm div {
	position: absolute;
	z-index: 99999;
	visibility: hidden;
	border: 1px solid <?=$menu_bg2_border?>;
	background: <?=$menu_bg2?>;
	padding: 3px 0px 5px 0px;
}

#sddm div a {	
	position: relative;
	display: block;
	width: auto;
	white-space: nowrap;
	text-align: left;
	color: #000000;
	font-weight: bold;
}

#sddm div a:hover {
	background: #f3845a;
}


/* menu */
#menu {
	clear: both;
	background: <?=$menu_bg?>;
	height: 26px;
	margin: 0;
	font-family: Tahoma, Verdana, Arial, Sans-Serif;		
	font-size: 11px;
	font-weight: bold;
	vertical-align: top;
	padding: 5px 0px 0px 0px;
}
#menu ul{
	margin: 0; 
	padding: 0 0 0 8px;
}
#menu ul li {
	float: left;
	list-style: none;		
	border-right: 0px solid <?=$menu_bg2?>;
}
#menu ul li a {
	display: block;
	font-size: 11px;
	font-weight: bold;
	padding: 0px 10px;
	color: #FFFFFF;	
}
#menu ul li div a {
	display: block;
	font-weight: bold;
	font-size: 11px;
	padding: 0px 10px;
	color: #FFFFFF;	
}
#menu ul li a:hover {
  	color: <?=$menu_bg2_hover_text?>;
	background: <?=$menu_bg2_hover?>;
}
#menu ul li div a:hover {
  	color: <?=$menu_bg2_hover_text?>;
	background: <?=$menu_bg2_hover?>;
}

.watermark {
    position: absolute;
    opacity: 0.25;
    font-size: 4em;
	font-weight: bold;
    width: 100%;
    text-align: center;
    z-index: 1000;
}
<?
		file_put_contents($cache_filename, ob_get_contents());  
		ob_end_flush();  
	}
	
function darker($c, $i=20) {
	$d = html2rgb($c);
	$d[0]-=$i;
	$d[1]-=$i;
	$d[2]-=$i;
	if ($d[0]<0) $d[0]=0;
	if ($d[1]<0) $d[1]=0;
	if ($d[2]<0) $d[2]=0;
	return rgb2html($d[0], $d[1], $d[2]);
}	
function lighter($c, $i=20) {
	$d = html2rgb($c);
	$d[0]+=$i;
	$d[1]+=$i;
	$d[2]+=$i;
	if ($d[0]>255) $d[0]=255;
	if ($d[1]>255) $d[1]=255;
	if ($d[2]>255) $d[2]=255;
	return rgb2html($d[0], $d[1], $d[2]);
}
function invert_colour($start_colour) { 
	$colour_red = hexdec(substr($start_colour, 1, 2)); 
	$colour_green = hexdec(substr($start_colour, 3, 2)); 
	$colour_blue = hexdec(substr($start_colour, 5, 2));

	$new_red = dechex(255 - $colour_red); 
	$new_green = dechex(255 - $colour_green); 
	$new_blue = dechex(255 - $colour_blue);

	if (strlen($new_red) == 1) {$new_red .= '0';} 
	if (strlen($new_green) == 1) {$new_green .= '0';} 
	if (strlen($new_blue) == 1) {$new_blue .= '0';}

	$new_colour = '#'.$new_red.$new_green.$new_blue;

	return $new_colour; 
}
	
function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

function rgb2html($r, $g=-1, $b=-1)
{
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return '#'.$color;
}	
?>