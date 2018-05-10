
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
    <link rel="stylesheet" href="Sitio/css/style.css">
    <meta charset="UTF-8"
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in</title>
</head>
<body style="background-color:#89CCCC">
<form class="form-signin form-top" id='form-top'>
    <?php 
        if(isset($error)) printMsg("The field User/Password are incorrect.","error");
        if(isset($_GET['error']) && $_GET['error'] == "2") printMsg("You can't access this resource without logging in first or without the required privileges.","announce");
    ?>
      <img class="imagen-log" src="Sitio/img/icons/firstlog.png" alt="" width="200" height="200">
      <label for="inputEmail"  <?php if(isset($_POST['user'])) echo 'value="'.$_POST['user'].'"';?>></label>
      <input type="email" id="inputEmail" class="form-control col-md-3 offset-md-4" placeholder="Email address" required autofocus>
      <input type="password" id="inputPassword" class="form-control col-md-3 offset-md-4" placeholder="Password" required>
      <div class="checkbox mb-3">
      </div>
      <button class=" btn-lg btn-primary btn-block col-md-3 offset-md-4" style='background-color:orange; border-color:orange;' type="submit">Sign in</button>

    </form>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</body>
</html>

<?php
  if (isset($resQueryValidateUser)) mysql_free_result($resQueryValidateUser);
?>

