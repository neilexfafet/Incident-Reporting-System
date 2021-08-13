<?php 

  include("conn.php");


  

  $queryResult = $connect->
      query("SELECT a.subject,a.image,a.message,n.* FROM notifications n inner JOIN announcements a ON  a.id = n.notif_id WHERE `sendto_type` = 'App\\\User' and status='read' order by created_at desc");
  $result = array();

  while ($fetchdata=$queryResult->fetch_assoc()) {
     $result[] = $fetchdata;
  }

  echo json_encode($result);
 ?>