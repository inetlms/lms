<?php

/*
 * LMS iNET
 *
 *  (C) Copyright 2012 LMS iNET Developers
 *
 *  Please, see the doc/AUTHORS for more information about authors!
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 2 as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 *  USA.
 *
 *  $Id: monitchartimg.php,v 1.00 2012/12/20 22:01:35 Sylwester Kondracki Exp $
 */

class MonitChart {
    
    var $img;
    var $colors;
    var $width			= 360;		// szerokość
    var $height			= 180;		// wysokość
    var $scalecolor		= 'whitesmoke';
    private $columns		= NULL;
    private $columns_width;
    private $ptime;
    private $cdate;
    private $title		= array();	// tekst,fontsize,align,color
    private $chart_width;			// szerokość robocza dla wykresu
    private $chart_height;			// wysokość robocza dla wykresu;
    private $chart_x1;				//
    private $chart_y1;				// współrzędne dla wersji roboczej
    private $chart_x2;				//
    private $chart_y2;				//
    private $fonttitlesize 	= 3;
    private $fontlegendsize	= 2;
    private $fonttitlewidth;
    private $fonttitleheight;
    private $fontlegendwidth;
    private $fontlegendheight;
    private $backgroundchart = 'white';
    private $borderchart = 'silver';
    private $scale		= false;
    private $maxy		= '10.00';
    private $ylegend		= false;
    private $ylegendcolor	= 'dimgrey';
    private $xlegend		= false;
    private $padding_bottom	= 5;
    
    // inicjujemy rozmiar, tło, obramowanie i sam image's
    function Init($dane) 
    {
	
	$this->fonttitlesize = $dane['fonttitlesize'];
	$this->fonttitlewidth = imagefontwidth($this->fonttitlesize);
	$this->fonttitleheight = imagefontheight($this->fonttitlesize);
	
	$this->fontlegendsize = $dane['fontlegendsize'];
	$this->fontlegendwidth = imagefontwidth($this->fontlegendsize);
	$this->fontlegendheight = imagefontheight($this->fontlegendsize);
	
	$this -> width = $this -> chart_width = $this -> chart_x2 = (int)$dane['width'];
	$this -> height = $this -> chart_height = $this -> chart_y2 = (int)$dane['height'];
	$this -> chart_x1 = $this -> chart_y1 = 0;
	
	$this->img = imagecreate($this->width,$this->height);
	$this->InitColors();
    }
    
    function AddData($ptime,$cdate,$steptest)
    {
//	$krok = get_conf('monit.step_test',5) * 60;
	$krok = $steptest * 60;
	
	$this->ptime = $ptime;
	$this->cdate = $cdate;
	unset($ptime); unset($cdate);
	$this->columns = sizeof($this->ptime);
	
	if (!empty($this->columns)) 
	{
	    $this->maxy = max($this->ptime);
	    $mindate = min($this->cdate);
	    $maxdate = max($this->cdate);
	} else 
	    $mindate = $maxdate = 0;
	
	
	
	// dodajemy zerowe wartości dla ptime jeżeli nie było testu
	$ptime = array();
	$cdate = array();
	$countdate = $maxdate - $mindate;
	if ($countdate < 21600) {
	    $countdate = 21600;
	    //$mindate = time() - 43200;
	    //$maxdate = time();
	    if (!empty($this->columns)) 
		$maxdate = max($this->cdate);
	    else 
		$maxdate = time();
	    $mindate = $maxdate - 21600;
	    $this->cdate[0] = $mindate;
	    $idx = $this->columns - 1;
	    $this->cdate[$idx] = $maxdate;
	}
	
	$iloscpozycji = ceil(($countdate) / $krok);

	for ($i=0; $i<$iloscpozycji; $i++) // pętla która tworzy taką ilość rekordów jake powinny istnieć w danym przedziale czasowym
	{
	    $tmpcdate = $mindate + ($i * $krok); // data jaka powinna być dla danego kroku (step_test)
	    $ptime[$i] = 0; // tworzymy zrowy rekord
	    $cdate[$i] = $tmpcdate; // wstawiamy daną jedn. czasu
	    
	    if ($this->columns > 0)
	    {
	    if ($id = array_search($tmpcdate,$this->cdate)) // jeżeli jest taki czas w tablicy z db to podstawiamy dane do nowej tablicy
	    {
		$ptime[$i] = $this->ptime[$id];
		$cdate[$i] = $this->cdate[$id];
		unset($this->ptime[$id]);
		unset($this->cdate[$id]);
	    }
	    else // jeżeli nie znalazł to sprawdzamy jeszcze raz znacznik czasowy ale już z tolerancją +- 20 sekund, ku pochybie powtarzalności cron
		for ($j=-5;$j<=25;$j++)
		    if ($id = array_search($tmpcdate+$j,$this->cdate))
		    {
			$ptime[$i] = $this->ptime[$id];
			$cdate[$i] = $this->cdate[$id];
			unset($this->ptime[$id]);
			unset($this->cdate[$id]);
			break; // przerywamy pętlę jak coś znalazł
		    }
	    }
	}
	$this->ptime = $ptime;
	$this->cdate = $cdate;
	$this->maxy = max($ptime);
	$this->columns = sizeof($ptime);
	if ($this->maxy < 3) $this->maxy = '3.00';
	
    }
    
