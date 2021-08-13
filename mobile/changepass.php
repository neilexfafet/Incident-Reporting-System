<?php
    include 'conn.php';
   
    $email = $_POST['email'];
    $password = trim($_POST['password']);
    $npassword = $_POST['npassword'];
    $cnpassword = $_POST['cnpassword'];

    $options = array("cost"=>12);
    $hashPassword = password_hash($cnpassword,PASSWORD_BCRYPT,$options);

  
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($connect, $query);
  
    if(mysqli_num_rows($result)==1){
        while ($row = mysqli_fetch_assoc($result)) {
            if(password_verify($password,$row['password'])) {
            $query2 = "UPDATE `users` SET `Password`='$hashPassword' where email='$email'";
            $result2 = mysqli_query($connect, $query2);

            $json['value'] = 1;
            $json['message'] = 'You have successfully changed your password.';
           }else{

                $json['value'] = 0;
                $json['message'] = 'The current password you entered is incorrect.';
            }
            
    
       
        }
        
        
    }else{
    return null;
    }
   


    echo json_encode($json);
    mysqli_close($connect);
    







?>
