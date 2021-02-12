<?php
include_once 'db/connect_db.php';

$id = @$_POST["id"];
$resi = @$_POST["resi"];
$select = $pdo->prepare("UPDATE `tbl_invoice` SET `no_resi` = '$resi' WHERE `inv_id` = $id;");
$select->execute();
$count = $select->rowCount();
header('Content-Type: application/json');

if ($count == '0') {
    echo json_encode(array(
        "status" => 0,
        "message" => "Failed"
    ));
} else {
    echo json_encode(array(
        "status" => 1,
        "message" => "Success"
    ));
}

