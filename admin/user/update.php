<?php
//redirect to index
if(!isset($_SESSION)) {
    session_start();
    if(!isset($_SESSION['userId'])) header("Location: login.php");
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


//Obtenemos los datos del usuario logeado
$queryGetUserDetails = "SELECT * FROM tbluser WHERE id=".$_SESSION['userId'];

$resQueryGetUserDetails = mysql_query($queryGetUserDetails, $conexionLocalhost) or trigger_error("User data couldn't be obtained.");

//Hacemos un fetch para extraer los datos del usuario y poder manipularlos.
$userDetails = mysql_fetch_assoc($resQueryGetUserDetails);


//validations
if(isset($_POST['sent'])) {

    // no empty fields
    foreach($_POST as $shirt => $pants) {
        
        if($pants == "") $error[] = "The field $shirt is required";
        
    }

    //Verificamos que los passwords coincidan
  if($_POST['password'] != $_POST['password2']){
    $error[]="The password doesn't match.";
  }

  if ($error) {
    
  //Definir el query ejecutable
      $queryUserEdit = sprintf("UPDATE tblUsers SET userName ='%s', firstName='%s', lastName='%s',
      role='%s', password='%s', WHERE id=%d",
          mysql_real_escape_string(trim($_POST['userName'])),
          mysql_real_escape_string(trim($_POST['firstName'])),
          mysql_real_escape_string(trim($_POST['lastName'])),
          mysql_real_escape_string(trim($_POST['rol'])),
          mysql_real_escape_string(trim($_POST['password'])),
          $_POST['id']
        );

      //ejecutamos el query
      $resQueryUserEdit = mysql_query($queryUserEdit, $conexionLocalhost) 
        or die("We're sorry but the query for updating the user wasn't excuted");

      //si todo salio bien, redirigimos al usuario al panel
      if($resQueryUserEdit){
        header("Location: index.php?updated=true");
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

        <form action="update.php?userId=<?php echo $userDetails['id'];?>" method="post">
            <?php if(isset($error)) printMsg("Complete the form correctly.","error"); ?>
            <table>
                <tr>
                    <td><label for="userName">User name:</label></td>
                    <td><input type="text" name="userName" value="<?php echo $userDetails['userName'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="firstName">First name:</label></td>
                <td><input type="text" name="firstName" value="<?php echo $userDetails['firstName'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="lastName">Last name:</label></td>
                    <td><input type="text" name="lastName" value="<?php echo $userDetails['lastName'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="password">Password:</label></td>
                    <td><input type="password" name="password" value="<?php echo $userDetails['password'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="password2">Confirm password:</label></td>
                    <td><input type="password" name="password2" value="<?php echo $userDetails['password'];?>"/></td>
                </tr>
                <tr>
                    <td><label for="rol">Role:</label></td>
                    <td>
                        <select name="rol" id="rol">
                            <option value="empleado" <?php if($userDetails['role']=="empleado") echo 'selected="selected"'; ?>>Empleado</option>
                            <option value="admin" <?php if($userDetails['role']=="admin") echo 'selected="selected"'; ?>>Administrator</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="hidden" name="id" value="<?php echo $userDetails['id']?>"></td>
                    <td><br /><input type="submit" value="Register user" name="sent" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>