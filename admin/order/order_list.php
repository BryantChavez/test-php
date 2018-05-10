<?php
//redirect to index
if(!isset($_SESSION)) {
    session_start();
    if(!isset($_SESSION['userId'])) header("Location: index.php");
} 

//database connection
include("../../conection/conectionLocalhost.php");
function printMsg($msg, $type)
{
    echo "<div class=\"message-container $type\">";
    if (is_array($msg)) {
        echo '<ul>';
        foreach ($msg as $field => $data) {
            echo "<li>$data</li>";
        }
        echo '</ul>';
    } else {
        echo $msg;
    }
    echo "</div>";
}


//Obtenemos todos los usuarios de la base de datos.
$queryGetOrders = "SELECT id, idUser, code, dateStart, dateEnd, service FROM tblorder";

//Ejecutamos el query
$resQueryGetOrders = mysql_query($queryGetOrders, $conexionLocalhost) or trigger_error("The query for obtaining all orders couldn't be executed.");

//Extraemos los datos de resQueryGetUsers
$orderDetail = mysql_fetch_assoc($resQueryGetOrders);

//obtenemos el total de usuarios
$totalOrders = mysql_num_rows($resQueryGetOrders);

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
<body>
    <ul class="userList">
    <?php 
    do{ ?>
      <li>
        <p class="userNames"> <?php echo $orderDetail['service'].' '.$orderDetail['code'].' '.$orderDetail['dateStart'].' - '.$orderDetail['dateEnd'];?></p>
        <p class="accionesUsuario"><a href="order_delete.php?userId=<?php echo $orderDetail['id'];?>">Delete</a></p>
      </li>
    <?php } while($userDetail = mysql_fetch_assoc($resQueryGetOrders));?>
  </ul>
</body>
</html>