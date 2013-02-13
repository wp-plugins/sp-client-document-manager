<?php
require( '../../../../wp-load.php' );

function thumbPdf($pdf, $w,$h)
{
    try
	
    {
        $tmp = '../../../uploads/wp-client-document-manager';
        $format = "png";
        $source = ABSPATH.$pdf;
		
        $dest =  ABSPATH."".$pdf."_small.$format";
 		$dest2 =  ABSPATH."".$pdf."_big.$format";
      
	   
            $exec = "convert -scale 80x80 $source $dest";			
            exec($exec);
			 $exec2 = "convert -scale 250x250 $source $dest2";			
            exec($exec2);
		
 
        $im = new Imagick($dest);
        header("Content-Type:".$im->getFormat());
        echo $im;
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}
 
$file = $_GET['pdf'];
$w = $_GET['w'];
$h = $_GET['h'];
if ($file)
{
    thumbPdf($file, $w,$h);
}


?>