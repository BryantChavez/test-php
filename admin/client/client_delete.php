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
  if (isset($_GET['userId']) && is_numeric($_GET['userId'])) {
  //obtenemos los datos del usuario logeado
  $queryGetUserDetails = sprintf("SELECT * FROM tblclient WHERE id= %d", $_GET['userId']);

  $resQueryGetUserDetails = mysql_query($queryGetUserDetails, $conexionLocalhost) or trigger_error("User data couldn't be obtained.");


  //hacemos un fetch para extraer los datos del usuario y poder manipularlos.
  $userDetails = mysql_fetch_assoc($resQueryGetUserDetails);

} else {
  header("Location: index.php?error=3");
}


//Evaluamos que el formulario ha sido enviado
if(isset($_POST['sent'])) { 
  
    //Definir el query ejecutable
      $queryUserDelete= sprintf("DELETE FROM tblclient WHERE id= %d", $_GET['userId']);
      
      //ejecutamos el query
      $resQueryUserDelete = mysql_query($queryUserDelete, $conexionLocalhost) 
        or die("We're sorry but the query for deleting the user wasn't excuted");

      //si todo salio bien, redirigimos al usuario al panel
      if($resQueryUserDelete){
        header("Location: order_list.php?deletedUser=true");
      }
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
<body>

        <form action="client_delete.php?userId=<?php echo $userDetails['id'];?>" method="post">
            <table>
                <tr>
                    <td><label for="firstName">First name:</label></td>
                <td><input type="text" name="firstName" value="<?php echo $userDetails['firstName'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="lastName">Last name:</label></td>
                    <td><input type="text" name="lastName" value="<?php echo $userDetails['lastName'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="address">Address:</label></td>
                    <td><input type="text" name="address" value="<?php echo $userDetails['address'];?>"/></td>
                </tr>
                
                    <td><input type="hidden" name="id" value="<?php echo $userDetails['id']?>"></td>
                    <td><br /><input type="submit" value="Delete client" name="sent" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>