    function SetLegendY($on = true)
    {
	if (!is_bool($on)) $on = true;
	$this->ylegend = $on;
    }
    
    function SetLegendX($on = true)
    {
	if (!is_bool($on)) $on = true;
	$this->xlegend = $on;
    }
    
    function AddScale()
    {
	$this->scale = true;
    }
    
    function SetBackground($color = 'transparent')
    {

	if ($color == 'transparent')
	    imagecolortransparent($this->img,$this->colors['black']);
	else
	{
	    if (!isset($this->colors[$color]) || empty($this->colors[$color])) $color = 'whitesmoke';
	    imagefilledrectangle($this->img,0,0,$this->width,$this->height,$this->colors[$color]);
	}
    }
    
    function SetBackgroundChart($color = 'white')
    {

	    if (!isset($this->colors[$color]) || empty($this->colors[$color])) $color = 'whitesmoke';
	    $this->backgroundchart = $color;
    }
    
    function SetBorderChart($color = 'silver')
    {
	if (!isset($this->colors[$color]) || empty($this->colors[$color])) $color = 'silver';
	$this->borderchart = $color;
    }
    
    private function BackgroundChart()
    {
	    imagefilledrectangle($this->img,$this->chart_x1,$this->chart_y1,$this->chart_x2,$this->chart_y2,$this->colors[$this->backgroundchart]);
    }
    
    private function BorderChart()
    {
	imageline($this->img,$this->chart_x1,$this->chart_y1,$this->chart_x2,$this->chart_y1,$this->colors[$this->borderchart]);
	imageline($this->img,$this->chart_x2,$this->chart_y1,$this->chart_x2,$this->chart_y2,$this->colors[$this->borderchart]);
	imageline($this->img,$this->chart_x1,$this->chart_y2,$this->chart_x2,$this->chart_y2,$this->colors[$this->borderchart]);
	imageline($this->img,$this->chart_x1,$this->chart_y1,$this->chart_x1,$this->chart_y2,$this->colors[$this->borderchart]);
	$this->chart_x1++;
	$this->chart_y1++;
	$this->chart_x2 -= 2;
	$this->chart_y2 -= 2;
	$this->chart_width = ($this->chart_x2 - $this->chart_x1);
	$this->chart_height = ($this->chart_y2 - $this->chart_y1);
	
    }
    
