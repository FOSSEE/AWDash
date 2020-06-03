<?php
/**
* Copyright (c) 2005 - Javier Infante
* All rights reserved.   This program and the accompanying materials
* are made available under the terms of the 
* GNU General Public License (GPL) Version 2, June 1991, 
* which accompanies this distribution, and is available at: 
* http://www.opensource.org/licenses/gpl-license.php 
*
* Description: Example of usage of class.awfile.php
*
* Author: Javier Infante (original author)
* Email: jabi (at) irontec (dot) com
**/
require("class.awfile.php");

//echo "Inside File";
//echo json_encode($_GET);

$startd  = $_GET['sd'];
$endd    = $_GET['ed'];
$startm  = $_GET['sm'];
$endm    = $_GET['em'];
$starty  = $_GET['sy'];
$endy    = $_GET['ey'];
$website = $_GET['web'];
$pathaw    = $_GET['path'];
//echo "vars= ".$startm.$endm.$starty.$endy.$website;

function data($sd, $ed, $sm, $em, $sy, $ey, $web, $path)
{

       $result = array();
       $sno = 1;

//	Path to the AWSTATS DATA FILE
       $unique_visit = 0;
       $total_visit = 0;
       $page_loads = 0;
       $hits = 0;
       $bandwidth =0;

//     Total Calculation leaving start month and end month
       for($i=$sy;$i<=$ey;$i++){
	    for($j=(($i==$sy)?($sm+1):1);$j<=(($i==$ey)?($em-1):12);$j++){
//                echo $sy.$sm."---";
                $file = $path.'awstats'.$j.$i.'.'.$web.'.txt';
		if($j < 10){
		    $file = $path.'awstats0'.$j.$i.'.'.$web.'.txt';
		}

		if(file_exists($file)){
		     $aw = new awfile($file);
	             if ($aw->Error()) die($aw->GetError());
		     $total_visit += $aw->GetVisits();
		     $unique_visit += $aw->GetUniqueVisits();

                     foreach ($aw->GetDays() as $day=>$stats)
                     {
                         $bandwidth   += $stats[2];
                         $hits        += $stats[1];
                         $page_loads  += $stats[0];
                     }
                   }
             }
         }

//$output = array('website' => $web, 'unique_visit' => $unique_visit, 'total_visit' => $total_visit, 'total_page_loads' => $page_loads, 'total_hits' => $hits, 'total_bandwidth' => $bandwidth);
//echo json_encode($output);

if(!($sm == $em && $sy == $ey))
{
//      Calculation for start month
        $file = $path.'awstats'.$sm.$sy.'.'.$web.'.txt';
                if($sm < 10){
                    $file = $path.'awstats0'.$sm.$sy.'.'.$web.'.txt';
                }
        if(file_exists($file)){
                     $aw = new awfile($file);
                     if ($aw->Error()) die($aw->GetError());
         //echo json_encode($aw->GetDays());
        $unique_visit += $aw->GetUniqueVisits();
        foreach ($aw->GetDays() as $day=>$stats)
          {
              if($day >= $sd)
              {
               $total_visit += $stats[3];
               $bandwidth   += $stats[2];
               $hits        += $stats[1];
               $page_loads  += $stats[0];
            }
          }
       }

//$output = array('website' => $web, 'unique_visit' => $unique_visit, 'total_visit' => $total_visit, 'total_page_loads' => $page_loads, 'total_hits' => $hits, 'total_bandwidth' => $bandwidth);
//echo json_encode($output);



//      Calculation for end month
        $file = $path.'awstats'.$em.$sy.'.'.$web.'.txt';
                if($em < 10){
                    $file = $path.'awstats0'.$em.$sy.'.'.$web.'.txt';
                }

        if(file_exists($file)){
                     $aw = new awfile($file);
                     if ($aw->Error()) die($aw->GetError());
        //echo json_encode($aw->GetDays());
        if($sy == $ey && $sm == $em){
        }
        else{
        $unique_visit += $aw->GetUniqueVisits();
        }
        foreach ($aw->GetDays() as $day=>$stats)
          {
              if($day <= $ed)
              {
               $total_visit += $stats[3];
               $bandwidth   += $stats[2];
               $hits        += $stats[1];
               $page_loads  += $stats[0];
            }
          }
       }

//$output = array('website' => $web, 'unique_visit' => $unique_visit, 'total_visit' => $total_visit, 'total_page_loads' => $page_loads, 'total_hits' => $hits, 'total_bandwidth' => $bandwidth);
//echo json_encode($output);
}

else{

//      Calculation for common month
        $file = $path.'awstats'.$sm.$sy.'.'.$web.'.txt';
                if($sm < 10){
                    $file = $path.'awstats0'.$sm.$sy.'.'.$web.'.txt';
                }
        if(file_exists($file)){
                     $aw = new awfile($file);
                     if ($aw->Error()) die($aw->GetError());
         //echo json_encode($aw->GetDays());
        $unique_visit += $aw->GetUniqueVisits();
        foreach ($aw->GetDays() as $day=>$stats)
          {
              if($day >= $sd && $day <= $ed)
              {
               $total_visit += $stats[3];
               $bandwidth   += $stats[2];
               $hits        += $stats[1];
               $page_loads  += $stats[0];
            }
          }
       }

}
        $bandwidth = round($bandwidth/1048576);
	$output = array('website' => $web, 'unique_visit' => $unique_visit, 'total_visit' => $total_visit, 'total_page_loads' => $page_loads, 'total_hits' => $hits, 'total_bandwidth' => $bandwidth);
//	var_dump($output);
	echo json_encode($output);
	return $output;
}

data($startd, $endd, $startm, $endm, $starty, $endy, $website, $pathaw);

/*	
	$aw = new awfile($file);
	if ($aw->Error()) die($aw->GetError());

	echo "<strong>Showing contents [".$file."]</strong><br />";

	echo "The site first visit in the month: ".$aw->GetFirstVisit()."<br /><br />";
	echo "Total visits this month: ".$aw->GetVisits()."<br /><br />";
	echo "Total unique visits this month: ".$aw->GetUniqueVisits()."<br /><br />";
	/*echo "Pages viewed / hours:<br />";
	foreach ($aw->GetHours() as $hour=>$pages)
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>".str_pad($hour, 2, "0", STR_PAD_LEFT).": ".$pages." pages viewed.</em><br />";
		
	echo "Pages viewed / days:<br />";
	foreach ($aw->GetDays() as $day=>$pages)
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$day.": ".$pages." pages viewed.</em><br />";
	echo "<br />";

	$betterDay = $aw->GetBetterDay();
	echo "The day with more visitors(".$betterDay[1].") was the ".$betterDay[0].".<br /><br />";

	echo "hits / os:<br />";
	foreach ($aw->GetOs() as $os=>$hits)
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$os.": ".$hits." hits.</em><br />";
	echo "<br />";	
	
	echo "hits / browser:<br />";
	foreach ($aw->GetBrowser() as $browser=>$hits)
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$browser.": ".$hits." hits.</em><br />";
	echo "<br />";
		
	echo "Distinct Referers:<br />";
	foreach ($aw->GetReferers() as $referer=>$hits)
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$referer.": ".$hits." hits.</em><br />";
	echo "<br />";
		
	echo "Visits / Session Ranges:<br />";
	foreach ($aw->GetRanges() as $range=>$visits)
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>".$range.": ".$visits." visits.</em><br />";
	echo "<br />";*/

?>
