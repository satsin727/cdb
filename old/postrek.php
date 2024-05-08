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


if(isset($_SESSION['username']) && $dta['sess']==$_SESSION['username'])
{

require("includes/header.php");
require("includes/menu.php");

?> 
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Post Requirement</li>
			</ol>
		</div><!--/.row-->
		
			<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">

					<form action="#" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
<div class="form-group">
								<td width="15%" align="left" valign="top">	<label>Job Title:&nbsp;&nbsp;&nbsp;</label></td>
								<td width="85%" align="left" valign="top"><input name="role" class="form-control-sub" placeholder="Job Title" ></td>
</div> </tr> <tr><td><label>&nbsp;&nbsp;&nbsp;</label></td></tr> <tr>
<div class="form-group">
									<td width="15%" align="left" valign="top"><label>Location:&nbsp;&nbsp;&nbsp;</label></td>
								<td width="5%" align="left" valign="top"><input name="rlocation" class="form-control-in" placeholder="Location"><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Duration:&nbsp;&nbsp;&nbsp;</label>
								<input name="rduration" class="form-control-in" placeholder="Duration in Months"></td>
</div></tr> <tr><td><label>&nbsp;&nbsp;&nbsp;</label></td></tr> <tr>
<div class="form-group">
									<td width="15%" align="left" valign="top"><label>Job Description:&nbsp;&nbsp;&nbsp;</label></td>
								<td width="90%" align="left" valign="top">	<textarea class="ckeditor" name="rdesc" ></textarea> </td>
</div></tr> <tr><td><label>&nbsp;&nbsp;&nbsp;</label></td></tr> 

<div class="form-group">
									<tr> <td width="15%" align="left" valign="top"><label>Skill:</label></td>
								<td width="5%" align="left" valign="top">	<select name="skillid" class="form-control-in">
									<?php
								$conn2 = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
								$q2 = "select * from skill";
								$ins2= $conn2->prepare($q2);
								$ins2->execute();
								$dta2 = $ins2->fetchAll();
								foreach( $dta2 as $row2) { ?>
										<option value="<?php echo $row2['sid']; ?>"><?php echo $row2['skillname']; ?></option>
									<?php } ?></select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>SM:</label>
									<select name="uid" class="form-control-in">
									<?php
									
									if($dta['level'] == 1 || $dta['level'] == 2)
									{
									$conn2 = null;
								$conn2 = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
								$q2 = "select * from users";
								$ins2= $conn2->prepare($q2);
								$ins2->execute();
								$dta2 = $ins2->fetchAll();
								
								foreach( $dta2 as $row2) { ?>
										<option value="<?php echo $row2['uid']; ?>"><?php echo $row2['name']; ?></option>
								<?php } } else { ?> <option value="<?php echo $dta['uid']; ?>"><?php echo $dta['name']; ?></option><?php }?></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="submit" name="save" class="btn btn-primary">Save</button> </td>					
                 </tr>
                 </table>

</form>
						
				</div></div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		
	</div>	<!--/.main-->

<?php
require("includes/footer.php"); 

if (isset($_POST['save']))
{
	if ( empty($_POST['role']) || empty($_POST['rlocation']) || empty($_POST['rduration']) || empty($_POST['rdesc']) || empty($_POST['skillid']) )
{

echo "<script>
alert(' All value is required !!!');
</script>";
}
else {

$role = $_POST['role'];
$rlocation = $_POST['rlocation'];
$rduration = $_POST['rduration'];
$rdesc = $_POST['rdesc'];
$skillid = $_POST['skillid'];
$uid = $_POST['uid'];

$conn= null;
$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );

 $que= $conn->prepare("INSERT INTO `req` (`uid`, `role`, `rlocation`, `rduration`, `rend_client`, `skillid`, `datetime`) VALUES ( :uid, :role, :rlocation, :rduration, NULL, :skillid, CURRENT_TIMESTAMP);");
 $que->bindValue( ":uid", $uid, PDO::PARAM_INT );
 $que->bindValue( ":role", $role, PDO::PARAM_STR );
 $que->bindValue( ":rlocation", $rlocation, PDO::PARAM_STR );
 $que->bindValue( ":rduration", $rduration, PDO::PARAM_STR );
 $que->bindValue( ":skillid", $skillid, PDO::PARAM_INT );
 $que->execute();
 
 $reqid = $conn->lastInsertId();
 
 $que1= $conn->prepare("INSERT INTO `jd` (`reqid`, `rdesc`) VALUES ( :reqid, :rdesc );");
 $que1->bindValue( ":reqid", $reqid, PDO::PARAM_INT );
 $que1->bindValue( ":rdesc", $rdesc, PDO::PARAM_STR );
 $que1->execute();
 
 
 echo "<script>
											alert('Requirement Added.');
											window.location.href='admin.php?action=postreq';
											</script>";
 
} // is check null values

} // isset post submit



}
else
{ echo "<script>
alert('Not Authorised to view this page, Not a valid session. Your IP address has been recorded for review. Please Log-in again to view this page !!!');
window.location.href='login.php';
</script>";   }

?>