    function SetBorder($color = 'silver')
    {
	if (!isset($this->colors[$color]) || empty($this->colors[$color])) $color = 'silver';

	imageline($this->img,0,0,$this->width,0,$this->colors[$color]);
	imageline($this->img,$this->width-1,0,$this->width-1,$this->height,$this->colors[$color]);
	imageline($this->img,0,$this->height-1,$this->width,$this->height-1,$this->colors[$color]);
	imageline($this->img,0,0,0,$this->height,$this->colors[$color]);
	
	$this->chart_x1++;
	$this->chart_y1++;
	$this->chart_x2 -= 2;
	$this->chart_y2 -= 2;
	$this->chart_width = ($this->chart_x2 - $this->chart_x1);
	$this->chart_height = ($this->chart_y2 - $this->chart_y1);
    }
    
    private function CalculateChart()
    {
	$this->chart_width = ($this->chart_x2 - $this->chart_x1);
	$this->chart_height = ($this->chart_y2 - $this->chart_y1);
    }
    
    function AddTitle($tekst='',$align='center',$color='black')
    {
	if (!isset($this->colors[$color]) || empty($this->colors[$color])) $color = 'black';
	if (!in_array($align,array('left','right','center'))) $align='center';
	if (empty($tekst)) $tekst = 'Statystyki PING';
	$this->title[] = array(
	    'title'	=> $tekst,
	    'align'	=> $align,
	    'color'	=> $color
	    );
    }
	
