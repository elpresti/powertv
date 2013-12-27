<?php 
  $userBrowser = strtolower($_SERVER['HTTP_USER_AGENT']); //Detect the browser (put in lowercase to aid searching)
  $userAccept= strtolower($_SERVER['HTTP_ACCEPT']); //Detect acceptable document types
  
  function checkidentity($fromThis,$identities){
  //this function iterates through the array identities matching to see if in the $fromThis string
  //returns true if found otherwise false
    foreach ($identities as $identity) {
    	if (stristr($fromThis,$identity)){
    	  return true;//found
    	}
    }
    return false;//not found
  }
  
  if (stristr($userAccept,'wml')) {
     // This can accept wml (Wireless Meta Language files) so let's assume its WAP)
     // trouble is accept can contain wildcards ... but here we go
     $ub="WML";
     }
  else {
  // Lets look at the browser
  //specify an array of identities to match against
  $wapidentity = array('wapbrowser','up.browser','up/4','mib','cellphone','go.web', 'nokia','panasonic','wap','wml-browser','wml','Android','webOS' );// can add other identities to this list
  $pcidentity = array('mozilla','gecko','opera','omniweb','msie','konqueror','safari',	'netpositive' ,'lynx' ,'elinks' ,'links' ,'w3m' ,'webtv' ,'amaya' ,	'dillo' ,'ibrowse' ,'icab' ,'crazy browser' ,'internet explorer'); // can add other identities to this list
  $pspidentity= array('PlayStation Portable'); //can add other identities to this list
  $iPhoneidentity= array('iphone'); //can add other identities to this list
  
  if (checkidentity($userBrowser,$wapidentity)){
      $ub="WML";
      }
    elseif (checkidentity($userBrowser,$iPhoneidentity)){
      $ub="iphone";
      }
    elseif (checkidentity($userBrowser,$pspidentity)){
      $ub="PSP";
      }
    elseif (checkidentity($userBrowser,$pcidentity)){
      $ub= "PC";
      }
    else {
      $ub="WML"; // can't find anything else so let's hope it is WML 
    }
  }
      
  //Code for jSon output based upon the results
  switch ($ub){
    case 'PC':
      echo '{"device":"desktop", "os":"desktop"}';
      exit;
    case 'WML':
      echo '{"device":"mobile", "os":"android"}';
      exit;
    case 'PSP':
      echo '{"device":"mobile", "os":"psp"}';
      exit;
    case 'iphone':
      echo '{"device":"mobile", "os":"ios"}';
      exit;
    }
     
 ?>
