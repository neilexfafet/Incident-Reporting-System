<?php
    include 'conn.php';

    $id = $_POST['id'];
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];
   
    
    $query = "SELECT * FROM users WHERE `email`='$email'";
    $result = mysqli_query($connect, $query);

    if($result->num_rows>0){
        $json['value'] = 0;
    	$json['message'] =  'This email is already taken. Please use an another email.';
 			
    }else{   
            $query2 = "UPDATE `users` SET `email`='$email', `first_name`= '$fname', `last_name`= '$lname', `contact_no` = '$mobile',`address` = '$address',`birthday` = '$birthday' where id='$id' ";
            $result2 = mysqli_query($connect, $query2);

            $json['value'] = 1;
            $json['message'] = 'Your account has been successfully updated. Please login again to view changes.';
    
    }
   


    echo json_encode($json);
    //mysqli_close($connect);
    


?>
