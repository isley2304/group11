<?php
session_start();
if(isset($_SESSION['account_no'])){
	$g11_account_no = $_SESSION['account_no'];
	$g11_con = mysqli_connect("localhost","root","root","bank");
	if($_SERVER["REQUEST_METHOD"] == "POST") {
	  $g11_old_password = $_POST['old_password'];
	  $g11_new_password = $_POST['new_password'];
	  $g11_result = mysqli_query($g11_con, "SELECT * FROM login WHERE account_no = '$g11_account_no'");
	  $g11_row = mysqli_fetch_array($g11_result,MYSQLI_ASSOC);
	  $g11_password = $g11_row['pwd'];
	  $g11_email = $g11_row['id'];
	  $g11_secretSalt = "g11.uit.nt213.m21.antt";
	  $g11_message = $g11_secretSalt.$g11_email;
	  $g11_hashed = md5($g11_message);
	  $g11_encryptionMethod = "AES-256-CBC"; 

	//To encrypt
	$g11_old_crypt = openssl_encrypt($g11_hashed, $g11_encryptionMethod, $g11_old_password);
	  if($g11_password == $g11_old_crypt) {
	    $g11_secretSalt = "g11.uit.nt213.m21.antt";
	    $g11_message = $g11_secretSalt.$g11_email;
	    $g11_hashed = md5($g11_message);
	    $g11_encryptionMethod = "AES-256-CBC"; 

	//To encrypt
	$g11_new_crypt = openssl_encrypt($g11_hashed, $g11_encryptionMethod, $g11_new_password);
	    $g11_c = mysqli_query($g11_con, "update login set pwd = '$g11_new_crypt' where account_no = '$g11_account_no';");
	    header("refresh:0;url=success.html");
	  }
	  else {
	    header("refresh:0;url=password.php");
	  }
	}
}
else {
	$message = "You do not have access to this page.";
	echo "<script type='text/javascript'>alert('$message');</script>";
	header("refresh:0;url=../login/login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<title>UIT Bank</title>
    <link rel="icon" href="../asset/img/logo-uit.png" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../../css/response.css">
<link rel="stylesheet" href="../../css/style.css">
<script>
var filter_password = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?=.*[!@#\$%\^&\*]).{8,}$/;
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
function validate() {
      var v1 = document.getElementById("new_password").value;
      var v2 = document.getElementById("new_password_confirmation").value;
      if(v1 != v2) {
        document.getElementById("msg").innerHTML = "Password do not match";
        return false;
      }
      else {
        document.getElementById("msg").innerHTML = "";
        return true;
      }
    }
    function checkPwd(){
	var pwd = document.getElementById("new_password").value;
	if ( !filter_password.test(pwd)){
	  document.getElementById("msgPwd").innerHTML = "Password must contain at least one number, one special character, one uppercase and lowercase letter, and at least 8 or more characters";
	  return false;
	}
	else {
	  document.getElementById("msgPwd").innerHTML ="";
	  return true;
	}
}
function checkAll(){
	if(checkPwd() && validate())
	{ return true;}
	else {
	alert ("Please enter all information correctly!");
	return false;}
}
</script>
</head>
<body>

<div class="topnav" id="myTopnav">
  <img style="float:right; right:1" src="../../img/lg.png" height="44" width="204.8">
  <a href="../dashboard.php"><i class="fa fa-fw fa-calculator "></i>Dashboard</a>
  <a href="../profile.php" class="active"><i class="fa fa-fw fa-id-card-o "></i>Profile</a>
  <a href="../transfer.php"><i class="fa fa-fw fa-cogs "></i>Transfer Money</a>
  <a href="../transactions.php"><i class="fa fa-fw fa-file-text "></i>Transactions</a>
  <a href="../limit.php"><i class="fa fa-fw fa-sliders "></i>Set Limit</a>
  <a href="../logout.php" style="float: right"><i class="fa fa-fw fa-sign-out "></i>Logout</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>
<br><br>
<center>
  <div style="background-color: #4CAF50; height: 45px; width: 100%; color: white; "><h1>Change Password</h1></div>
  <br><br>
  <form method="POST" onsubmit ="return checkAll()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table width="40%">
      <tr>
        <td>
          <input type="password" name="old_password" class="question" id="old_password" required autocomplete="off" />
          <label for="old_password"><span>Old Password</span></label>
        </td>
      </tr>
    </table>
    <table width="40%">
      <tr>
        <td>
          <input type="password" name="new_password" oninput="checkPwd()" class="question" id="new_password" required autocomplete="off" />
          <label for="new_password"><span>New Password</span></label>
          <div style="color: red;" id="msgPwd"></div>
        </td>
      </tr>
    </table>
    <table width="40%">
      <tr>
        <td>
          <input type="password" name="new_password_confirmation" class="question" oninput="validate()" id="new_password_confirmation" required autocomplete="off" />
          <label for="new_password_confirmation"><span>Confirm Password</span></label>
          <div style="color: red;" id="msg"></div>
        </td>
      </tr>
    </table>
    <br>
    <table width="80%">
      <tr>
        <td align="right"><input type="submit" name="submit" value="CHANGE PASSWORD" style="height: 40px; width: 250px; font-size: 20px; color: white; background-color: #4CAF50"></td>
      </tr>
    </table>
  </form>
</center>
</body>
</html>
