<?php
    $id = isset($_GET['id']) ? $_GET['id'] : rand(100000000, 999999999);
    header("Content-type: image/png");
    mt_srand($id);

    $size = 2048;
    $savePath = ".\\Images\\".$id;
    $lineThicknessMin = 2;
    $lineThicknessMax = 5;

    // create the "canvas"
    $img = imagecreatetruecolor($size, $size);
    imagesetthickness($img, mt_rand($lineThicknessMin, $lineThicknessMax));

    $img1 = imagecreatetruecolor($size, $size);
    imagesetthickness($img1, mt_rand($lineThicknessMin, $lineThicknessMax));

    $img2 = imagecreatetruecolor($size, $size);
    imagesetthickness($img2, mt_rand($lineThicknessMin, $lineThicknessMax));

    $img3 = imagecreatetruecolor($size, $size);
    imagesetthickness($img3, mt_rand($lineThicknessMin, $lineThicknessMax));

    $img4 = imagecreatetruecolor($size, $size);
    imagesetthickness($img4, mt_rand($lineThicknessMin, $lineThicknessMax));

    $img5 = imagecreatetruecolor($size, $size);
    imagesetthickness($img5, mt_rand($lineThicknessMin, $lineThicknessMax));

    $imgCombo = imagecreatetruecolor($size, $size);
    imagesetthickness($imgCombo, 4);

    $ncol = 60;// min_colore 0
    $xcol = 250;// max_colore 255

    $colore = imagecolorallocate($img, 255, 255, 255);


    // random color
    if(!empty($_GET['coloured']))
    {
        $coloreR = mt_rand($ncol, $xcol);
        $coloreG = mt_rand($ncol, $xcol);
        $coloreB = mt_rand($ncol, $xcol);

        $colore = imagecolorallocate($img, $coloreR, $coloreG, $coloreB);
    }

    // background color
    $coloresnf = imagecolorallocatealpha($img, 30, 30, 30, 127);
    imagefilledrectangle($img, 0, 0, $size, $size, $coloresnf);
    // imagecolortransparent($img, $coloresnf); // toggle transparent background

    // hexagon's center coordinates and radius
    $hex_x = $size / 2;
    $hex_y = $size / 2;
    $radius = ($size / 2) * 3 / 4;

    // Circle
    imagearc($img, $size / 2, $size / 2, $radius * 2, $radius * 2, 0, 360, $colore);

    // Shape (between 4 and 8 points, random rotation)
    $lati = mt_rand(4, 8);
    imagepolygon($img1, drawPoly($lati, $colore, 0, $radius, $size), $lati, $colore);
    $case = mt_rand(0,1);
    if($case == 1){
        $smallArcSize = ($size / 2) / 44 * 90 / $lati;
        $angdiff = deg2rad(360 / $lati);
        for ($i = 0; $i < $lati; $i++)
        {
            $posax = (($radius) * cos($i * $angdiff));
            $posay = (($radius) * sin($i * $angdiff));
            
            $points = circleIntersect($size/2, $size/2, $radius * 2, $posax, $posay, $smallArcSize);

            $dy = ($posay - $points[0][0]);
            $dx = ($posax - $points[0][1]);
            $angle1 =  (atan($dy/$dx) + (rad2deg($angdiff) * $i) + 93 + $lati) % 360;

            $dy = ($posay - $points[1][0]);
            $dx = ($posax - $points[1][1]);
            $angle2 = ((atan($dy/$dx) + (rad2deg($angdiff) * $i)) - (91 + $lati)) % 360;
            
            imagearc($img2, $size / 2 + $posax, $size / 2 + $posay, $smallArcSize, $smallArcSize, $angle1, $angle2, $colore);

        }
    } elseif($case == 2){
        $posax = (($radius) * cos($i * $angdiff));
        $posay = (($radius) * sin($i * $angdiff));
        
        $points = circleIntersect($size/2, $size/2, $radius * 2, $posax, $posay, $smallArcSize);

        $dy = ($posay - $points[0][0]);
        $dx = ($posax - $points[0][1]);
        $angle1 =  (atan($dy/$dx) + (rad2deg($angdiff) * $i) + 93 + $lati) % 360;

        $dy = ($posay - $points[1][0]);
        $dx = ($posax - $points[1][1]);
        $angle2 = ((atan($dy/$dx) + (rad2deg($angdiff) * $i)) - (91 + $lati)) % 360;
        
        imagearc($img2, $size / 2 + $posax, $size / 2 + $posay, $smallArcSize, $smallArcSize, $angle1, $angle2, $colore);
    }
    
    // Cross, same number of points 
    for ($l = 0; $l < $lati; $l++)
    {
        $ang = deg2rad((360 / ($lati))) * $l;
        imageline($img1, ($size / 2), ($size / 2), ($size / 2) + $radius * cos($ang), ($size / 2) + $radius * sin($ang), $colore);
    }

    // Add another polygon over the first with lines
    if($lati%2 == 0)
    {
        $latis = mt_rand(3, 6);
        while($latis%2 != 0) $latis = mt_rand(3, 6);
        
        imagefilledpolygon($img2, drawPoly($latis, $coloresnf, 180, $radius, $size), $latis, $coloresnf);
        imagepolygon($img2, drawPoly($latis, $colore, 180, $radius, $size), $latis, $colore);

        for ($l = 0; $l < $latis; $l++)
        {
            $ang = deg2rad((360 / $latis)) * $l;
            imageline($img2, ($size / 2), ($size / 2), ($size / 2) + $radius * cos($ang), ($size / 2) + $radius * sin($ang), $colore);
        }
    }
    // add another polygon over the first without lines
    else
    {
        while(($latis = mt_rand(3, 6))%2 != 0);

        imagefilledpolygon($img2, drawPoly($latis, $coloresnf, 180, $radius, $size), $latis, $coloresnf);
        imagepolygon($img2, drawPoly($latis, $colore, 180, $radius, $size), $latis, $colore);
    }
    
    // smaller polygon
    if(mt_rand(0, 1)%2 == 0)
    {
        $ronad = mt_rand(0, 4);
        // Big Polygon and cross
        if($ronad%2 == 1)
        {
            for ($l = 0; $l < $lati + 4; $l++)
            {
                $ang = deg2rad((360 / ($lati + 4))) * $l;
                imageline($img3, ($size / 2), ($size / 2), ($size / 2)+((($radius / 8) * 5) + 2) * cos($ang), ($size / 2) + ((($radius / 8) * 5) + 2) * sin($ang), $colore);
            }
            imagefilledpolygon($img3, drawPoly($lati + 4, $colore, 0, $radius / 2, $size), $lati + 4, $coloresnf);
            imagepolygon($img3, drawPoly($lati + 4, $colore, 0, $radius / 2, $size), $lati + 4, $colore);
        }
        // Small Polygon big cross
        elseif($ronad%2 == 0 && $lati > 5)
        {
            for ($l = 0; $l < $lati - 2; $l++)
            {
                $ang = deg2rad((360 / ($lati - 2))) * $l;
                imageline($img3, ($size / 2), ($size / 2), ($size / 2) + ((($radius / 8) * 5) + 2) * cos($ang), ($size / 2) + ((($radius / 8) * 5) + 2) * sin($ang), $colore);
            }
            imagefilledpolygon($img3, drawPoly($lati - 2, $colore, 0, $radius / 4, $size), $lati - 2, $coloresnf);
            imagepolygon($img3, drawPoly($lati - 2, $colore, 0, $radius / 4, $size), $lati - 2, $colore);
        }
    }

    // smaller circle with another polygon
    if(mt_rand(0,4)%2 == 0)
    {
        imagearc($img4, $size / 2, $size / 2, ($radius / 8) * 11, ($radius / 8) * 11, 0, 360, $colore);
        // Even number of sides on the polygon
        if($lati%2 == 0)
        {
            $latis = mt_rand(3, 8);
            while($latis%2 != 0) $latis = mt_rand(3, 8);

            imagepolygon($img4, drawPoly($latis, $colore, 180, ($radius / 3) * 2, $size), $latis, $colore);
        }
        // odd number of sides
        else
        {
            while(($latis = mt_rand(3, 8))%2 == 0);

            imagepolygon($img4, drawPoly($latis, $colore, 180, ($radius / 3) * 2, $size), $latis, $colore);
            
        }
    }

    // Extra
    $case = mt_rand(0, 3);
    $case2 = mt_rand(0, 1);
    // Circles on edge or point of inner polygon and circle
    if($case == 0)
    {
        $numPoints = mt_rand(3, 8);
        for ($i = 0; $i < $latis; $i++)
        {
            $angdiff = deg2rad(360 / ($latis));
            $posax = (($radius / 18) * 11) * cos($i * $angdiff);
            $posay = (($radius / 18) * 11) * sin($i * $angdiff);
            if($case2 == 0){
                imagefilledarc($img5, $size / 2 + $posax, $size / 2 + $posay, ($radius / 44) * 12, ($radius / 44) * 12, 0, 360, $coloresnf, IMG_ARC_PIE);
                imagearc($img5, $size / 2 + $posax, $size / 2 + $posay, ($radius / 44) * 12, ($radius / 44) * 12, 0, 360, $colore);
            }
            elseif($case2 == 1){
                imagepolygon($img5, drawCustomPoly($numPoints, $colore, 0 /** deg2rad(360/$numPoints) - $angdiff*/, ($radius / 44) * 9, $size, $posax, $posay), $numPoints, $colore);
            }
        }
    }
    // Circles on Main Circle
    elseif($case == 1)
    {
        $numPoints = mt_rand(3, 8);
        for ($i=0; $i < $latis; $i++)
        {
            $angdiff = deg2rad(360 / $latis);
            $posax = $radius * cos($i * $angdiff);
            $posay = $radius * sin($i * $angdiff);
            if($case2 == 0){
                imagefilledarc($img5, $size / 2 + $posax, $size / 2 + $posay, ($radius / 44) * 12, ($radius / 44) * 12, 0, 360, $coloresnf, IMG_ARC_PIE);
                imagearc($img5, $size / 2 + $posax, $size / 2 + $posay, ($radius / 44) * 12, ($radius / 44) * 12, 0, 360, $colore);
            }
            elseif($case2 == 1){
                imagepolygon($img5, drawCustomPoly($numPoints, $colore, 0, ($radius / 44) * 9, $size, $posax, $posay), $numPoints, $colore);
            }
        }
    }
    // small circles in middle
    elseif($case == 2)
    {
        imagearc($img5, $size / 2, $size / 2, ($radius / 18) * 12, ($radius / 18) * 12, 0, 360, $colore);
        imagefilledarc($img5, $size / 2, $size / 2, ($radius / 22) * 12, ($radius / 22) * 12, 0, 360, $coloresnf, IMG_ARC_PIE);
        imagearc($img5, $size / 2, $size / 2, ($radius / 22) * 12, ($radius / 22) * 12, 0, 360, $colore);
    }

    // Lines between main and middle circle
    elseif($case == 3)
    {
        imagesetthickness($img2, mt_rand(3, 5));
        // Lines
        if($case2 == 0){
            for ($i = 0; $i < $latis; $i++)
            {
                $ang = deg2rad((360 / ($latis))) * $i;

                $lineStartX = ($size / 2) + (($radius / 3) * 2) * cos($ang);
                $lineStartY = ($size / 2) + (($radius / 3) * 2) * sin($ang);

                $lineEndX = ($size / 2) + $radius * cos($ang);
                $lineEndY = ($size / 2) + $radius * sin($ang);

                imageline($img2, $lineStartX, $lineStartY, $lineEndX, $lineEndY, $colore);
            }
        }
        elseif($case2 == 1){
            $angleOffset = (360 / ($latis) /2);
            for ($i = 0; $i < $latis; $i++)
            {
                $ang = deg2rad((360 / ($latis))) * $i;

                $lineEndX = ($size / 2) + $radius * cos($ang);
                $lineEndY = ($size / 2) + $radius * sin($ang);

                $lineStartX = ($size / 2) + (($radius / 3) * 2) * cos($ang - $angleOffset);
                $lineStartY = ($size / 2) + (($radius / 3) * 2) * sin($ang - $angleOffset);
                imageline($img2, $lineStartX, $lineStartY, $lineEndX, $lineEndY, $colore);

                $lineStartX = ($size / 2) + (($radius / 3) * 2) * cos($ang + $angleOffset);
                $lineStartY = ($size / 2) + (($radius / 3) * 2) * sin($ang + $angleOffset);
                imageline($img2, $lineStartX, $lineStartY, $lineEndX, $lineEndY, $colore);
            }
        }
        // Extra Shapes
        if($latis == $lati)
        {
        }
        else
        {
            // Little Circle
            imagefilledarc($img5, $size / 2, $size / 2, ($radius / 3) * 4, ($radius / 3) * 4, 0, 360, $coloresnf, IMG_ARC_PIE);
            imagearc($img5, $size / 2, $size / 2, ($radius / 3) * 4, ($radius / 3) * 4, 0, 360, $colore);
            $lati = mt_rand(3, 6);

            // Big Shape
            imagepolygon($img5, drawPoly($lati, $colore, 0, ($radius / 4) * 5, $size), $lati, $colore);
            // Little Shape
            imagepolygon($img2, drawPoly($lati, $colore, 180, ($radius / 3) * 2, $size), $lati, $colore);
        }
        
    }
    
    imagecolortransparent($img, $coloresnf);
    imagecolortransparent($img1, $coloresnf);
    imagecolortransparent($img2, $coloresnf);
    imagecolortransparent($img3, $coloresnf);
    imagecolortransparent($img4, $coloresnf);
    imagecolortransparent($img5, $coloresnf);

    imageFilter($img,IMG_FILTER_GAUSSIAN_BLUR);
    imageFilter($img1,IMG_FILTER_GAUSSIAN_BLUR);
    imageFilter($img2,IMG_FILTER_GAUSSIAN_BLUR);
    imageFilter($img3,IMG_FILTER_GAUSSIAN_BLUR);
    imageFilter($img4,IMG_FILTER_GAUSSIAN_BLUR);
    imageFilter($img5,IMG_FILTER_GAUSSIAN_BLUR);

    if (!file_exists($savePath)) { mkdir($savePath, 0777, true); }
    imagepng($img, $savePath."\\1.png");
    imagepng($img1, $savePath."\\2.png");
    imagepng($img2, $savePath."\\3.png");
    imagepng($img3, $savePath."\\4.png");
    imagepng($img4, $savePath."\\5.png");
    imagepng($img5, $savePath."\\6.png");

    imagecopymerge($imgCombo, $img, 0, 0, 0, 0, $size, $size, 50);
    imagecopymerge($imgCombo, $img1, 0, 0, 0, 0, $size, $size, 50);
    imagecopymerge($imgCombo, $img2, 0, 0, 0, 0, $size, $size, 50);
    imagecopymerge($imgCombo, $img3, 0, 0, 0, 0, $size, $size, 50);
    imagecopymerge($imgCombo, $img4, 0, 0, 0, 0, $size, $size, 50);
    imagecopymerge($imgCombo, $img5, 0, 0, 0, 0, $size, $size, 50);

    imagepng($img);

    imagedestroy($img);
    imagedestroy($img1);
    imagedestroy($img2);
    imagedestroy($img3);
    imagedestroy($img4);
    imagedestroy($img5);
    imagepng($imgCombo);
    

    function drawPoly($sides, $colore, $rot, $radius, $size){
        // data graph values
        $values = array();
        $angdiff = deg2rad(360 / ($sides * 2));
        $rot = deg2rad($rot);

        for ($i = 0; $i < $sides * 2; $i++)
        {
            // trova i punti sulla circonferenza
            $values[$i] = ($size / 2) + $radius * cos($i * $angdiff + $rot); // X
            $i++;
            $values[$i] = ($size / 2) + $radius * sin(($i - 1) * $angdiff + $rot); // Y
        }

        return $values;
    }

    function drawCustomPoly($sides, $colore, $rot, $radius, $size, $xCentre, $yCentre){
        // data graph values
        $values = array();
        $angdiff = deg2rad(360 / ($sides * 2));
        $rot = deg2rad($rot);

        for ($i = 0; $i < $sides * 2; $i++)
        {
            // trova i punti sulla circonferenza
            $values[$i] = ($size / 2) + $radius * cos($i * $angdiff + $rot) + $xCentre; // X
            $i++;
            $values[$i] = ($size / 2) + $radius * sin(($i - 1) * $angdiff + $rot) + $yCentre; // Y
        }

        return $values;
    }

    function circleIntersect($X1, $Y1, $R1, $X2, $Y2, $R2){
        // adapted from http://paulbourke.net/geometry/circlesphere/
        #Find Difference
        $Dx = $X2 - $X1;
        $Dy = $Y2 - $Y1;
        $D = intval(sqrt($Dx**2 + $Dy**2));

        $chorddistance = ($R1**2 - $R2**2 + $D**2)/(2*$D);

        #distance from 1st circle's centre to the chord between intersects
        $halfchordlength = sqrt($R1**2 - $chorddistance**2);
        $chordmidpointx = $X1 + ($chorddistance*$Dx)/$D;
        $chordmidpointy = $Y1 + ($chorddistance*$Dy)/$D;
        $I1 = [intval($chordmidpointx + ($halfchordlength*$Dy)/$D), intval($chordmidpointy - ($halfchordlength*$Dx)/$D)];
        $theta1 = intval(deg2rad(atan2($I1[1]-$Y1, $I1[0]-$X1)));
        $I2 = [intval($chordmidpointx - ($halfchordlength*$Dy)/$D), intval($chordmidpointy + ($halfchordlength*$Dx)/$D)];
        $theta2 = intval(deg2rad(atan2($I2[1]-$Y1, $I2[0]-$X1)));
        if($theta2 > $theta1){
            $TEMP = $I1;
            $I1 = $I2;
            $I2 = $TEMP;
        }
        return [$I1, $I2];
    }
?>