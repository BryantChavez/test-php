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
      $queryRegister = sprintf("INSERT INTO tblcoupon(code, discount, dateBeginning, dateEnd) Values('%s','%s', '%s', '%s')",
                mysql_real_escape_string(trim($_POST['code'])),
                mysql_real_escape_string(trim($_POST['discount'])),
                mysql_real_escape_string(trim($_POST['dateBeginning'])),
                mysql_real_escape_string(trim($_POST['dateEnd']))
        ); 

      //ejecutamos el query
      $resQueryRegister = mysql_query($queryRegister, $conexionLocalhost) 
        or die("We're sorry but the query for registering a new user wasn't excuted");

        if ($resQueryRegister) {
            header("Location: index.php");
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

        <form action="create_coupon.php" method="post">
            <?php if(isset($error)) printMsg("Complete the form correctly.","error"); ?>
            <table>
                <tr>
                    <td><label for="discount">Discount:</label></td>
                <td><input type="text" name="discount"/></td>
                </tr>
                <tr>
                    <td><label for="code">Code:</label></td>
                    <td><input type="text" name="code"/></td>
                </tr>
                <tr>
                    <td><label for="dateBeginning">Date Beginning(AAAA/MM/DD):</label></td>
                    <td><input type="text" name="dateBeginning"/></td>
                </tr>
                <tr>
                    <td><label for="dateEnd">Date End(AAAA/MM/DD):</label></td>
                    <td><input type="text" name="dateEnd"/></td>
                </tr>
                
                    <td>&nbsp;</td>
                    <td><br /><input type="submit" value="Activate Coupon" name="sent" /></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>