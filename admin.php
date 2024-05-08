<?php

require( "config.php" );

$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['cdbgusername'] ) ? $_SESSION['cdbgusername'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'adduser':
    adduser();
    break;
  case 'addskill':
    addskill();
    break;
  case 'listusers':
    listusers();
    break;
  case 'fetchdb':
    fetchdb();
    break;
  case 'viewdb':
    viewdb();
    break;
  case 'viewresume':
    viewresume();
    break;

  default:
    dashboard();
}


function login() {

  if ( isset( $_POST['login'] ) ) {

    // User has posted the login form: attempt to log the user in
    $u = $_POST['username'];
    $p= $_POST['password']; 
    $mdemail = md5($p); 
    $baseemail = base64_encode($p);
    $code = base64_encode($baseemail); 
    $phash = md5($mdemail.$code); 
    $uhash = md5($u);
  $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
 $query = "select * from users where `username` = :u";
 $ins= $conn->prepare($query);
 $ins->bindValue( ":u", $u, PDO::PARAM_STR );
 $ins->execute();
 $dta = $ins->fetch();
 
 $rid =$dta['uid'];
if($dta['sess']!= 0 )

{
   echo "<script>
alert('Another session is going on !!! Logging in will destroy all remaining sessions.');
window.location.href='lout.php?id="."$rid"."';
</script>";
   
 } else {
    if ( $uhash == $dta['uhash'] && $phash == $dta['password'] ) {

      if($dta['status']==1)
      {

      // Login successful: Create a session and redirect to the admin homepage
      $date = date("Y-m-d H:i:s");
      $_SESSION['cdbgusername'] = md5(md5($u).$date);
      $_SESSION['id']= $dta['uid'];
      $_SESSION['date'] = $date ; 
      $ip= $_SERVER['REMOTE_ADDR'];
      $q2 = "UPDATE `users` SET `sess` = \"".$_SESSION['cdbgusername']."\", `date` = \"".$date."\", `lastloginip` = \"".$ip."\" WHERE `users`.`uid` = \"".$dta['uid']."\" ";
      $inssess= $conn->prepare($q2);
      $inssess->execute();
      header( "Location: admin.php" );
}

else {

    echo "<script>
alert('Your account is disabled. !!!');
window.location.href='login.php';
</script>";
}
    } else {

      // Login failed: display an error message to the user
      echo "<script>
alert('Wrong Login Credentials !!!');
window.location.href='login.php';
</script>";


    }


  }

  } else {

    // User has not posted the login form yet: display the form
    require( "login.php" );
  }

}


function logout() {
  unset( $_SESSION['cdbgusername'] );
  $uid = $_SESSION['id'];
  unset ($_SESSION['id']);
  unset ($_SESSION['date']);  
  $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD);
 $q4 = "UPDATE `users` set `sess` = \"0\" where `uid` = :id ";
 $lout= $conn->prepare($q4);
 $lout->bindValue( ":id", $uid, PDO::PARAM_INT );
 $lout->execute();
  header( "Location: admin.php" );
}
function dashboard() { $selected = "dashboard"; require( "dashboard.php" ); }
function adduser() { $selected = "listusers"; require( "add_user.php" ); }
function addskill() {  $selected = "listconsultants";  require( "add_skill.php" ); }
function listusers() {  $selected = "listusers"; require( "list_users.php" );  }
function fetchdb() {  $selected = "fetchdb"; require( "fetchdetails.php" );  }
function viewdb() {  $selected = "listdb"; require( "listdb.php" );  }
function viewresume() {  $selected = "listdb"; require( "viewresume.php" );  }

?>
