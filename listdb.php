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

require("includes/header.php");
$selected = "listdb";
require("includes/menu.php");

if($dta['level'] == 1 || $dta['level'] == 2 || $dta['level'] == 3)
{
$uid = $dta['uid'];
if($dta['level'] == 1 || $dta['level'] == 2 )
{
	
$query = "SELECT * FROM `consultantdetails` order by id desc";
//$query = "SELECT * FROM `consultantdetails` WHERE `updated` > CURRENT_DATE";
}
elseif ($dta['level'] == 3)
{
$query = "SELECT * FROM `consultantdetails` WHERE `updated` > CURRENT_DATE";}
$ins= $conn->prepare($query);
$ins->execute();
$data = $ins->fetchAll();
$conn=null;
?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Consultant Lists</li>
			</ol>
</div>
<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<table data-toggle="table"  data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="uid" data-sort-order="asc">
						    <thead>
						    <tr>
						        <th data-field="uid" data-sortable="true">ID</th>
						        <th data-field="Consultant Name"  data-sortable="true">Consultant Name</th>
						        <th data-field="Skill" data-sortable="true">Skill</th>
						        <th data-field="Location" data-sortable="true">Location</th>
						        <th data-field="Email" data-sortable="true">Email</th>
						        <th data-field="Phone" data-sortable="true">Phone</th>
						        <th data-field="Actions" data-sortable="true">Actions</th>
						    </tr>
						    </thead>
						   <tbody>
<?php
$i=1;

$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
foreach( $data as $row) { 	

$sid = $row['id'];
								$q2 = "select * from resumedb where `resumeid` = $sid";
								$ins3= $conn->prepare($q2);
								$ins3->execute(); 
								$dta2 = $ins3->fetch();
								if(isset($dta2['html']))
								{
								$mcontents = base64_decode($dta2['html']);
								}
								
								
	?>
    <tr>
  		<td data-order="<?php echo $i; ?>"> <?php echo $i; $i=$i+1;  ?></td>
    	<td data-search="<?php echo $row['fname']." ".$row['lname']; ?>"> <?php echo $row['fname']." ".$row['lname']; ?></td>
    	<td data-search="<?php echo $row['skill']; ?>"> <?php echo $row['skill']; ?></td>    	
    	<td data-search="<?php echo $row['city'].", ".$row['state']; ?>"> <?php echo $row['city'].", ".$row['state']; ?></td> 	
    	<td data-search="<?php echo $row['email']; ?>"> <?php echo $row['email']; ?></td>     	
    	<td data-search="<?php echo $row['phone']; ?>"> <?php echo $row['phone']; ?></td>     	
    	 <td> <button> <a href="admin.php?action=viewresume&rid=<?php echo $sid; ?>" target="_blank">Open Resume</a></button></td>
    </tr>
    <?php  //for if
} //foreach
$conn=null; 
?>
						   </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

</div>
<?php

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