    function DisplayPingChart()
    {
	
	// dodajemy tytuł wykresu;
	$this->title[] = array('align'=>'center','color'=>'dimgrey','title'=>'od '.date('Y/m/d H:i',min($this->cdate)).' do '.date('Y/m/d H:i',max($this->cdate)));
	if (!empty($this->title))
	{
	    for ($i=0; $i<sizeof($this->title); $i++)
	    {
		if ($this->title[$i]['align'] == 'left')
		    $x = 4;
		elseif ($this->title[$i]['align'] == 'right')
		    $x = ceil(($this->width - (strlen($this->title[$i]['title']) * $this->fonttitlewidth))-4);
		else
		    $x = ceil(($this->width - (strlen($this->title[$i]['title']) * $this->fonttitlewidth))/2);
		imagestring($this->img, $this->fonttitlesize, $x, $this->chart_y1 + 2,$this->title[$i]['title'],$this->colors[$this->title[$i]['color']]);
		$this->chart_y1 += ($this->fonttitleheight+4);
	    }
	    $this->chart_y1 += 6;
	    $this->chart_x2 -= 4;
	    $this->CalculateChart();
	}

	//dodajemy napis z prawej strony
	$this->chart_x2 -= ($this->fontlegendwidth+5);
	$this->CalculateChart();
	$y = (($this->height - ((strlen('LMS iNET') * $this->fontlegendheight)/2))/2);
	imagestringup($this->img,$this->fontlegendsize,$this->width-($this->fontlegendheight+3),$this->height-$y,'LMS iNET',$this->colors['darkgrey']);
	
	if ($this->xlegend)
	{
	    
	    $this->chart_y2 -= (3*$this->fontlegendheight)+4;
	    $this->calculatechart();

	    $count = sizeof($this->ptime);
	    
	    if (!empty($count))
	    {
		$avg = 0;
		$licznik = 0;
		$min = max($this->ptime);;
		for ($i=0; $i<$count; $i++)
		    if ($this->ptime[$i] > '0') 
		    { 
			$licznik++; 
			$avg += $this->ptime[$i];
			if ($this->ptime[$i]<$min) $min = $this->ptime[$i];
		    }
		if ($avg !=0) $avg = ($avg / $licznik);
		$count--;
		$last = $this->ptime[$count];
	    }
	    else
	    $avg = $min = $max = $last = 0;
	    
	    unset($count);
	    unset($licznik);
	    $max = max($this->ptime);
	    $string = 'Min: '.sprintf("%.2f",$min).' ms  Avg: '.sprintf("%.2f",$avg).' ms  Max: '.sprintf("%.2f",$max).' ms  Last: '.sprintf("%.2f",$last).' ms';
	    $x = (($this->width - (strlen($string) * $this->fontlegendwidth))/2);
	    $y = $this->chart_y2+(2*$this->fontlegendheight);
	    $this->CalculateChart();
	    imagestring($this->img,$this->fontlegendsize,$x,$y,$string,$this->colors['dimgrey']);
	}
	if ($this->ylegend)
	{ 
	    $widthstr = ($this->fontlegendwidth * strlen($this->maxy));
	    $this->chart_x1 += $widthstr+6;
	    $this->chart_y2 -= $this->padding_bottom;
	    $this->CalculateChart();
	    $step = ((20 * $this->maxy) / 100); 
	    $val = '0.00';
	    $bar = ((20 * $this->chart_height) / 100);
	    for ($i=0; $i<6; $i++)
	    {
		$y = ($this->chart_y2 - ($i * $bar) - ($this->fontlegendheight/2)-2);
		$tekst = sprintf("%.2f",($val));
		$tekst = str_replace(',','.',$tekst);
		$widthstr = ceil(($this->fontlegendwidth * (strlen($this->maxy) - strlen($tekst))))+4;
		imagestring($this->img,$this->fontlegendsize,$widthstr,$y,$tekst,$this->colors[$this->ylegendcolor]);
		$val += $step;
	    }
	}
	
	// rysujemy obszar roboczy
	$this->backgroundchart();
	$this->borderchart();
	
	// rysujemy główną podziałkę
	if ($this->scale)
	{
	    // dla osi x (pionowa podziałka)
	    $bar =(5 * $this->chart_width) / 100;
	    for ($i=1; $i<20; $i++)
	    {
		$x = ($this->chart_x1 + ($i * $bar));
		imageline($this->img,$x,$this->chart_y1,$x,$this->chart_y2,$this->colors[$this->scalecolor]);
	    }
	
	    // dla osi y (pozioma podziałka)
	    $bar = ( (10 * $this->chart_height) / 100);
	    for ($i=1; $i<10; $i++)
	    {
		$y = ($this->chart_y1 + ($i * $bar));
		imageline($this->img,$this->chart_x1,$y,$this->chart_x2,$y,$this->colors[$this->scalecolor]);
	    }
	
	}	
	
	// rysujemy wykres
	if (!empty($this->columns) && !empty($this->chart_height) && !empty($this->maxy))
	{
	imagesetthickness($this->img,$this->columns_width);
	if (!empty($this->columns))
	{
	    
	    $this->columns_width = (($this->chart_width / $this->columns));
	    
	    $y2 = $this->chart_y2;
	    
	    for ($i=0; $i<$this->columns; $i++)
	    {
		if ($this->ptime[$i] == '-1')
		    $column_height = $this->chart_height;
		else
		    $column_height = ($this->chart_height / 100) * (($this->ptime[$i] / $this->maxy)*100);

		$x1 = $this->chart_x1 + ($i * $this->columns_width);
		$y1 = $this->chart_y2 - $column_height;
		$x2 = $this->chart_x1 + (($i * $this->columns_width) + $this->columns_width);
    
		if ($this->ptime[$i] == '-1')
		    imagefilledrectangle($this->img,$x1,$y1,$x2,$y2,$this->colors['red']);
		if ($this->ptime[$i] > '0')
		    imagefilledrectangle($this->img,$x1,$y1,$x2,$y2,$this->colors['limegreen']);

	    }
	
	}
	imagesetthickness($this->img,1);
	}
	
	// rysujemy główną podziałkę
	if ($this->scale)
	{
	
	    $y = $this->chart_y2+5;
	    
	    $sizestr = 10*$this->fontlegendwidth;
	    $countview = ceil($this->chart_width / $sizestr);
	    $countcdate = ceil($this->columns / ($countview));
	    for ($i=0; $i<$countview; $i++)
	    {
		$x = $this->chart_x2 - ($i * $sizestr) - (8*$this->fontlegendwidth) ;
		$xs = $this->chart_x2 - ($i * $sizestr) - (12*$this->fontlegendwidth) ;
		$ind =  ($this->columns) - (($i+1)*$countcdate);
		$string1 = date('y-m-d',$this->cdate[$ind]);
		$string2 = date('  H:i',$this->cdate[$ind]);
		if ($i < $countview-1)
		{
		imagestring($this->img,$this->fontlegendsize,$xs,$y,$string1,$this->colors['dimgrey']);
		imagestring($this->img,$this->fontlegendsize,$xs,$y+$this->fontlegendheight,$string2,$this->colors['dimgrey']);
		imagedashedline($this->img,$x,$this->chart_y1,$x,$this->chart_y2,$this->colors['darkkhaki']);
		}
	    }
	
	    // dla osi y (pozioma podziałka)
	    $bar = ( (10 * $this->chart_height) / 100);
	    for ($i=1; $i<10; $i++)
	    {
		$y = ($this->chart_y1 + ($i * $bar));
		if ($i % 2) 
		    {}
		else 
		    imagedashedline($this->img,$this->chart_x1,$y,$this->chart_x2,$y,$this->colors['darkkhaki']);
	    }
	
	}

	header("Content-type: image/png");
	imagepng($this->img);
    }
    

