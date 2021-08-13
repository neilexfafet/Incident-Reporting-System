<?php 

  include("conn.php");


  

  $queryResult = $connect->
      query("SELECT * FROM users");
  $result = array();

  while ($fetchdata=$queryResult->fetch_assoc()) {
     $result[] = $fetchdata;
  }

  echo json_encode($result);
 ?>