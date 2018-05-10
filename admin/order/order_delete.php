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
  $queryGetOrderDetails = sprintf("SELECT * FROM tblorder WHERE id= %d", $_GET['userId']);

  $resQueryGetOrderDetails = mysql_query($queryGetOrderDetails, $conexionLocalhost) or trigger_error("User data couldn't be obtained.");


  //hacemos un fetch para extraer los datos del usuario y poder manipularlos.
  $orderDetails = mysql_fetch_assoc($resQueryGetOrderDetails);

} else {
  header("Location: index.php?error=3");
}


//Evaluamos que el formulario ha sido enviado
if(isset($_POST['sent'])) { 
  
    //Definir el query ejecutable
      $queryOrderDelete= sprintf("DELETE FROM tblorder WHERE id= %d", $_GET['userId']);
      
      //ejecutamos el query
      $resQueryOrderDelete = mysql_query($queryOrderDelete, $conexionLocalhost) 
        or die("We're sorry but the query for deleting the order wasn't excuted");

      //si todo salio bien, redirigimos al usuario al panel
      if($resQueryOrderDelete){
        header("Location: order_list.php?deletedOrder=true");
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

        <form action="order_delete.php?userId=<?php echo $orderDetails['id'];?>" method="post">
            <table>
              <tr>
                    <td><label for="service">Service:</label></td>
                <td><input type="text" name="service" value="<?php echo $orderDetails['service'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="code">Code:</label></td>
                <td><input type="text" name="code" value="<?php echo $orderDetails['code'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="discount">Discount:</label></td>
                    <td><input type="text" name="discount" value="<?php echo $orderDetails['discount'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="dateStart">Date start:</label></td>
                    <td><input type="text" name="dateStart" value="<?php echo $orderDetails['dateStart'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="dateEnd">Date end:</label></td>
                    <td><input type="text" name="dateEnd" value="<?php echo $orderDetails['dateEnd'];?>"/></td>
                </tr>
                
                    <td><input type="hidden" name="id" value="<?php echo $orderDetails['id']?>"></td>
                    <td><br /><input type="submit" value="Delete order" name="sent" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>