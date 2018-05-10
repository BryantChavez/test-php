<?php 
//redirect to index
 if(!isset($_SESSION)) {
    session_start();
    if(!isset($_SESSION['userId'])) header("Location: login.php");
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="login">

      <div class="txt_navbar" id="logoff"><?php echo (isset($_SESSION ['userFullname'])) ? "Welcome <strong>".$_SESSION['userFullname']."</strong>" :'<a href="login.php">Login</a>';?><br />
      <a href="?logOut=true">Log Out</a><br />
        <?php 
           if ($_SESSION['userLevel']!="empleado"){
            echo ' <a href="admin/user/register.php">Register</a><br />';
            echo ' <a href="admin/client/order_list.php">Client List</a><br />';
            echo ' <a href="admin/user/create_coupon.php">Create Coupon</a><br />';
          }
         ?>
      <a href="admin/user/update.php">Update</a><br />
      <a href="admin/client/register_client.php">Register Client</a><br />
      <a href="admin/order/register_order.php">Register Order</a><br />
      <a href="admin/order/order_list.php">Order List</a><br />

      
    </div>


</body>
</html>

<?php  if(isset($_GET['logOut']) && $_GET['logOut']=='true') {

	session_destroy();
	header("Location: login.php?logOut=true");
}
?>