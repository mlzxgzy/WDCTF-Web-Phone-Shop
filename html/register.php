<html>
    <body>
        <form action="register.php" method="POST">
            <input type='text' name='name'>
            <input type='password' name='password'>
            <input type='submit' value="注册">
        </form>
    </body>
</html>

<?php
require "config.php";

$mysqli = new mysqli("localhost", $dbuser,$dbpass, $db);

if ($mysqli->connect_errno) {
 printf("Connect failed: %s\n", $mysqli->connect_error);
 exit();
}
if (isset($_POST['name']))
{
    $name = $_POST['name'];
    $password = $_POST['password'];
    $init = 1000;
    if ($stmt = $mysqli->prepare("SELECT * FROM users WHERE name=?"))
    {
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows>0)
        {
            echo "the username has been reistered";
            $stmt->close();
        }else{
            $stmt->close();
            $stmti = $mysqli->prepare("INSERT INTO users(name,password,balance) VALUES (?,?,?)");
            $stmti->bind_param("ssi", $name, $password,$init);
            $stmti->execute();
            if ($stmti->affected_rows>0)
            {
                echo "register success";
                header("Location:login.php");
                exit();
            }
        }
    }
    
}




