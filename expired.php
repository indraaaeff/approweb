<?php 
include "user.php";
include "jam.php";
if ( !isset($_GET['u']) || empty($_GET['u']) )
{
echo'
<center><br>
<br>
<br>

<h2>PAGE NOT FOUND</h2>

<h1 style=font-size:110px;>404</h1>
</center>';
 } else {
switch ( $_GET['u'] ) {
				case BOD_RT_ID : 
					$_SESSION['username'] = BOD_RT;
					break;

				case BOD_HP_ID : 
					$_SESSION['username'] = BOD_HP;
					break;

				case BOD_DL_ID : 
					$_SESSION['username'] = BOD_DL;
					break;
			}
$user=$_SESSION['username'];

?><!DOCTYPE html>
<html lang="en">

<head>
	<title>HTS APP</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="css/sticky-footer-navbar.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body>
	<div class="header-nav expired">
		<nav class="navbar navbar-fixed-top navbar-style">
			<div class="container-fluid" style="padding-left:5%;padding-right:5%;">
				<a class="navbar-brand logo" href="#">Approval</a>
				<div>
					<img class="logo-image" src="img/logo_hts_glowing.png" alt="">
				</div>
			</div>
		</nav>
	</div>
	<div class="header-date">
		<div class="container-fluid" style="padding-left:5%;padding-right:5%;">
			<div class="date">
				<?php echo $hari.",";?> <?php echo $tanggal;?> <?php echo $bulan;?> <?php echo $tahun;?> 
			</div>
			<div id="clock" class="time"></div>
			<div class="user">
				<b>
					<?php echo ucwords($user); ?>
				</b>
			</div>
		</div>
	</div>
	<div class="kotak_user">
		<div class="user_res">
			<b><?php echo ucwords($user);?></b>
		</div>
	</div>
	<div class="container-fluid" style="padding-left:5%;padding-right:5%;padding-top:2%;">
		<div class="row">
			<div class="col-sm-12">
				<div class="alerts" style="line-height:30px;">
					<font color="#FF0000">Data link tidak ditemukan atau sudah expired.!</font>
				</div>			
			</div>
		</div>
	</div>
	<footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright &copy; 2016 PT. Hawk Teknologi Solusi, All Rights Reserved.</p>
      </div>
    </footer>
</body>
<?php } ?>
<script type="text/javascript">
function startTime()
{
var today=new Date()
var h=today.getHours()
var m=today.getMinutes()
var s=today.getSeconds()
var ap="AM";

//to add AM or PM after time

if(h>11) ap="PM";
if(h>12) h=h-12;
if(h==0) h=12;

//to add a zero in front of numbers<10

m=checkTime(m)
s=checkTime(s)

document.getElementById('clock').innerHTML=h+":"+m+":"+s+" "+ap
t=setTimeout('startTime()', 500)
}

function checkTime(i)
{
if (i<10)
{ i="0" + i}
return i
}

window.onload=startTime;

</script>