<?php 

  include("conn.php");


  

  $queryResult = $connect->
      query("SELECT i.type,n.sendto_id FROM notifications n inner JOIN reports r ON r.id = n.notif_id inner JOIN incidents i ON r.incident_id = i.id WHERE `sendto_type` = 'App\\\User' and n.status='unread' and n.type= 'Report Feedback' ");
  $result = array();

  while ($fetchdata=$queryResult->fetch_assoc()) {
     $result[] = $fetchdata;
  }

  echo json_encode($result);
 ?>