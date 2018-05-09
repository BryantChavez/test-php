<?php
//redirect to index
if(!isset($_SESSION)) {
    session_start();
    if(!isset($_SESSION['userId']) || $_SESSION['userLevel']!="admin") header("Location: index.php");
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
$queryGetUsers = "SELECT id, firstName, lastName, address FROM tblclient";

//Ejecutamos el query
$resQueryGetUsers = mysql_query($queryGetUsers, $conexionLocalhost) or trigger_error("The query for obtaining all users couldn't be executed.");

//Extraemos los datos de resQueryGetUsers
$userDetail = mysql_fetch_assoc($resQueryGetUsers);

//obtenemos el total de usuarios
$totalUsers = mysql_num_rows($resQueryGetUsers);

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
        <p class="userNames"> <?php echo $userDetail['firstName'].' '.$userDetail['lastName'].' - '.$userDetail['address'];?></p>
        <p class="accionesUsuario"><a href="client_delete.php?userId=<?php echo $userDetail['id'];?>">Delete</a></p>
      </li>
    <?php } while($userDetail = mysql_fetch_assoc($resQueryGetUsers));?>
  </ul>
</body>
</html>