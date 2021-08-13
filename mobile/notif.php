<?php
    include 'conn.php';
   
    $read = $_POST['read'];
    $id = $_POST['id'];



    $query = "SELECT a.subject,a.image,a.message,n.* FROM notifications n inner JOIN announcements a ON  a.id = n.notif_id WHERE `sendto_type` = 'App\\\User' and sendto_id='$id' and status='unread' ";
    $result = mysqli_query($connect, $query);
    if($result->num_rows>0){
    		    
        while ($row = mysqli_fetch_assoc($result)) {
          
            $query2 = "UPDATE `notifications` SET `status`='$read' WHERE `sendto_type` = 'App\\\User' and sendto_id='$id' and status='unread' and type='Announcement' ";
            $result2 = mysqli_query($connect, $query2);

        
        }
        
        
    }





  //  echo json_encode($json);
    mysqli_close($connect);
    







?>
