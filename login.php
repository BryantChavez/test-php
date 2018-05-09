<?php
//redirect to index
 if(!isset($_SESSION)) {
    session_start();
    if(isset($_SESSION['userId'])) header("Location: index.php");
} 

include("conection/conectionLocalhost.php");

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
    foreach ($_POST as $field => $data) {
        $tempdata = trim($data);
        if (empty($tempdata) && $field!="sent") {
            $error[] = sprintf("The field %s is required", $field);
        }
    }

        if (!isset($error)) {
        
        // create query
        $queryValidateUser = sprintf("SELECT id, userName, firstName, lastName, role FROM tbluser WHERE userName = '%s' AND password = '%s'",
                mysql_real_escape_string(trim($_POST['user'])),
                mysql_real_escape_string(trim($_POST['password']))
        ); 
        
            // run query
        $resQueryValidateUser = mysql_query($queryValidateUser, $conexionLocalhost) or die("The query for validating the user couldn't be executed");

        // Contamos los resultados obtenidos, 0 = no hay registro que cumpla con los criterios email y ó password; 1 = se encontró unj registro que satisface ambos criterios
        if(mysql_num_rows($resQueryValidateUser)) {
            $userData = mysql_fetch_assoc($resQueryValidateUser);
            $_SESSION['userId'] = $userData['id'];
            $_SESSION['userName'] = $userData['userName'];
            $_SESSION['userFullname'] = $userData['firstName']." ".$userData['lastName'];
            $_SESSION['userLevel'] = $userData['role'];
            header("Location: index.php?login=true");
        }
        else {
            $error[] = "The user email/password didn't match... please check your credentials and try again.";
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
        <form action="login.php" method="post">
            <?php 
                if(isset($error)) printMsg("The field User/Password are incorrect.","error");
                if(isset($_GET['error']) && $_GET['error'] == "2") printMsg("You can't access this resource without logging in first or without the required privileges.","announce");
            ?>
            <div class="field">
                <label for="user">User</label>
                <input type="text" name="user" <?php if(isset($_POST['user'])) echo 'value="'.$_POST['user'].'"';?> placeholder="Ej. HarryStyles">
            
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Ej. 12Three!">
            </div>
            <button type="submit" name="sent">Log In</button>
            <tr><h1><a href="register.php">Register</a></h1></tr>
        </form>
    </div>
</body>
</html>

<?php
  if (isset($resQueryValidateUser)) mysql_free_result($resQueryValidateUser);
?>