    function SetChart($key=NULL,$value=NULL)
    {
	if (is_null($key) || is_null($value)) return false;
	$this->chart[$key] = $value;
    }
    
    
    function InitColors()
    {
	$this->colors = array(
	    'black'		=> imagecolorallocate($this->img, 0, 0, 0),
	    'dimgrey'		=> imagecolorallocate($this->img, 105, 105, 105),
	    'grey'		=> imagecolorallocate($this->img, 128, 128, 128),
	    'darkgrey'		=> imagecolorallocate($this->img, 169, 169, 169),
	    'silver'		=> imagecolorallocate($this->img, 192, 192, 192),
	    'lightgrey'		=> imagecolorallocate($this->img, 211, 211, 211),
	    'gainsboro'		=> imagecolorallocate($this->img, 220, 220, 220),
	    'whitesmoke'	=> imagecolorallocate($this->img, 245, 245, 245),
	    'white'		=> imagecolorallocate($this->img, 255, 255, 255),
	    'snow'		=> imagecolorallocate($this->img, 255, 250, 250),
	    'rosybrown'		=> imagecolorallocate($this->img, 188, 143, 143),
	    'lightcoral'	=> imagecolorallocate($this->img, 240, 128, 128),
	    'brown'		=> imagecolorallocate($this->img, 165, 42, 42),
	    'darkred'		=> imagecolorallocate($this->img, 139, 0, 0),
	    'red'		=> imagecolorallocate($this->img, 255, 0, 0),
	    'tomato'		=> imagecolorallocate($this->img, 255, 99, 71),
	    'coral'		=> imagecolorallocate($this->img, 255, 127, 80),
	    'orangered'		=> imagecolorallocate($this->img, 255, 69, 0),
	    'sienna'		=> imagecolorallocate($this->img, 160, 82, 45),
	    'orange'		=> imagecolorallocate($this->img, 255, 165, 0),
	    'gold'		=> imagecolorallocate($this->img, 255, 215, 0),
	    'darkkhaki'		=> imagecolorallocate($this->img, 189, 183, 107),
	    'lightyellow'	=> imagecolorallocate($this->img, 255,255,224),
	    'olive'		=> imagecolorallocate($this->img, 128,128,0),
	    'yellow'		=> imagecolorallocate($this->img, 255,255,0),
	    'green'		=> imagecolorallocate($this->img, 0, 128, 0),
	    'darkgreen'		=> imagecolorallocate($this->img, 0, 100, 0),
	    'limegreen'		=> imagecolorallocate($this->img, 50, 205, 50),
	    'lime'		=> imagecolorallocate($this->img, 0, 255, 0),
	    'seagreen'		=> imagecolorallocate($this->img, 46, 139, 87),
	    'azure'		=> imagecolorallocate($this->img, 240, 255, 255),
	    'lightcyan'		=> imagecolorallocate($this->img, 224, 255, 255),
	    'teal'		=> imagecolorallocate($this->img, 0, 128, 128),
	    'rolayblue'		=> imagecolorallocate($this->img, 65,105,225),
	    'blue'		=> imagecolorallocate($this->img, 0,0,255),
	    'mediumblue'	=> imagecolorallocate($this->img, 0,0,205),
	    'darkblue'		=> imagecolorallocate($this->img, 0,0,139),
	    'crismon'		=> imagecolorallocate($this->img, 220,20,60),
	    'purple'		=> imagecolorallocate($this->img, 128,0,128),
	    'violet'		=> imagecolorallocate($this->img, 238,130,238),
	    'azure'		=> imagecolorallocate($this->img, 240,255,255),
	    'lightcyan'		=> imagecolorallocate($this->img, 224,255,255),
	    'lightblue'		=> imagecolorallocate($this->img, 173,216,230),
	);
    }


} // end class

