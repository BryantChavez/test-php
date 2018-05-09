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

    //Verificamos que los passwords coincidan
  if($_POST['password'] != $_POST['password2']){
    $error[]="The password doesn't match.";
  }


 //Se crea el query para validar si existe el usuario por medio del email
  $queryValidateEmail = sprintf("SELECT id FROM tbluser WHERE userName = '%s' ",
                                mysql_real_escape_string(trim($_POST['userName'])));

  //ejecutamos el query
  $resQueryValidateEmail = mysql_query($queryValidateEmail, $conexionLocalhost) or die("The query for email wasn't executed.");

  if (mysql_num_rows($resQueryValidateEmail)) {
    $error[]="The given user name is already registered.";
  }

  if (!isset($error)){
      $queryRegister = sprintf("INSERT INTO tbluser(userName, firstName, lastName, role, password) Values('%s', '%s', '%s', '%s', '%s')",
                mysql_real_escape_string(trim($_POST['userName'])),
                mysql_real_escape_string(trim($_POST['firstName'])),
                mysql_real_escape_string(trim($_POST['lastName'])),
                mysql_real_escape_string(trim($_POST['rol'])),
                mysql_real_escape_string(trim($_POST['password']))
        ); 

      //ejecutamos el query
      $resQueryRegister = mysql_query($queryRegister, $conexionLocalhost) 
        or die("We're sorry but the query for registering a new user wasn't excuted");

        if ($resQueryRegister) {
            header("Location: login.php");
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

        <form action="register.php" method="post">
            <?php if(isset($error)) printMsg("Complete the form correctly.","error"); ?>
            <table>
                <tr>
                    <td><label for="userName">User name:</label></td>
                    <td><input type="text" name="userName"/></td>
                </tr>
                <tr>
                    <td><label for="firstName">First name:</label></td>
                <td><input type="text" name="firstName"/></td>
                </tr>
                <tr>
                    <td><label for="lastName">Last name:</label></td>
                    <td><input type="text" name="lastName"/></td>
                </tr>
                <tr>
                    <td><label for="password">Password:</label></td>
                    <td><input type="password" name="password" /></td>
                </tr>
                <tr>
                    <td><label for="password2">Confirm password:</label></td>
                    <td><input type="password" name="password2" /></td>
                </tr>
                <tr>
                    <td><label for="rol">Role:</label></td>
                    <td>
                        <select name="rol" id="rol">
                            <option value="empleado" selected="selected">Empleado</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><br /><input type="submit" value="Register user" name="sent" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>