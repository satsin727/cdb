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
$userid=$dta['uid'];

if(isset($_SESSION['username']) && $dta['sess']==$_SESSION['username'])
{

require("includes/header.php");
$selected = "listall";
require("includes/menu.php");

if($dta['level'] == 1 || $dta['level'] == 2 || $dta['level'] == 3 )
{


if(isset($_GET['do']))
{
	$do="foobar";
	$do=$_GET['do'];	
	$listid=$_GET['lid'];
	?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active"><?php echo $do; ?> List Details</li>
			</ol>
		</div>
		<?php
		if($do=='insert')
	{
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$query ="Select * FROM lists where listid = $listid";
		$ins= $conn->prepare($query);
		$ins->execute();
		$udata = $ins->fetch();
		$status = $udata['status'];
if($udata['uid']==$userid || $dta['level'] == 1 || $dta['level'] == 2 )
		{
			if($status==1) 
			{ 
				$inquery = "UPDATE `lists` SET `status` = '0' WHERE `listid` = $listid";
				$ins= $conn->prepare($inquery);
				$ins->execute();
				echo "<script>
				alert(' List has been Disabled.');
				window.location.href='admin.php?action=listall';
				</script>"; 

			}

			if($status==0) 
			{ 
				$inquery = "UPDATE `lists` SET `status` = '1' WHERE `listid` = $listid";
				$ins= $conn->prepare($inquery);
				$ins->execute();
				echo "<script>
				alert(' List has been Enabled.');
				window.location.href='admin.php?action=listall';
				</script>"; 

			}
		}
	else { 
echo "<script>
				alert(' List is uploaded by another user.');
				window.location.href='admin.php?action=listall';
				</script>"; 


	}
	}	

	else
	{
		echo "<script>
alert('Not a valid command.');
window.location.href='admin.php?action=listall';
</script>";
	}

} //for $do

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

<?php
$connect = mysqli_connect("localhost", "root", "", "testing");
if(isset($_POST["first_name"], $_POST["last_name"]))
{
 $first_name = mysqli_real_escape_string($connect, $_POST["first_name"]);
 $last_name = mysqli_real_escape_string($connect, $_POST["last_name"]);
 $query = "INSERT INTO user(first_name, last_name) VALUES('$first_name', '$last_name')";
 if(mysqli_query($connect, $query))
 {
  echo 'Data Inserted';
 }
}
?>