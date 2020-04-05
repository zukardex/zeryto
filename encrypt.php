<?php
//include 'decrypt.php';
$alph= array('m','l', 'n', 'k', 'o', 'j', 'p', 'i', 'q', 'h', 'r', 'g', 's', 'f', 't', 'e', 'u', 'd', 'v', 'c', 'w', 'b', 'x', 'a', 'y', 'z', '0', '1' , '2', '3', '4', '5', '6', '7', '8', '9', 'M', 'L', 'N', 'K', 'O', 'J', 'P', 'I', 'Q', 'H', 'R', 'G', 'S', 'F', 'T', 'E', 'U', 'D', 'V', 'C', 'W', 'B', 'X', 'A', 'Y', 'Z', '.', ',', ';', '?', '!', ' ');
$beta=array('p', 'W', 'G', 's', 'R', 'n', 'B', 'H', 'k', 'J');
function beta_enc($txt, $art){
	GLOBAL $beta;
	for($i=0; $i<strlen($txt); $i++){
		for($j=0; $j<count($beta); $j++){
			if($txt[$i] == ' '){
				$txt[$i]='f';
			}else if($txt[$i] == $j){
				if(($i * $j) % 7 !=0){
					$txt[$i] = $beta[$j];
				}
			}
		}
	}
	return $txt;
}
function salt($txt, $len){
	GLOBAL $alph;
	$len_str= (string) $len;
	/*Salt the whitespaces in $txt with $len
	
	1)encode the $len using GLOBAL $alph shift cipher (0-9; m-r) and append it into the end of $txt string
	2)count the no.of digits in the encoded text(=strlen($len)) and append the integer right to the encoded text.
	*/
	$txt= beta_enc($txt, $len_str);
	$txt_st= $txt . $len. $alph[strlen($len_str)+1];
	return $txt_st;
}
function enc_z($z){
	//Genrates the private key
	$z= (string) $z;
	GLOBAL $beta;
	for($i=0; $i<strlen($z); $i++){
		for($j=0; $j<count($beta); $j++){
			if($z[$i] == $j){
				$z[$i] = $beta[$j];
			}
		}
	}
	return $z;
}
function enc($a, $key){
	/*	1)get position of $alph array and  makes it to 2digit if it isn't (encoding)
		2)get the sum of the first 4 digits of the key ($keysf) and find the cube sums of the first 4 digits ($keyscub)
		3)get the sum  of the digits of the key ($keysum)
		4) $modulo= ($keysf * $keyscub) / $keysum
		5) $int_sin= sin($modulo) and run a loop if necessary to get its integer part >0
		6) $z= $int_sin * $key
		7) run a loop to get product of every encoded letter and $z and save the value in a string, $result
		8)Salt the $result by adding seperation letters to it to distinguish between the letters
	*/
	$keysf=0;
	$keyscub=0;
	$keysum=0;
	$keystr= (string) $key; //string version of the $key
	$int_sin= 0;
	$pos_alph=-1;
	$enc_digt=array();
	$result='';
	GLOBAL $alph;
	
	
	for($j=0; $j< strlen($keystr); $j++){
		if($j <=4){
			$keysf += $keystr[$j];
			$keyscub += ($keystr[$j] * $keystr[$j] * $keystr[$j]);
		}
		$keysum += $keystr[$j];
	}
	



//MAKE $modulo value variable along with strlen($a) to get randomized results
	$modulo= ($keysf * $keyscub) / 90; //$modulo= ($keysf * $keyscub) / (90*$len_mod);
	echo $key;
	$temp_sin= sin(M_PI * $modulo/(180));
	//echo $temp_sin;
	$int_sin= (int)$temp_sin;
	for($i=0; $int_sin<1; $i++){
		$temp_sin *= 10;
		$int_sin= (int) $temp_sin;
	}

	echo '<p>plain=' . strlen($a);
	$z_temp= ($int_sin * $key)/strlen($a);
	$z=(int) $z_temp;
	echo "<p>z=" . $z . "<p>";
	
	/*
		1)Create a loop which runs from 0 to strlen($a) and access each letter of $a seperately
		2)$pos_alph=Find the position of the i'th letter in $alph and change it into two digit
		3)Create a new array($enc_digt) and save the $pos_alph in to it using a loop running from 0 to strlen($a)
		4)Using an  external loop, $enc_digit[i]= $z * $enc_digit[i] . ' '
		5)Salt the whitespaces in the elements and convert the whole array into a string and display it
	*/
	for($m=0; $m<strlen($a); $m++){
		//Accessess each letter of the plaintext seperately
		for($i=0; $i<count($alph); $i++){
			//Gets the position of the letter of $a in $alph
			if($a[$m] == $alph[$i]){
				$pos_alph= (int) $i;
				//echo $pos_alph;
			}
		}
		if($pos_alph >=0){
			//The cryptosystem supports the digit included in plaintext.
			$enc_digt[$m]= (int) $pos_alph * $z; //adds the encrypted text into the new array
			//echo $enc_digt[$m] . "<p>";
		}
	} 
	if($m == count($enc_digt)){
		echo "<b>Encryption process was done successfully</b><p>";
	}
	//convert the array into  a string
	for($j=0; $j < count($enc_digt); $j++){
		$result= $result . $enc_digt[$j] . ' ';
	}
	if($result != ''){
		echo ' <p>$result='. $result . "<p>";
		//return salt($result, strlen($a));
		echo "final=" . salt($result, strlen($a)) . '<p>';
		echo "<p>\$z private key= " . enc_z($z) . "<p>";
	}else{
		echo 'Encryption failed';
	}
}




if(isset($_GET['text']) ){
	$t=$_GET['text'];
	if(isset($_GET['key'])){
		$key= $_GET['key'];
		if(strlen($key)>= 4){ 
			$key=(int) $_GET['key'];
			echo enc($t, $key);
		}
	}
}


?>
<form method="get">
<input type="text" name="text" placeholder="Text to encrypt"/>
<input type="text" name="key" placeholder="Encryption key"/>
<input type="submit" name="submit"/>
</form>
