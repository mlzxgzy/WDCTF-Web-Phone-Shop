<?php
session_start();
error_reporting(0);
require "config.php";

$mysqli = new mysqli("localhost", $dbuser,$dbpass, $db);

if ($mysqli->connect_errno) {
 printf("Connect failed: %s\n", $mysqli->connect_error);
 exit();
}

if (!isset($_SESSION['name']))
{
    header("Location:login.php");
    exit();
}


$name = $_SESSION['name'];
$query = "select name, price from phone";
$result = $mysqli->query($query);

echo "可购买的手机: <br>";
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
    echo "&nbsp;&nbsp;&nbsp;&nbsp;".$row['name'] . ": " . $row['price'];
    echo "<br />";
}

$stmt = $mysqli->prepare("select balance,id from users where name=?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->bind_result($balance,$userid);
$stmt->fetch();
$stmt->close();

echo "<br>用户信息: <br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;用户名: ".$name."<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;用户ID: ".$userid."<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;用户钱包: ".$balance."<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;拥有的手机: ".$_SESSION['phone']."<br>";

echo "<br>游戏信息: <br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;"."你需要靠不正当手段赚取到8000元"."<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;"."来购买iphoneXR"."<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;"."买完了你就是人生赢家"."<br>";

?>
<form action="#" method="get">
  <p>
  要买还是退?&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="radio" name="action" value="buy" onclick="document.getElementById('phone').style.display='';">买</input>
  <input type="radio" name="action" value="refund" onclick="document.getElementById('phone').style.display='none';">退</input>
  </p>
  <p id="phone" style="display: none">
  什么手机?
  <input type="radio" name="phone" value="nokia">诺基亚</input>
  <input type="radio" name="phone" value="mi">小米</input>
  <input type="radio" name="phone" value="huawei">华为</input>
  <input type="radio" name="phone" value="iphoneXR">iPhoneXR</input>
  </p>
  <input type="submit" value="下单" />
  <input type="submit" value="刷新" onclick="location.search='';location.reload();"/>
</form>
<?php
if (isset($_GET['action']))
{
    $action = $_GET['action'];
    
    if ($action === 'buy')
    {
        $phone = $_GET['phone'];

        $stmt= $mysqli->prepare("select price from phone where name=?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->bind_result($price);
        $stmt->fetch();
        $stmt->close();

        if ($price <= $balance)
        {
            $_SESSION['phone'] = $phone;
            sleep(1);
            $result = $mysqli->query("update users set balance=balance-$price where id=$userid");
            
            if(result)
            {
                echo "购买成功<br>";
                echo "你的手机: ".$_SESSION['phone'];
            }else{
                echo "购买失败";
            }
            

        }else{
            echo "账户余额不足";
        }
    }
    if ( $action === 'refund')
    {
        if (isset($_SESSION['phone']))
        {
$phone = $_SESSION['phone'];
$stmt = $mysqli->prepare("select price from phone where name=?");
$stmt->bind_param("s", $phone);
$stmt->execute();
$stmt->bind_result($price);
$stmt->fetch();
$stmt->close();

$result = $mysqli->query("update users set balance=balance+$price where id=$userid");
            unset($_SESSION['phone']);
            

            echo "退款成功<br>";

        }else{
			echo "你还没买东西呢同志<br>";
		}
    }
   
}
if (isset($_SESSION['phone']))
{
    if ($_SESSION['phone'] === "iphoneXR")
    {
        echo '<br>'.$flag;
    }
}

?>
