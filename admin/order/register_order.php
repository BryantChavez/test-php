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

//validations
if(isset($_POST['sent'])) {

    // no empty fields
    foreach($_POST as $shirt => $pants) {
        
        if($pants == "") $error[] = "The field $shirt is required";
        
    }

  if (!isset($error)){
      $queryRegister = sprintf("INSERT INTO tblorder(idUser, code, discount, dateStart, dateEnd, service) Values('%d','%s', '%d', '%s', '%s', '%s')",
                mysql_real_escape_string(trim($_SESSION['userId'])),
                mysql_real_escape_string(trim($_POST['code'])),
                mysql_real_escape_string(trim($_POST['discount'])),
                mysql_real_escape_string(trim($_POST['dateStart'])),
                mysql_real_escape_string(trim($_POST['dateEnd'])),
                mysql_real_escape_string(trim($_POST['service']))
        ); 

      //ejecutamos el query
      $resQueryRegister = mysql_query($queryRegister, $conexionLocalhost) 
        or die("We're sorry but the query for registering a new order wasn't excuted");

        if ($resQueryRegister) {
            header("Location: ../../index.php");
        }
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

        <form action="register_order.php" method="post">
            <?php if(isset($error)) printMsg("Complete the form correctly.","error"); ?>
            <table>
                
                <tr>
                    <td><label for="code">Code:</label></td>
                <td><input type="text" name="code"/></td>
                </tr>
                <tr>
                    <td><label for="discount">Discount:</label></td>
                    <td><input type="text" name="discount"/></td>
                </tr>
                <tr>
                    <td><label for="dateStart">Date Start:</label></td>
                    <td><input type="text" name="dateStart"/></td>
                </tr>
                <tr>
                    <td><label for="dateEnd">Date End:</label></td>
                    <td><input type="text" name="dateEnd"/></td>
                </tr>
                <tr>
                    <td><label for="service">Service:</label></td>
                    <td><input type="text" name="service"/></td>
                </tr>
                
                    <td>&nbsp;</td>
                    <td><br /><input type="submit" value="Register order" name="sent" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>