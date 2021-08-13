<?php 

  include("conn.php");


  

  $queryResult = $connect->
      query("SELECT * FROM Stations WHERE is_active = 1");
  $result = array();

  while ($fetchdata=$queryResult->fetch_assoc()) {
     $result[] = $fetchdata;
  }

  echo json_encode($result);
 ?>