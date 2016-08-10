<?php
   include "connection.php";
   include "jam.php";
   include "user.php";
?>
<!-------- cek data ----------> 

<!-------- end cek data ----------> 
<?php
$user            = ucwords($_POST['user']);
$ppo             = $_POST['no_ppo'];
$grand           = $_POST['grand'];
$end_grand       = number_format($grand);
$key             = $_POST['key'];
$u               = $_POST['u'];
include"rt.php";
include"hp.php";
include"dl.php";                              
?>