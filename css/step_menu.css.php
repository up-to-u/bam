
<?
//	$c = '#163038';
	//$p = '#A3C1C9';

	$c = '#1566C3';
	$hover = '#ffcc99';
	$p = '#DDDDFF';
?>

.wizard-steps {
    margin:20px 10px 0px 10px;
    padding:0px;
    position: relative;
    clear:both;
    font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;
    font-weight: bold;
}
.wizard-steps div {
    position:relative;
}
.wizard-steps span {
    display: block;
    float: left;
    font-size: 10px;
    text-align:center;
    width:15px;
    margin: 2px 5px 0px 0px;
    line-height:15px;
    color: #ccc;
    background: #FFF;
    border: 2px solid #CCC;
    -webkit-border-radius:10px;
    -moz-border-radius:10px;
    border-radius:10px;
}
.wizard-steps a {
    position:relative;
    display:block;
    width:auto;
    height:24px;
    margin-right: 18px;
    padding:0px 10px 0px 3px;
    float: left;
    font-size:11px;
    line-height:24px;
    color:#666;
    background: #F0EEE3;
    text-decoration:none;
    text-shadow:1px 1px 1px rgba(255,255,255, 0.8);
}
.wizard-steps a:before {
    width:0px;
    height:0px;
    border-top: 12px solid #F0EEE3;
    border-bottom: 12px solid #F0EEE3;
    border-left:12px solid transparent;
    position: absolute;
    content: "";
    top: 0px;
    left: -12px;
}
.wizard-steps a:after {
    width: 0;
    height: 0;
    border-top: 12px solid transparent;
    border-bottom: 12px solid transparent;
    border-left:12px solid #F0EEE3;
    position: absolute;
    content: "";
    top: 0px;
    right: -12px;
}
.wizard-steps .completed-step a {
    color:<?=$c?>;
    background: <?=$p?>;
}
.wizard-steps .completed-step a:before {
    border-top: 12px solid <?=$p?>;
    border-bottom: 12px solid <?=$p?>;
}
.wizard-steps .completed-step a:after {
    border-left: 12px solid <?=$p?>;
}
.wizard-steps .completed-step span {
    border: 2px solid <?=$c?>;
    color: <?=$c?>;
    text-shadow:none;
}
.wizard-steps .active-step a {
    color: #ffffff;
    background: <?=$c?>;
    text-shadow:1px 1px 1px rgba(0,0,0, 0.8);
}
.wizard-steps .active-step a:before {
    border-top: 12px solid <?=$c?>;
    border-bottom: 12px solid <?=$c?>;
}
.wizard-steps .active-step a:after {
    border-left: 12px solid <?=$c?>;
}
.wizard-steps .active-step span {
    color: <?=$c?>;
    -webkit-box-shadow:0px 0px 2px rgba(0,0,0, 0.8);
    -moz-box-shadow:0px 0px 2px rgba(0,0,0, 0.8);
    box-shadow:0px 0px 2px rgba(0,0,0, 0.8);
    text-shadow:none;
    border: 2px solid <?=$p?>;
}
.wizard-steps .completed-step:hover a, .wizard-steps .active-step:hover a {
    color:#fff;
    background: <?=$hover?>;
    text-shadow:1px 1px 1px rgba(0,0,0, 0.8);
}
.wizard-steps .completed-step:hover span, .wizard-steps .active-step:hover span {
    color:<?=$hover?>;
}
.wizard-steps .completed-step:hover a:before, .wizard-steps .active-step:hover a:before {
    border-top: 12px solid <?=$hover?>;
    border-bottom: 12px solid <?=$hover?>;
}
.wizard-steps .completed-step:hover a:after, .wizard-steps .active-step:hover a:after {
    border-left: 12px solid <?=$hover?>;
}