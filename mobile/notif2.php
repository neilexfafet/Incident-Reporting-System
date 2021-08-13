<?php
    include 'conn.php';
   
    $read = $_POST['read'];
    $id = $_POST['id'];



    $query = "SELECT i.type,n.sendto_id FROM notifications n inner JOIN reports r ON  r.id = n.notif_id inner JOIN incidents i ON  r.incident_id = i.id WHERE `sendto_type` = 'App\\\User' and sendto_id='$id' and n.status='unread' ";
    $result = mysqli_query($connect, $query);
    if($result->num_rows>0){
    		    
        while ($row = mysqli_fetch_assoc($result)) {
          
            $query2 = "UPDATE `notifications` SET `status`='$read' WHERE `sendto_type` = 'App\\\User' and sendto_id='$id' and status='unread' and type='Report Feedback'  ";
            $result2 = mysqli_query($connect, $query2);

        
        }
        
        
    }





  //  echo json_encode($json);
    mysqli_close($connect);
    







?>
