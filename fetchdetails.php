<?php
require( "config.php" );
if($_SESSION['id'])
{
$sessid = $_SESSION['id'];
}
else
{
	header( "Location: admin.php" ); 

}
$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
$query = "select * from users where `uid` = :u";
$ins= $conn->prepare($query);
$ins->bindValue( ":u", $sessid, PDO::PARAM_STR );
$ins->execute();
$dta = $ins->fetch();


if(isset($_SESSION['cdbgusername']) && $dta['sess']==$_SESSION['cdbgusername'])
{

require("includes/header.php");
$selected = "fetchdb";
require("includes/menu.php");

if($dta['level'] == 1 || $dta['level'] == 2 || $dta['level'] == 3)
{
	?>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">	
<?php
$i=0;
require("gmail_login.php");
$emails = imap_search($inbox,'UNSEEN');

/* if emails are returned, cycle through each... */
if($emails) {
	
	/* begin output var */
	$output = '';
	
	/* put the newest emails on top */
	rsort($emails);
	
	/* for every email... */
	foreach($emails as $email_number) {

					$headers = imap_fetchheader($inbox, $email_number, FT_PREFETCHTEXT);
					$body = imap_body($inbox, $email_number);
					file_put_contents('mail/latest.eml', $headers . "\n" . $body);

				$html = null;
				$attachment = null;
				$path    = 'mail/';
				
				$files = array_diff(scandir($path), array('.', '..'));
				//$files = scandir($path);
				foreach($files as $file)
				{
				$contents = file_get_contents($path.$file);
				if(preg_match_all("/Subject:\s(.*)\n(.*)\sContent-Type/",$contents,$subject_array)) { $subject = trim(implode($subject_array[1]))." ".trim(implode($subject_array[2])); } 
				else if(preg_match_all("/Subject:\s(.*)\sContent-Type/",$contents,$subject_array))
				{
				$subject = trim(implode($subject_array[1]));
				}
				else { $subject=""; }
				//echo $contents;
				$contents = str_replace("\n","",$contents);

				if(preg_match_all("/name=(.+)Content-Disposition/",$contents,$filename))
				{
				$filen=trim(implode($filename[1]));
				$pattern = "/----boundary_(.+)attachment(.+)----boundary/"; 
				preg_match_all($pattern, trim($contents), $matches);
				$attachment = implode($matches[2]);
				$htmlp1 = implode($matches[1]);
				$p2 = "/Content-Transfer-Encoding:\sbase64(.+)----boundary/"; 
				preg_match_all($p2, trim($htmlp1), $email);
				$html = implode($email[1]);
				}
				else 
				{
				$filen="";
				$pattern = "/Content-Transfer-Encoding:\sbase64\s+(.*)/"; 
				if(preg_match_all($pattern, trim($contents), $matches))
				{
				$html = implode($matches[1]);
				}
				}

				if($html != null )
				{
				$mcontents = base64_decode($html);

				$pc = str_replace(" ","",$mcontents);
				$pc = str_replace("(","",$pc);
				$pc = str_replace(")","",$pc);
				$pc = str_replace("-","",$pc);
				$pc = str_replace("\n","",$pc);
				$pc = str_replace("\r","",$pc);

				if(preg_match_all("/(\d{10})/", $pc, $output))
					{
				$alt_phone = implode($output[0]);
					}

				if(preg_match_all("/<FirstName>(.*)<\/FirstName>/",$pc,$first_name)){$fname =  trim(implode($first_name[1])); } else { $fname = ""; }
				if(preg_match_all("/<City>(.*)<\/City>/",$pc,$city_array)) { $city = trim(implode($city_array[1])); } else { $city = ""; }
				if(preg_match_all("/<LastName>(.*)<\/LastName>/",$pc,$last_name)) { $lname =  trim(implode($last_name[1])); } else { $lname =  ""; }
				if(preg_match_all("/<PostalCode>(.*)<\/PostalCode>/",$pc,$zipcode_array)) { $zip = trim(implode($zipcode_array[1])); } else { $zip = ""; }
				if(preg_match_all("/<StateAbbrevMsgMessageID.*?>(.*)<\/StateAbbrevMsg>/",$pc,$statecode_array)) { $state = trim(implode($statecode_array[1])); } else { $state = ""; }
				if(preg_match_all("/<EmailAddress>(.*)<\/EmailAddress>/",$pc,$email_array)){ $email = trim(implode($email_array[1])); } else { $email = ""; }
				if(preg_match_all("/<Number>(.*)<\/Number>/",$pc,$number_array)) { $number = trim(implode($number_array[1])); } else { $number = ""; }

				$conn = null;
				$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
				$update = 0;
				$checkifemailexist = "SELECT * FROM `consultantdetails` WHERE `email` = \"$email\";";
				$cq = $conn->prepare($checkifemailexist);
				$cq->execute();
				$cdta = $cq->fetch();
				$cid = $cdta['id'];
				if($cdta['id'] != "" || $cdta['id'] != null)
				{
					$inquery = "UPDATE `consultantdetails` SET `skill` = '$subject', `fname` = '$fname', `lname` = '$lname', `city` = '$city', `state` = '$state', `zipcode` = '$zip', `email` = '$email', `phone` = '$number', `alt_phone` = '$alt_phone' WHERE `consultantdetails`.`id` = $cid;";
					$update = 1;
				}
				else 
				{
				$inquery = "INSERT INTO `consultantdetails` (`skill`, `fname`, `lname`, `city`, `state`, `zipcode`, `email`, `phone`, `alt_phone`) VALUES ('$subject', '$fname', '$lname', '$city', '$state', '$zip', '$email', '$number', '$alt_phone');";
				}
				$ins= $conn->prepare($inquery);
				$ins->execute();
				if($update == 1)
					{	$resumeid = $cid;
						$inquery2 = "UPDATE `resumedb` SET `html` = '$html', `filename` = '$filen', `attachment` = '$attachment' WHERE `resumedb`.`resumeid` = $resumeid;";
					}
					else
					{ 
						$resumeid = $conn->lastInsertId();
						$inquery2 = "INSERT INTO `resumedb` (`resumeid`, `html`, `filename`, `attachment`) VALUES ('$resumeid', '$html', '$filen', '$attachment');";
					}
				if($resumeid)
				{
				$ins2= $conn->prepare($inquery2);
				$ins2->execute();
				$i=$i+1;
				}
				//echo $state;
				//echo $inquery2;
				//echo trim(implode($statecode_array[1]));

				} // if html

				unlink($path.$file);


} // for each mail file exist */

imap_delete($inbox,$email_number);
				}
	imap_close($inbox);
}
/*
else {
echo "<script>
alert('No new resumes.');
window.location.href='admin.php';
</script>"; }*/

//$files = array_diff(scandir($path), array('.', '..'));
echo $i." Databases Added";

?>
</div>
<?php
} //for admin
else
{
	echo "<script>
alert('You Need to be Admin to view this page.');
window.location.href='admin.php';
</script>"; 
}

?>
</div>

<?php
require("includes/footer.php"); 
}
else
{ echo "<script>
alert('Not Authorised to view this page, Not a valid session. Your IP address has been recorded for review. Please Log-in again to view this page !!!');
window.location.href='login.php';
</script>";   }

?>
