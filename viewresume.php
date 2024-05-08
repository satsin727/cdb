<?php
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

if($dta['level'] == 1 || $dta['level'] == 2 || $dta['level'] == 3)
{
	
$uid = $dta['uid'];
								$sid = $_GET['rid'];
								$q2 = "select * from resumedb where `resumeid` = $sid";
								$ins3= $conn->prepare($q2);
								$ins3->execute(); 
								$dta2 = $ins3->fetch();
								$mcontents = base64_decode($dta2['html']);
								
								$file = $dta2['filename'];
								if($file)
								{
								$attachment = $dta2['attachment'];
								$ext = strtolower(substr(strrchr($file, '.'), 1));
									if($ext == "pdf" || $ext == "odt" || $ext == "odf" || $ext == "ods" || $ext == "odp"|| $ext == "doct")
									{
										$query = "select * from consultantdetails where `id` = $sid";
										$ins= $conn->prepare($query);
										$ins->execute();
										$data = $ins->fetch();
										$file= "tmp/".$data['fname']."_".$data['lname']."_".strtolower(substr(md5($dta2['filename']), 0, 14))."_Resume.".$ext;
										$tfile=base64_decode($attachment,true);
										$filep = $file;
										$result = file_put_contents($file,$tfile);
										/*header('Pragma: public');
										header('Expires: 0');
										header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
										header('Cache-Control: private', false); // required for certain browsers 
										header('Content-Disposition: attachment; filename="'. basename($file) . '";');
										header('Content-Transfer-Encoding: binary');
										header('Content-Length: ' . filesize($file));
										ob_clean();
										flush();
										//readfile($file); */
										?>
										<table>
										<tr>
										<td style="width: 50%; vertical-align:top"><?php echo $mcontents; ?></td>
										<td style="width: 50%; vertical-align:top"><iframe src = "ViewerJS/#../<?php echo $file; ?>" style="width:100%; height:1024px"  allowfullscreen webkitallowfullscreen></iframe></td>
										
										
										</tr>
										</table>
										<?php
										
									}
									 if($ext == "doc")
									{
											//next code
											$query = "select * from consultantdetails where `id` = $sid";
											$ins= $conn->prepare($query);
											$ins->execute();
											$data = $ins->fetch();
											$file= "tmp/".$data['fname']."_".$data['lname']."_".strtolower(substr(md5($dta2['filename']), 0, 14))."_Resume.".$ext;
											$tfile=base64_decode($attachment,true);
											$filep = $file;
											$result = file_put_contents($file,$tfile);
											
										/* 2nd method for Docx to html
										include('includes/docxtohtml.php');

											$doc = new Docx_reader();
											$doc->setFile($file);

											if(!$doc->get_errors()) {
												$html = $doc->to_html();
												$plain_text = $doc->to_plain_text();

											//	echo $html;
											} else {
												echo implode(', ',$doc->get_errors());
											}
											echo "\n"; */
											
												  if(($fh = fopen($file, 'r')) !== false ) 
												  {
													 $headers = fread($fh, 0xA00);

													 // 1 = (ord(n)*1) ; Document has from 0 to 255 characters
													 $n1 = ( ord($headers[0x21C]) - 1 );

													 // 1 = ((ord(n)-8)*256) ; Document has from 256 to 63743 characters
													 $n2 = ( ( ord($headers[0x21D]) - 8 ) * 256 );

													 // 1 = ((ord(n)*256)*256) ; Document has from 63744 to 16775423 characters
													 $n3 = ( ( ord($headers[0x21E]) * 256 ) * 256 );

													 // 1 = (((ord(n)*256)*256)*256) ; Document has from 16775424 to 4294965504 characters
													 $n4 = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );

													 // Total length of text in the document
													 $textLength = ($n1 + $n2 + $n3 + $n4);

													 $extracted_plaintext = fread($fh, $textLength);

													 // if you want to see your paragraphs in a new line, do this
													 // return nl2br($extracted_plaintext);
												  } 
											 
											
											?>
											<table>
										<tr>
										<td style="width: 50%; vertical-align:top"><?php echo $mcontents; ?></td>
										<td style="width: 50%; vertical-align:top"><?php echo nl2br($extracted_plaintext); ?> </td>
										</tr>
										</table>
											
											<?php
									} 
									 if($ext == "docx")
									{
											//next code
											$query = "select * from consultantdetails where `id` = $sid";
											$ins= $conn->prepare($query);
											$ins->execute();
											$data = $ins->fetch();
											$file= "tmp/".$data['fname']."_".$data['lname']."_".strtolower(substr(md5($dta2['filename']), 0, 14))."_Resume.".$ext;
											$tfile=base64_decode($attachment,true);
											$filep = $file;
											$result = file_put_contents($file,$tfile);
											
											require_once('includes/wordphp.php');
											$rt = new WordPHP(false);
											$html = $rt->readDocument($file);
											?>
											<table>
										<tr>
										<td style="width: 50%; vertical-align:top"><?php echo $mcontents; ?></td>
										<td style="width: 50%; vertical-align:top"><?php echo $html; ?></td>		
										</tr>
										</table>
											
											<?php
									}
								}
								else
								{
									echo $mcontents;
								}
								
								

}
else
{
	echo "<script>
alert('You Need to be valid user to view this page.');
window.location.href='admin.php';
</script>"; 
}
require("includes/footer.php"); 
}
else
{ echo "<script>
alert('Not Authorised to view this page, Not a valid session. Your IP address has been recorded for review. Please Log-in again to view this page !!!');
window.location.href='login.php';
</script>";   }

?>
