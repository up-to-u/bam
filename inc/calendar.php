<?


class Calendar
{
    function Calendar()
    {
    }
	function init()
	{
	}
    function getDayNames()
    {
        return $this->dayNames;
    }
    function setDayNames($names)
    {
        $this->dayNames = $names;
    }
    function getMonthNames()
    {
        return $this->monthNames;
    }
    function setMonthNames($names)
    {
        $this->monthNames = $names;
    }
    function getStartDay()
    {
        return $this->startDay;
    }
    function setStartDay($day)
    {
        $this->startDay = $day;
    }
    function getStartMonth()
    {
        return $this->startMonth;
    }
    function setStartMonth($month)
    {
        $this->startMonth = $month;
    }
    function getCalendarLink($month, $year)
    {
        return "";
    }
    function getDateLink($day, $month, $year)
    {
        return "";
    }
    function getDateBold($day, $month, $year)
    {
        return false;
    }
    function getDateNote($day, $month, $year)
    {
        return "";
    }
    function getCurrentMonthView()
    {
        $d = getdate(time());
        return $this->getMonthView($d["mon"], $d["year"]);
    }
    function getCurrentYearView()
    {
        $d = getdate(time());
        return $this->getYearView($d["year"]);
    }
    function getMonthView($month, $year, $channel_id)
    {
        return $this->getMonthHTML($month, $year, 1, $channel_id);
    }
    function getYearView($year)
    {
        return $this->getYearHTML($year);
    }
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
   
        $d = $this->daysInMonth[$month - 1];
   
        if ($month == 2)
        {
            // Check for leap year
            // Forget the 4000 rule, I doubt I'll be around then...
        
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }
    function getMonthHTML($m, $y, $showYear = 1, $channel_id)
    {
        $s = "";
        
        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year = $a[1];        
        
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));
    	
    	$first = $date["wday"];
    	$monthName = $this->monthNames[$month - 1];
    	
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	
    	if ($showYear == 1)
    	{
    	    $prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
    	    $nextMonth = $this->getCalendarLink($next[0], $next[1]);
    	}
    	else
    	{
    	    $prevMonth = "";
    	    $nextMonth = "";
    	}
    	
    	$header = $monthName . (($showYear > 0) ? "  " . ($year+543) : "");
    	
    	$s .= "<table width='100%'  border='0' cellspacing='1' cellpadding='0' bgcolor='#ffffff'>\n";
    	$s .= "<tr bgcolor='#ffffff' height='20'>\n";
    	$s .= "<td align=\"center\" valign=\"top\">" . (($prevMonth == "") ? "&nbsp;" : "<a href=\"$prevMonth\"><font color='#ffffff'><img src='images/left.gif' border='0'></a>")."</b></font></td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" colspan=\"5\"><font color='#8d4900'><b>$header</b></font></td>\n"; 
    	$s .= "<td align=\"center\" valign=\"top\">" . (($nextMonth == "") ? "&nbsp;" : "<a href=\"$nextMonth\"><font color='#ffffff'><img src='images/right.gif' border='0'></a>")."</b></font></td>\n";
    	$s .= "</tr>\n";
    	
    	$s .= "<tr>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarDayHeader\"><font color=#ff0000><b>".$this->dayNames[($this->startDay)%7]."</b></font></td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarDayHeader\"><b>" . $this->dayNames[($this->startDay+1)%7]."</b></td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarDayHeader\"><b>" . $this->dayNames[($this->startDay+2)%7]."</b></td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarDayHeader\"><b>" . $this->dayNames[($this->startDay+3)%7]."</b></td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarDayHeader\"><b>" . $this->dayNames[($this->startDay+4)%7]."</b></td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarDayHeader\"><b>" . $this->dayNames[($this->startDay+5)%7]."</b></td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarDayHeader\"><b>" . $this->dayNames[($this->startDay+6)%7]."</b></td>\n";
    	$s .= "</tr>\n";
    	
		//$s .= "<tr><td colspan='7'><hr size='1'></td></tr>\n";

    	// We need to work out what date to start at so that the first appears in the correct column
    	$d = $this->startDay + 1 - $first;
    	while ($d > 1)
    	{
    	    $d -= 7;
    	}

        // Make sure we know when today is, so that we can use a different CSS style
        $today = getdate(time());
    	
    	while ($d <= $daysInMonth)
    	{
    	    $s .= "<tr>\n";       
    	    
    	    for ($i = 0; $i < 7; $i++)
    	    {
        	    $class = ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendar";
    	        
				if ($this->getDateFill($d, $month, $year, $channel_id)==true)
					$d_bgcolor="#aaffaa";
				else
					$d_bgcolor="#eeeeee";

				$s .= "<td align=\"left\" valign=\"top\" bordercolor=\"#000000\" bgcolor=\"$d_bgcolor\" height='100' width='120'>";


    	        if ($d > 0 && $d <= $daysInMonth)
    	        {
    	            $link = $this->getDateLink($d, $month, $year);

					if ($this->getDateBold($d, $month, $year, $channel_id)==true)
						$dd = "<b>$d</b>";
					else
						$dd = $d;
					if(($i==0) || ($i == 6)) $dd = "<font color=#ff0000 >$dd</font>";
					$s .= (($link == "") ? $d : "<a href=\"$link\">$dd</a>");
					$s .= $this->getDateNote($d, $month, $year);
    	        }
    	        else
    	        {
    	            $s .= "&nbsp;";
    	        }
      	        $s .= "</td>\n";       
        	    $d++;
    	    }
    	    $s .= "</tr>\n";    
    	}
    	
    	$s .= "</table>\n";
    	
    	return $s;  	
    }
    function getYearHTML($year)
    {
        $s = "";
    	$prev = $this->getCalendarLink(0, $year - 1);
    	$next = $this->getCalendarLink(0, $year + 1);
        
        $s .= "<table class=\"calendar\" border=\"0\">\n";
        $s .= "<tr>";
    	$s .= "<td align=\"center\" valign=\"top\" align=\"left\">" . (($prev == "") ? "&nbsp;" : "<a href=\"$prev\">&lt;&lt;</a>")  . "</td>\n";
        $s .= "<td class=\"calendarHeader\" valign=\"top\" align=\"center\">" . (($this->startMonth > 1) ? $year . " - " . ($year + 1) : $year) ."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" align=\"right\">" . (($next == "") ? "&nbsp;" : "<a href=\"$next\">&gt;&gt;</a>")  . "</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>";
        $s .= "<td align=\"center\" class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(0 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(1 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(2 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(3 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(4 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(5 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(6 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(7 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(8 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(9 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(10 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td align=\"center\"  class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(11 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "</table>\n";
        
        return $s;
    }
	function adjustDate($month, $year)
    {
        $a = array();  
        $a[0] = $month;
        $a[1] = $year;
        
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }
        
        return $a;
    }

    var $startDay = 0;

    var $startMonth = 1;

    var $dayNames = array("อา", "จ", "อ", "พ", "พฤ", "ศ", "ส");
    
    var $monthNames = array('มกราคม', 'กุมพาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤษจิกายน', 'ธันวาคม');
                            
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    
}

