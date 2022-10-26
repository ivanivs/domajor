<?php
$weight = $_POST['weight'];
$price = $_POST['price'];
if ($weight<0.5)
{
    $weight = 0.5;
}
$priceResult = $weight*15+($price*0.15)+$price+$_POST['priceUSA'];
print $priceResult.' USD';