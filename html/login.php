<html>
    <body>
        <form action="login.php" method="POST">
            <input type='text' name='name'>
            <input type='password' name='password'>
            <input type='submit' value="登陆">
            <input type='button' value="注册" onclick="window.location='register.php'">
        </form>
    </body>
</html>

<?php
session_start();
require "config.php";

$mysqli = new mysqli("localhost", $dbuser,$dbpass, $db);

if ($mysqli->connect_errno) {
 printf("Connect failed: %s\n", $mysqli->connect_error);
 exit();
}
if ($_POST['name'])
{
    $name = $_POST['name'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("select * from users where name=? and password=?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $stmt->store_result();
    echo $stmt->num_rows;
    if ($stmt->num_rows>0)
    {
        echo "login success";
        $_SESSION['name'] = $name;
        header("Location:index.php");
        exit();
    }
}




