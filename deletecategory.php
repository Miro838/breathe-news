<?php

include "db/connection.php";
$id=$_GET["delete"];
$query=mysqli_query($conn, "delete from category where id='$id'");
if($query){
    echo "<script>alert('category delet');</script>";
    
 header('Location: categories.php');
    
}
else{echo "try again";}

?>