// 2.15:1

function MonitDisplayPingChart($danepodane=NULL)
{
    global $DB;
    
    $dane = array(
	'width'		=> 450,
	'height'	=> 192,
	'title'		=> '',
	'from'		=> (time() - 86400),
	'to'		=> time(),
	'id'		=> 0,
	'nodes'		=> true,
	'ptime'		=> NULL,
	'cdate'		=> NULL,
	'ip'		=> '127.0.0.1',
	'hostname'	=> NULL,
	'fonttitlesize' => 3,
	'fontlegendsize'=> 2,
	'chartsize'	=> 'big',
    );
    
    if (!is_null($danepodane) && is_array($danepodane)) foreach ($danepodane as $key => $item) $dane[$key] = $item;
    
    switch ($dane['chartsize'])
    {
	
	case 'verysmall'	: $dane['width'] = 320; $dane['height'] = 160; $dane['fonttitlesize'] = 1; $dane['fontlegendsize'] = 1;		break;
	case 'small'		: $dane['width'] = 400; $dane['height'] = 186; $dane['fonttitlesize'] = 2; $dane['fontlegendsize'] = 1;		break;
	case 'middle'		: $dane['width'] = 500; $dane['height'] = 250;		break;
	case 'normal'		: $dane['width'] = 640; $dane['height'] = 290;		break;
	case 'big'		: $dane['width'] = 740; $dane['height'] = 350;		break;
	case 'verybig'		: $dane['width'] = 990; $dane['height'] = 460;	$dane['fonttitlesize'] = 5; $dane['fontlegendsize'] = 3;	break;
	default			: $dane['width'] = 450; $dane['height'] = 204;		break;
	
    }
    
    
    $czas = time();
    $steptest = 5;
    if (is_null($dane['ptime']) || is_null($dane['cdate']) || empty($dane['ptime']) || empty($dane['cdate']))
    {
	if ($dane['nodes']) {
	    $datalist = $DB->GetAll('SELECT ptime, cdate FROM monittime WHERE cdate >= ? AND cdate <= ? AND nodeid = ?', array($dane['from'],$dane['to'],$dane['id']));
	    $tmp = $DB->GetRow('SELECT name, inet_ntoa(ipaddr) AS ipaddr FROM nodes WHERE id=? LIMIT 1;',array($dane['id']));
	    $tmp2 = $DB->GetOne('SELECT netdev FROM monit_vnodes WHERE id=? LIMIT 1;',array($dane['id']));
	    if ($tmp2) 
		$steptest = get_conf('monit.step_test_netdev',5);
	    else
		$steptest = get_conf('monit.step_test_nodes',5);
		
	} else {
	    $datalist = $DB->GetAll('SELECT ptime, cdate FROM monittime WHERE cdate >= ? AND cdate <= ? AND ownid = ?', array($dane['from'],$dane['to'],$dane['id']));
	    $tmp = $DB->GetRow('SELECT name, ipaddr FROM monitown WHERE id=? LIMIT 1;',array($dane['id']));
	    $steptest = get_conf('monit.step_test_owner',5);
	}
	
	$dane['ip'] = $tmp['ipaddr'];
	$dane['hostname'] = $tmp['name'];
	unset($tmp);
	
	if (!$datalist) return false; // jak nic nie ma
	$count = sizeof($datalist);
	for ($i=0; $i<$count; $i++) 
	{
	    $dane['ptime'][] = $datalist[$i]['ptime'];
	    $dane['cdate'][] = $datalist[$i]['cdate'];
	}
	unset($datalist);
    }
    if ($dane['from'] < min($dane['cdate'])) $dane['from'] = min($dane['cdate']);
    if ($dane['to'] > max($dane['cdate'])) $dane['to'] = max($dane['cdate']);
    
    $img = new MonitChart();
    $img->init($dane);
    $img->adddata($dane['ptime'],$dane['cdate'],$steptest);
    $img->SetBackground('default');
    $img->SetBorder();
    $img->AddTitle('PING dla '.$dane['hostname'],'center','dimgrey');
    $img->AddScale();
    $img->SetBackgroundChart('white');
    $img->SetBorderChart('silver');
    $img->SetLegendX(true);
    $img->SetLegendY(true);
    $img->displayPingChart();
}

