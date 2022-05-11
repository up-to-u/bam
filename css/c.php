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
select, input, textarea {
font-family: verdana;
font-size:11px;
word-wrap:break-word;
}

/* links */
a {
	color: #F9864D; 
	background-color: inherit;
	text-decoration: none;
}
a:hover {
	color: #575757;
	background-color: inherit;
}

/* headers */
h1, h2, h3 {
	font-family: 'Trebuchet MS', Tahoma, Verdana, Sans-serif;
	font-weight: Bold; 		
}
h1 {
	font-size: 145%;	
	padding: 10px 10px 5px 10px;
	color: #75A54B;
	background-color: inherit;
	border-bottom: 1px solid #EFF0F1;		
}
h2 {
	font-size: 125%;
	text-transform: uppercase;
}
h3 {
	font-size: 125%;	
	color: #404040;
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
	padding: 3px;
	border:1px solid #eee;
	font: normal 1em Verdana, sans-serif;
	color:#777;
}
textarea {
	padding:3px;
	font: normal 1em Verdana, sans-serif;
	border:1px solid #eee;
	display:block;
	color:#777;
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
	width: 1000px;
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
	color: #75A54B;
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
	background: #FFF url(../images/footerbg.gif) repeat-x;
	height: 60px
}
#footer a { 
	text-decoration: none; 
	font-weight: bold;
}
#footer-content {
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
	z-index: 999;
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
	visibility: hidden;
	border: 1px solid #f3845a;
	background: #ff6600;
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
	background: #3e8a1f;
	height: 30px;
	margin: 0;
	font-family: Tahoma, Verdana, Arial, Sans-Serif;		
	font-size: 12;
	font-weight: bold;
	vertical-align: middle;
	padding: 5px 0px 0px 0px;
}
#menu ul{
	margin: 0; 
	padding: 0 0 0 8px;
}
#menu ul li {
	float: left;
	list-style: none;		
	border-right: 0px solid #ff6600;
}
#menu ul li a {
	display: block;
	text-decoration: none;	
	padding: 0px 10px;
	color: #FFFFFF;	
}
#menu ul li a:hover {
  	color: #00aee0;	
	background: none;
}
#menu ul li#current a {
	color: #333;
	background: url(../images/menu-current-bg.gif) repeat-x;	
}