class MyCalendar extends Calendar
{
	var $_target_file;
	var $_param;

	function init($month, $year)
	{
	}
	function setTarget($t)
	{
		$this->_target_file = $t;
	}
	function getTarget()
	{
		if ($this->_target_file=="") 
			return getenv("SCRIPT_NAME");
		else
			return $this->_target_file;
	}
	function setParam($p)
	{
		$this->_param = $p;
	}
	function getParam()
	{
		if ($this->_param=="") 
			return "";
		else
			return "&".$this->_param;
	}
	function getCalendarLink($month, $year)
	{
		global $channel_id;
		$link = "".$this->getTarget()."?month=$month&year=$year";
		$link .= $this->getParam();
		return $link;
	}
	function getDateLink($day, $month, $year)
	{
		global $channel_id;
		$link = "".$this->getTarget()."?day=$day&month=$month&year=$year";
		$link .= $this->getParam();
		return $link;
	}
    function getDateNote($day, $month, $year)
    {
		global $database, $connect;
		$s="";
		$result=mysql_db_query($database,"SET NAMES tis620", $connect);
		$sql="SELECT * FROM calendar WHERE DAY(calendar_date)='$day' AND MONTH(calendar_date)='$month' AND YEAR(calendar_date)='$year'";
		$result1=mysql_db_query($database,$sql, $connect);
		if ($row=mysql_fetch_array($result1))
		{
			if ($row[day_type]==1)
				$s .= " <img src='images/stop.gif' alt='non working'>";
			if (trim($row[calendar_note])!="")
				$s .= " <img src='images/mbox.gif'><BR>".$row[calendar_note];
		}
        return $s;
    }
	function getDateBold($day, $month, $year, $channel_id)
	{
		global $channel_id, $database, $connect;
		return false;
	}
	function getDateFill($day, $month, $year, $channel_id)
	{
		global $channel_id, $database, $connect;
		;
		if (sprintf("%04d-%02d-%02d", $year, $month, $day)==date("Y-m-d"))
			return true;
		else
			return false;
	}
}
?>