//$layout['popup'] = true;

$datachart = array();

if (isset($_GET['chart']) && !empty($_GET['chart'])) $chart = strtolower($_GET['chart']); else $chart = 'ping';

if (!isset($_GET['type']) || empty($_GET['type'])) $_GET['type'] = 'nodes';
$_GET['type'] = strtolower($_GET['type']);
if (!in_array($_GET['type'],array('netdev','nodes','owner'))) $_GET['type'] = 'nodes';
if ($_GET['type'] == 'netdev' || $_GET['type'] == 'nodes') $datachart['nodes'] = true; else $datachart['nodes'] = false;

$datachart['id'] = intval($_GET['id']);

if (isset($_GET['from']) && !empty($_GET['from'])) 
{
    if (strtotime($_GET['from']))
	$datachart['from'] = strtotime($_GET['from']);
    else
	$datachart['from'] = $_GET['from'];
}
if (isset($_GET['to']) && !empty($_GET['to'])) 
{
    if (strtotime($_GET['to']))
	$datachart['to'] = strtotime($_GET['to']) + 86400;
    else
	$datachart['to'] = $_GET['to'];
}

if (isset($_GET['time']))
{
     $datachart['to'] = time();
    switch ($_GET['time'])
    {
	case '-6h'	: $datachart['from'] = time() - 21600; break;
	case '-12h'	: $datachart['from'] = time() - 43200; break;
	case '-1d'	: $datachart['from'] = time() - 86400; break;
	case '-2d'	: $datachart['from'] = time() - 172800; break;
	case '-3d'	: $datachart['from'] = time() - 259200; break;
	case '-7d'	: $datachart['from'] = time() - 604800; break;
	default 	: $datachart['from'] = time() - 43200; break;
	
    }
}
if ($checktime = $DB->getOne('SELECT MAX(cdate) FROM monittime WHERE '.($datachart['type']=='owner' ? 'ownerid' : 'nodeid').' = ? LIMIT 1;',array($datachart['id'])))
{
    if ($datachart['to'] > $checktime) $datachart['to'] = $checktime;
    if ( ($datachart['to'] - $datachart['from']) < 21600) $datachart['from'] = $datachart['to'] - 21600;
}
else
{
    $datachart['to'] = time();
    $datachart['from'] = time() - 43200;
}
if (isset($_GET['width']) && !empty($_GET['width'])) $datachart['width'] = intval($_GET['width']);
if (isset($_GET['height']) && !empty($_GET['height'])) $datachart['height'] = intval($_GET['height']);
if (isset($_GET['chartsize']) && !empty($_GET['chartsize'])) $datachart['chartsize'] = $_GET['chartsize'];
MonitDisplayPingChart($datachart);
?>