<?php
include "auth_check.php";
include "../includes/db.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $galeri_res = $conn->query("SELECT image_path FROM product_images WHERE product_id = $id");
    while ($galeri_data = $galeri_res->fetch_assoc()) {
        $gal_file = "../uploads/" . $galeri_data['image_path'];
        if (file_exists($gal_file)) {
            unlink($gal_file);
        }
    }
    
    $conn->query("DELETE FROM product_images WHERE product_id = $id");

    $res = $conn->query("SELECT image FROM products WHERE id = $id");
    $data = $res->fetch_assoc();
    if ($data) {
        $main_file = "../uploads/" . $data['image'];
        if (file_exists($main_file)) {
            unlink($main_file);
        }
    }

    $delete = $conn->query("DELETE FROM products WHERE id = $id");

    if ($delete) {
        header("Location: urun_liste.php?status=success");
    } else {
        header("Location: urun_liste.php?status=error");
    }
} else {
    header("Location: urun_liste.php");
}
exit();
?>