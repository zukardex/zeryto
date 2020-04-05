<?php
$alph= array('m','l', 'n', 'k', 'o', 'j', 'p', 'i', 'q', 'h', 'r', 'g', 's', 'f', 't', 'e', 'u', 'd', 'v', 'c', 'w', 'b', 'x', 'a', 'y', 'z', '0', '1' , '2', '3', '4', '5', '6', '7', '8', '9', 'M', 'L', 'N', 'K', 'O', 'J', 'P', 'I', 'Q', 'H', 'R', 'G', 'S', 'F', 'T', 'E', 'U', 'D', 'V', 'C', 'W', 'B', 'X', 'A', 'Y', 'Z', '.', ',', ';', '?', '!', ' ');
$beta=array('p', 'W', 'G', 's', 'R', 'n', 'B', 'H', 'k', 'J');
function beta_dec($enc, $lensq){
	GLOBAL $beta;
	$dec='';
	$enc_str='';
	
	for($i=0; $i<count($enc); $i++){
		for($j=0; $j<count($beta); $j++){
			if($enc[$i] == 'f'){
				$enc[$i] = ' '; //NOTE
			}else if($enc[$i] == $beta[$j]){
				//if(($i* $j) % 7 ==0){
					$enc[$i] = $j;
				//}
			}
		}
		$enc_str = $enc_str . $enc[$i];
	}
	echo $enc_str . '<p>';
	$enc= explode(' ', $enc_str);
	return $enc;
}
function sinz($key_str, $plaintemp, $key){
	$four_s=0;
	$key_sum=0;
	$cube_sum=0;
	
	for($i=0; $i<strlen($key_str); $i++){
			if($i <4){
				$four_s+= $key_str[$i]; //four digit sum
				$cube_sum+= ($key_str[$i] * $key_str[$i] * $key_str[$i] );
			}
			$key_sum += $key_str[$i];
		}
		$temp_sin=(string) sin(M_PI *($four_s * $cube_sum/90) / 180);
		$int_sin= (int) $temp_sin;
		for($i=0; $int_sin<1; $i++){
			$temp_sin *=10;
			$int_sin= (int) $temp_sin;
		}
		//echo $temp_sin;
		
		
				//echo  '<p>ze=' . $temp_sin . ' ';
		$z= ($int_sin * $key)/$plaintemp;
			//echo  '<p>ze=' . $int_sin . ' ';
			return $z;
}

function dec($enc, $key, $z_set){
	GLOBAL $alph;
	GLOBAL $beta;
	$key_str= (string) $key;
	$pos=0;
	$z=0;
	$neo_enc=array();
	$result='';
	$plaintemp='';
	
	
	$ldig= $enc[-1]; //last digit of encrypted text
		for($i=0; $i < count($alph); $i++){
			if($ldig == $alph[$i]){
				$pos= $i - 1; //$pos=strlen(strlen(PLAIN_TEXT))
			}
		}
		for($i=strlen($enc)- $pos-1; $i<strlen($enc)-1; $i++){
			$plaintemp = $plaintemp . $enc[$i];
			//echo  '<p>plain=' . $plaintemp . ' ';
		}
		$plaintemp=(int)$plaintemp;
	
	if( $z_set == FALSE){
		$z= sinz($key_str, $plaintemp, $key);
		$z=(int) $z;
	}
	else{
		for($i=0; $i<strlen($key_str); $i++){
			for($j=0; $j<count($beta); $j++){
				if($key_str[$i] == $beta[$j]){
					$key_str[$i] = $j;
				}
			}
		}
		echo $key_str;
		$z= (int) $key_str;
	}
	echo '<p> $z=' . $z . "<p>";

	//deletes the last ($pos+1) letters from $enc and forms a new array
	for($i=0; $i < strlen($enc)- $pos-1; $i++){
		$neo_enc[$i] = $enc[$i];
	}
	$alen= beta_dec($neo_enc, $pos);
	
	//Key deciphering and alpha decoding
	for($i=0; $i<count($alen); $i++){
		if($alen[$i] !=0){
			$alen[$i] = $alen[$i] / $z;
			for($j=0; $j<count($alph); $j++){
				if($alen[$i] == $j){
					$alen[$i]= $alph[$j];
				}
			}
		}else{
			$alen[$i] = 'm';
		}
		if($i != count($alen)-1){
			$result =$result . $alen[$i];
		}
		//echo '<p>' . $alen[$i];
	}
	return $result;
	
}





if(isset($_GET['text']) ){
	$t=$_GET['text'];
	if(isset($_GET['z'])){
		$key=$_GET['z'];
		if(strlen($key)>0){ 
			echo dec($t, $key, true);
		}
	}else{
	if(isset($_GET['key'])){
		$key=$_GET['key'];
		if(strlen($key)>= 4){ 
			$key=(int) $_GET['key'];
			echo dec($t, $key, false);
		}
	}} 
}
?>
<form method="get">
<input type="text" name="text" placeholder="Text to decrypt"/>
<input type="text" name="key" placeholder="Encryption key"/>
<input type="text" name="z" placeholder="Private $z key"/>
<input type="submit" name="submit"/>
</form>
