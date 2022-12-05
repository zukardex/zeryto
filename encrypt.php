<?php
//include 'decrypt.php';
$zeryto11_alph= array('m','l', 'n', 'k', 'o', 'j', 'p', 'i', 'q', 'h', 'r', 'g', 's', 'f', 't', 'e', 'u', 'd', 'v', 'c', 'w', 'b', 'x', 'a', 'y', 'z', '0', '1' , '2', '3', '4', '5', '6', '7', '8', '9', 'M', 'L', 'N', 'K', 'O', 'J', 'P', 'I', 'Q', 'H', 'R', 'G', 'S', 'F', 'T', 'E', 'U', 'D', 'V', 'C', 'W', 'B', 'X', 'A', 'Y', 'Z', '.', ',', ';', '?', '!', ' ');
$zeryto11_beta=array('p', 'W', 'G', 's', 'R', 'n', 'B', 'H', 'k', 'J');
function beta_enc($zeryto11_txt, $zeryto11_art){
	GLOBAL $zeryto11_beta;
	for($zeryto11_i=0; $zeryto11_i<strlen($zeryto11_txt); $zeryto11_i++){
		for($zeryto11_j=0; $zeryto11_j<count($zeryto11_beta); $zeryto11_j++){
			if($zeryto11_txt[$zeryto11_i] == ' '){
				$zeryto11_txt[$zeryto11_i]='f';
			}else if($zeryto11_txt[$zeryto11_i] == $zeryto11_j){
				if(($zeryto11_i * $zeryto11_j) % 7 !=0){
					$zeryto11_txt[$zeryto11_i] = $zeryto11_beta[$zeryto11_j];
				}
			}
		}
	}
	return $zeryto11_txt;
}
function salt($zeryto11_txt, $zeryto11_len){
	GLOBAL $zeryto11_alph;
	$zeryto11_len_str= (string) $zeryto11_len;
	/*Salt the whitespaces in $zeryto11_txt with $zeryto11_len
	
	1)encode the $zeryto11_len using GLOBAL $zeryto11_alph shift cipher (0-9; m-r) and append it into the end of $zeryto11_txt string
	2)count the no.of digits in the encoded text(=strlen($zeryto11_len)) and append the integer right to the encoded text.
	*/
	$zeryto11_txt= beta_enc($zeryto11_txt, $zeryto11_len_str);
	$zeryto11_txt_st= $zeryto11_txt . $zeryto11_len. $zeryto11_alph[strlen($zeryto11_len_str)+1];
	return $zeryto11_txt_st;
}
function enc_z($zeryto11_z){
	//Genrates the private key
	$zeryto11_z= (string) $zeryto11_z;
	GLOBAL $zeryto11_beta;
	for($zeryto11_i=0; $zeryto11_i<strlen($zeryto11_z); $zeryto11_i++){
		for($zeryto11_j=0; $zeryto11_j<count($zeryto11_beta); $zeryto11_j++){
			if($zeryto11_z[$zeryto11_i] == $zeryto11_j){
				$zeryto11_z[$zeryto11_i] = $zeryto11_beta[$zeryto11_j];
			}
		}
	}
	return $zeryto11_z;
}
function enc($zeryto11_a, $zeryto11_key){
	/*	1)get position of $zeryto11_alph array and  makes it to 2digit if it isn't (encoding)
		2)get the sum of the first 4 digits of the key ($zeryto11_keysf) and find the cube sums of the first 4 digits ($zeryto11_keyscub)
		3)get the sum  of the digits of the key ($zeryto11_keysum)
		4) $zeryto11_modulo= ($zeryto11_keysf * $zeryto11_keyscub) / $zeryto11_keysum
		5) $zeryto11_int_sin= sin($zeryto11_modulo) and run a loop if necessary to get its integer part >0
		6) $zeryto11_z= $zeryto11_int_sin * $zeryto11_key
		7) run a loop to get product of every encoded letter and $zeryto11_z and save the value in a string, $zeryto11_result
		8)Salt the $zeryto11_result by adding seperation letters to it to distinguish between the letters
	*/
	$zeryto11_keysf=0;
	$zeryto11_keyscub=0;
	$zeryto11_keysum=0;
	$zeryto11_keystr= (string) $zeryto11_key; //string version of the $zeryto11_key
	$zeryto11_int_sin= 0;
	$zeryto11_pos_alph=-1;
	$zeryto11_enc_digt=array();
	$zeryto11_result='';
	GLOBAL $zeryto11_alph;
	
	
	for($zeryto11_j=0; $zeryto11_j< strlen($zeryto11_keystr); $zeryto11_j++){
		if($zeryto11_j <=4){
			$zeryto11_keysf += $zeryto11_keystr[$zeryto11_j];
			$zeryto11_keyscub += ($zeryto11_keystr[$zeryto11_j] * $zeryto11_keystr[$zeryto11_j] * $zeryto11_keystr[$zeryto11_j]);
		}
		$zeryto11_keysum += $zeryto11_keystr[$zeryto11_j];
	}
	
/*	//Randomize the results- modify2
	for($zeryto11_i=2; $zeryto11_i<=10; $zeryto11_i++){
		if(strlen($zeryto11_a) % $zeryto11_i){
			$zeryto11_len_mod=  $zeryto11_i;
		}
	}
*/


//MAKE $zeryto11_modulo value variable along with strlen($zeryto11_a) to get randomized results
	$zeryto11_modulo= ($zeryto11_keysf * $zeryto11_keyscub) / 90; //$zeryto11_modulo= ($zeryto11_keysf * $zeryto11_keyscub) / (90*$zeryto11_len_mod);
	echo $zeryto11_key;
	$zeryto11_temp_sin= sin(M_PI * $zeryto11_modulo/(180));
	//echo $zeryto11_temp_sin;
	$zeryto11_int_sin= (int)$zeryto11_temp_sin;
	for($zeryto11_i=0; $zeryto11_int_sin<1; $zeryto11_i++){
		$zeryto11_temp_sin *= 10;
		$zeryto11_int_sin= (int) $zeryto11_temp_sin;
	}
/*
		if($zeryto11_len_mod % 2){
			for($zeryto11_i=0; $zeryto11_int_sin <= 0; $zeryto11_i++){
				$zeryto11_temp_sin *= 10;
				$zeryto11_int_sin= (int) $zeryto11_temp_sin;
			}
		}
		if($zeryto11_len_mod % 3){
			for($zeryto11_i=0; $zeryto11_int_sin <=10; $zeryto11_i++){
				$zeryto11_temp_sin *= 10;
				$zeryto11_int_sin= (int) $zeryto11_temp_sin;
			}
		}
		if($zeryto11_len_mod % 5){
			for($zeryto11_i=0; $zeryto11_int_sin <= 100; $zeryto11_i++){
				$zeryto11_temp_sin *= 10;
				$zeryto11_int_sin= (int) $zeryto11_temp_sin;
			}
		}
		if($zeryto11_len_mod % 7){
			for($zeryto11_i=0; $zeryto11_int_sin <= 1000; $zeryto11_i++){
				$zeryto11_temp_sin *= 10;
				$zeryto11_int_sin= (int) $zeryto11_temp_sin;
			}
		}
*/
	echo '<p>plain=' . strlen($zeryto11_a);
	$zeryto11_z_temp= ($zeryto11_int_sin * $zeryto11_key)/strlen($zeryto11_a);
	$zeryto11_z=(int) $zeryto11_z_temp;
	echo "<p>z=" . $zeryto11_z . "<p>";
	
	/*
		1)Create a loop which runs from 0 to strlen($zeryto11_a) and access each letter of $zeryto11_a seperately
		2)$zeryto11_pos_alph=Find the position of the i'th letter in $zeryto11_alph and change it into two digit
		3)Create a new array($zeryto11_enc_digt) and save the $zeryto11_pos_alph in to it using a loop running from 0 to strlen($zeryto11_a)
		4)Using an  external loop, $zeryto11_enc_digit[i]= $zeryto11_z * $zeryto11_enc_digit[i] . ' '
		5)Salt the whitespaces in the elements and convert the whole array into a string and display it
	*/
	for($zeryto11_m=0; $zeryto11_m<strlen($zeryto11_a); $zeryto11_m++){
		//Accessess each letter of the plaintext seperately
		for($zeryto11_i=0; $zeryto11_i<count($zeryto11_alph); $zeryto11_i++){
			//Gets the position of the letter of $zeryto11_a in $zeryto11_alph
			if($zeryto11_a[$zeryto11_m] == $zeryto11_alph[$zeryto11_i]){
				$zeryto11_pos_alph= (int) $zeryto11_i;
				//echo $zeryto11_pos_alph;
			}
		}
		if($zeryto11_pos_alph >=0){
			//The cryptosystem supports the digit included in plaintext.
			$zeryto11_enc_digt[$zeryto11_m]= (int) $zeryto11_pos_alph * $zeryto11_z; //adds the encrypted text into the new array
			//echo $zeryto11_enc_digt[$zeryto11_m] . "<p>";
		}
	} 
	if($zeryto11_m == count($zeryto11_enc_digt)){
		echo "<b>Encryption process was done successfully</b><p>";
	}
	//convert the array into  a string
	for($zeryto11_j=0; $zeryto11_j < count($zeryto11_enc_digt); $zeryto11_j++){
		$zeryto11_result= $zeryto11_result . $zeryto11_enc_digt[$zeryto11_j] . ' ';
	}
	if($zeryto11_result != ''){
		echo ' <p>$zeryto11_result='. $zeryto11_result . "<p>";
		//return salt($zeryto11_result, strlen($zeryto11_a));
		echo "final=" . salt($zeryto11_result, strlen($zeryto11_a)) . '<p>';
		echo "<p>\$zeryto11_z private key= " . enc_z($zeryto11_z) . "<p>";
	}else{
		echo 'Encryption failed';
	}
}




/*if(isset($_GET['text']) ){
	$zeryto11_t=$_GET['text'];
	if(isset($_GET['key'])){
		$zeryto11_key= $_GET['key'];
		if(strlen($zeryto11_key)>= 4){ 
			$zeryto11_key=(int) $_GET['key'];
			echo enc($zeryto11_t, $zeryto11_key);
		}
	}
}

*/

?>