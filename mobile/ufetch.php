<?php 

  include("conn.php");

 
  

  $queryResult = $connect->
      query("SELECT r.id,i.type,n.sendto_id,n.created_at,r.station_id,s.station_name FROM notifications n inner JOIN reports r ON  r.id = n.notif_id inner JOIN incidents i ON  r.incident_id = i.id  inner JOIN stations s ON  r.station_id = s.id WHERE `sendto_type` = 'App\\\User' and n.status='read' and n.type= 'Report Feedback' order by created_at desc");
  $result = array();

  while ($fetchdata=$queryResult->fetch_assoc()) {
     $result[] = $fetchdata;
  }

  echo json_encode($result);
 ?>