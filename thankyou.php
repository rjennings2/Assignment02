<?php

/*******w******** 
    
    Name: Rylee Jennings
    Date: May 27th, 2024
    Description: Server-Side User Input Validation
****************/

$itemDescription = ["MacBook", "The Razer", "WD My Passport", "Nexus 7", "DD-45 Drums"];
$itemPrice = [1899.99, 79.99, 179.99, 249.99, 119.99];

function my_filter_input(){
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $postal = filter_input(INPUT_POST, 'postal', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[ABCEGHJKLMNPRSTVXY]\d[A-Z] \d[A-Z]\d$/i")));
    $cardnum = filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^\d{10}$/"))); 
    $cardmonth = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT, array("options"=>array("min_range"=>1, "max_range"=>12))); 
    $cardyear = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT, array("options"=>array("min_range"=>date("Y"), "max_range"=>date("Y") + 5))); 
    $cardType = $_POST['cardtype'];
    $fullname = trim($_POST['fullname']);
    $cardname = trim($_POST['cardname']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $nonBlankFields = !empty($fullname) && !empty($cardname) && !empty($address) && !empty($city);
    $validProvinces = ["AB", "BC", "MB", "NB", "NL", "NS", "NT", "NU", "ON", "PE", "QC", "SK", "YT"];
    $province = filter_input(INPUT_POST, 'province', FILTER_CALLBACK, array("options" => function($value) use ($validProvinces) {
            return in_array($value, $validProvinces) ? $value : false;
        }
    ));
    $validProvinceSelected = $province !== false; 
    $quantity = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);
  

    if ($email !== false && $postal !== false && $cardnum !== false && $cardmonth !== false && $cardyear !== false && $cardType !== false && $nonBlankFields && $validProvinceSelected && $quantity !== false) {
        return array(
            'email' => $email,
            'postal' => $postal,
            'cardnumber' => $cardnum,
            'month' => $cardmonth,
            'year' => $cardyear,
            'cardtype' => $cardType,
            'qty' => $quantity
        );
    } else {
        return false; 
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="thankcss.css">
    <title>Thanks for your order!</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <?php if(my_filter_input()): ?>
    <h1><?= "Thanks for your order {$_POST['fullname']}." ?></h1>
    <h2><?= "Here's a summary of your order:" ?></h2>
    <h3><?= "Address Information" ?></h3>
    <table>
        <tr>
            <td>Address:</td>
            <td><?= "{$_POST['address']}"?></td>
        </tr>
        <tr>
            <td>City:</td>
            <td><?= "{$_POST['city']}"?></td>
        </tr>
        <tr>
            <td>Province:</td>
            <td><?= "{$_POST['province']}"?></td>
        </tr>
        <tr>
            <td>Postal Code:</td>
            <td><?= "{$_POST['postal']}"?></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?= "{$_POST['email']}"?></td>
        </tr>
    </table>
    <h3><?= "Order Information" ?></h3>
    <table>
        <tr>
            <th>Quantity</th>
            <th>Description</th>
            <th>Cost</th>
        </tr>
       <?php
        for ($i = 1; $i <=5; $i++) {
            $quantity = $_POST["qty$i"];
            if ($quantity > 0){
                $description = $itemDescription[$i - 1];
                $cost = $itemPrice[$i - 1] * $quantity;

                echo "<tr>";
                echo "<td>$quantity</td>";
                echo "<td>$description</td>";
                echo "<td>$cost</td>";
                echo "</tr>";

            }
        }
        ?>
    </table>    
    <?php else: ?>
        <h1>This form could not be processed.</h1>
    <?php endif ?>
</body>
</html>