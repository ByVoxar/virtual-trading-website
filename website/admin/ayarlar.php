<?php
include "auth_check.php";
include "../includes/db.php";

if(isset($_POST['guncelle'])){
    $hero = $_POST['hero_img'];
    $about = $_POST['about_img'];
    
    $conn->query("UPDATE settings SET hero_img='$hero', about_img='$about' WHERE id=1");
    echo "Images updated successfully!";
}
?>

<form method="POST">
    <h3>Manage Homepage Images</h3>
    <label>Hero Image:</label>
    <input type="text" name="hero_img" value="img/analogo.png"><br>
    
    <label>About Us Image:</label>
    <input type="text" name="about_img" value="img/analogo1.png"><br>
    
    <button type="submit" name="guncelle">Save Settings</button>
</form>