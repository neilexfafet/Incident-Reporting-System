<?php 
include 'conn.php';




$id = $_POST['id'];
$type = $_POST['type'];
$des = $_POST['des'];
$incident_date= $_POST['incident_date'];
$incident_time= $_POST['time'];
$loc =  $_POST['location'];
$lon =  $_POST['long'];
$lat = $_POST['lat'];

function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}


$query = "SELECT *, 
( 6367 * acos( cos( radians($lat) ) * 
cos( radians( location_lat ) ) * 
cos( radians( location_lng ) - 
radians($lon) ) + 
sin( radians($lat) ) * 
sin( radians( location_lat ) ) ) ) 
AS distance FROM stations HAVING distance < 10 ORDER BY distance  LIMIT 1";
$distanceresult = mysqli_query($connect, $query);

$query1 = "SELECT id FROM incidents WHERE incidents.type='$type'";
$result = mysqli_query($connect, $query1);



$blockedquery = "SELECT * FROM users WHERE id='$id'";
$resultblocked = mysqli_query($connect, $blockedquery);



if(mysqli_num_rows($resultblocked)==1){
    while ($brow = mysqli_fetch_assoc($resultblocked)) {
        if($brow['status'] == 'blocked') {
        $json['value'] = 0;
        $json['message'] = "It seems like your account has been blocked. Please log-out immediately.";
        }
else{


if (mysqli_num_rows($distanceresult)==1)
{
while  ($row = mysqli_fetch_assoc($result)) { 
 while  ($row2 = mysqli_fetch_assoc($distanceresult)) { 
        
    $query3 = "INSERT INTO reports (incident_id,description,location,location_lat,location_lng,incident_date,reporter_id,station_id,created_at,updated_at) VALUES ('".$row['id']."','$des','$loc','$lat','$lon','$incident_date+$incident_time','$id','".$row2['id']."',now(),now())";
    $inserted = mysqli_query($connect,$query3);
    $reportid = mysqli_insert_id($connect);
  
    if(!empty($_FILES["file"])) {
        $file_ary = reArrayFiles($_FILES['file']);
        foreach($file_ary as $file) {
            $ran = rand(000,99999);
            $date = date('dmy_H_s_i');
            $img_name = $file['name'][0];
            $ext = pathinfo($img_name, PATHINFO_EXTENSION);
            $filename = $ran.'_'.$date.'.'.$ext;
        
            $savefile = "../evidence/$filename";
            $savedb = "evidence/$filename";
        
            move_uploaded_file($file["tmp_name"][0], $savefile);
            
            if($ext == 'mp4' || $ext == 'mkv' || $ext == '3gp' || $ext == 'ts' || $ext == 'avi' || $ext == 'mov' || $ext == 'webm') {
                $filequery= "INSERT INTO evidence (report_id,filename,filetype,created_at,updated_at) VALUES ('$reportid','$savedb','video',now(),now())";
                $inserted2 = mysqli_query($connect,$filequery);
            } else {
                $filequery= "INSERT INTO evidence (report_id,filename,filetype,created_at,updated_at) VALUES ('$reportid','$savedb','image',now(),now())";
                $inserted2 = mysqli_query($connect,$filequery);
            }
        }
    }

    $filequery2= "INSERT INTO notifications (type,notif_type,notif_id,sendto_type,sendto_id,created_at,updated_at) VALUES ('Report','App\\\Report','$reportid','App\\\Station','".$row2['id']."',now(),now())";
    $inserted3 = mysqli_query($connect,$filequery2);

    $filequery3 = "INSERT INTO report_logs (activity,report_id,created_at,updated_at) VALUES ('pending','$reportid',now(),now())";
    $inserted4 = mysqli_query($connect,$filequery3);
  
   
   
   if( $inserted == 1 && $inserted3 == 1 && $inserted4 == 1){    
       $json['value'] = 1;
       $json['message'] = 'Your report has been sent to the nearest station.';
   }else{
       $json['value'] = 0;
       $json['message'] = "There was an error sending your report. Try making again.";
   } 
 }
}

} 
 else {
    $json['value'] = 0;
    $json['message'] = "Report Sending Failed. Your input location is beyond the proximity of CDO. Try other location.";
  }
  }
 } 
} 
 
 
   


echo json_encode($json);
mysqli_close($connect);
//echo json_encode($return);
//converting array to JSON string
//header('Content-Type: application/json');
?>  