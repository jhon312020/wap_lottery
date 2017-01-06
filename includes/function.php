<?php 
function getAllCombos($arr) {
    //print_r($arr); exit;
    $combinations = array();
    $words = sizeof($arr);

    $arrCount = array_count_values($arr);
    //print_r($arrCount); exit;
    $s = 0;
    $p = 1;
    foreach($arrCount as $val) {
        $s += $val;
        $p *= $val;
    }
     
    $fact_s = (int)fact($s);
    $fact_p = (int)fact($p);
    $combos = $fact_s/$fact_p;
    while(sizeof($combinations) < $combos) {
        shuffle($arr);
        $combo = implode(" ", $arr);
        if(!in_array($combo, $combinations)) {
            $combinations[] = $combo;
        }
    }
    asort($combinations);
    return $combinations;
}
function fact($i) {
    if($i==1) {
        return 1;
    } else {
        return $i*fact($i-1);
    }
}
function getLengthArray($arr) {
    $words = sizeof($arr);
    return $words;
}

function getAllNumbers($number_type) {
    //echo $number_type; exit;
    if($number_type == "besar") {
        $combinations = array();
        for($i=50;$i<100;$i++) {
            $combinations[] = $i;
        }
        return $combinations;
    }
     if($number_type == "kecil") {
        $combinations = array();
        for($i=0;$i<50;$i++) {
            $combinations[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        return $combinations;
    }

    if($number_type == "genap") {
        $combinations = array();
        for($i=0;$i<100;$i++) {
            if($i % 2 == 0) {
            $combinations[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        }
        return $combinations;
    }

     if($number_type == "ganjil") {
        $combinations = array();
        for($i=0;$i<100;$i++) {
            if($i % 2!= 0) {
            $combinations[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        }
        return $combinations;
    }
}

function getPossibleCombination($arr) {

    $possibleCombinations = array();
    $num =  sizeof($arr);

    //The total number of possible combinations 
    $total = pow(2, $num);
   
    //Loop through each possible combination  
    for ($i = 0; $i < $total; $i++) {  
        $possibleCombinations[$i] = array();
        //For each combination check if each bit is set 
        for ($j = 0; $j < $num; $j++) { 
            //Is bit $j set in $i? 
            if (pow(2, $j) & $i) {
                array_push($possibleCombinations[$i],$arr[$j]);
            }
        }
    }
    $cleanCombo = array();
    foreach($possibleCombinations as $arrSet) {
        //print_r($arrSet);
        if(count($arrSet)>1 && count($arrSet)<5) {
            array_push($cleanCombo, $arrSet);
        }
    }
    return $cleanCombo;
}
?>