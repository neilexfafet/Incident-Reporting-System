<?php
include 'conn.php';

//flag 1 for login
//flag 2 for update register



//flag to check and to execute specific switch case based on flag value sent
$flag = $_POST['flag'];
(int) $flag;


switch ($flag) {
    
    case 1:
        	
        	$Email = $_POST['email'];
		    $Password = trim($_POST['password']);
	
        	$query = "SELECT * FROM users WHERE email='$Email'";
			$result = mysqli_query($connect, $query);

			/* $a = "SELECT * FROM users WHERE email='$Email' AND status='blocked' ";
			$a2 = mysqli_query($connect, $a); */
	    
           	
    		if(mysqli_num_rows($result)==1){
    			while ($row = mysqli_fetch_assoc($result)) {
					if($row['status'] == 'verified') {
						if(password_verify($Password,$row['password'])) {
							$json['value'] = 1;
							$json['message'] = 'You have logged-in successfully!';
							$json['email'] = $row['email'];
							$json['fname'] = $row['first_name'];
							$json['lname'] = $row['last_name'];
							$json['address'] = $row['address'];
							$json['mobile'] = $row['contact_no'];
							$json['password'] = $row['password'];
							$json['date'] = $row['birthday'];
							$json['gender'] = $row['gender'];
							$json['id'] = $row['id'];
							$json['status'] = 'success';
						} else {
							$json['value'] = 0;
							$json['message'] = 'Username and Password does not match';
							$json['email'] = '';
							$json['fname'] = '';
							$json['lname'] = '';
							$json['address'] = '';
							$json['mobile'] = '';
							$json['password'] = '';
							$json['id'] = '';
							$json['status'] = 'fail';
						}
					} 
					if($row['status'] == 'blocked') {
						if(password_verify($Password,$row['password'])) {
							$json['value'] = 4;
							$json['message'] = 'Your account has been blocked due to a bogus report.';
							$json['email'] = '';
							$json['fname'] = '';
							$json['lname'] = '';
							$json['address'] = '';
							$json['mobile'] = '';
							$json['password'] = '';
							$json['id'] = '';
							$json['status'] = 'sss';
						} else {
							$json['value'] = 0;
							$json['message'] = 'Username and Password does not match';
							$json['email'] = '';
							$json['fname'] = '';
							$json['lname'] = '';
							$json['address'] = '';
							$json['mobile'] = '';
							$json['password'] = '';
							$json['id'] = '';
							$json['status'] = 'fail';
						}
					}
					
				}
			} else {
				$json['value'] = 0;
				$json['message'] = 'This account does not exist';
				$json['email'] = '';
				$json['fname'] = '';
				$json['lname'] = '';
				$json['address'] = '';
				$json['mobile'] = '';
				$json['password'] = '';
				$json['id'] = '';
				$json['status'] = 'fail';
			}
		
 
            break;
         
    
    case 2:
			 $fname = $_POST['fname'];
			 $mname = $_POST['mname'];
			 $lname = $_POST['lname'];
			 $address = $_POST['address'];
			 $Email = $_POST['email'];			
			 $Mobile = $_POST['mobile'];
			 $Password = $_POST['password'];
			 $birthday = $_POST['birthday'];
			 $gender = $_POST['gender'];

			$options = array("cost"=>12);
			$hashPassword = password_hash($Password,PASSWORD_BCRYPT,$options);
		

		
			
			 $age= date("Y") - date("Y", strtotime($birthday)); 
    		/* $sqlmax = "SELECT max(id) FROM `users`";
    	     $resultmax = mysqli_query($connect, $sqlmax);
    	     $rowmax = mysqli_fetch_array($resultmax);
    	     
    	     if($rowmax[0]==null){
    	          $idnomax=1;
    	     }else{
    	          $idnomax=$rowmax[0]+1;
    	     }*/
        
            $query = "SELECT * FROM users WHERE email='$Email'";
        	$result = mysqli_query($connect, $query);
			
			
    		if(mysqli_num_rows($result)>0){
    			$json['value'] = 2;
    			$json['message'] =  'This email is already taken. Please use an another email.';
    			
			}
			if ($age > 16 ) {
    			$query = "INSERT INTO users (first_name,middle_name,last_name, email,address,birthday,gender,contact_no, password, status,created_at,updated_at) VALUES ('$fname','$mname','$lname','$Email','$address','$birthday','$gender','$Mobile','$hashPassword', 'verified',now(),now())";
    			$inserted = mysqli_query($connect, $query);
    			
    			if($inserted == 1 ){    			
    			    
    				$json['value'] = 1;
    				$json['message'] = 'You have registered successfully!';
    			}else{
    				$json['value'] = 0;
    				$json['message'] = 'This email is already taken. Please use an another email.';
    			}

			  
			}
			else{
				$json['value'] = 0;
				$json['message'] = 'Only 16 years old and above can register this application';
			}
		
     	
            
            break;
            //Ends case 2
    
    default:
        $inserted == 0;
}    
        
    echo json_encode($json);
 	mysqli_close($connect);

?>