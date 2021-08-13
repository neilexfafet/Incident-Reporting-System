<?php 

  include("conn.php");

  $queryResult = $connect->
      query("SELECT * FROM incidents WHERE is_active = 1");//change your_table with your database table that you want to fetch values

  $result = array();

  while ($fetchdata=$queryResult->fetch_assoc()) {
      $result[] = $fetchdata;
  }

  echo json_encode($result);
 ?>