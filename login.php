<?php
        $msg = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user = isset($_POST['user'])?$_POST['user']:null;
        $pass = isset($_POST['pass'])?$_POST['pass']:null;
        if (isset($user) && isset($pass)) {
    $access = true;
    include_once('config.php');
    
    $link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname);
    
    $query = "SELECT users.uid, users.email, users.fname, users.lname, users.profpic FROM users WHERE users.username = '$user' AND users.password = '$pass';";
    $result = $link->query($query);
            
    if ($result->num_rows){
        $msg['status'] = 0;
        $msg['msg'] = 'Login success';
        
        $row = $result->fetch_row();
        if (isset($row[2])) {
            $msg['fname'] = $row[2];
        }
        if (isset($row[3])) {
            $msg['lname'] = $row[3];
        }
        
        $msg['uid'] = $row[0];
        $msg['email'] = $row[1];
        if (isset($row[4])) {
             $msg['profpic'] = base64_encode($row[4]);
        }
        $msg['user'] = $user;
    } else {
        $msg['status'] = 1;
        $msg['msg'] = 'Invalid credentials';
    }
    
    $result->free();
        } else {
            $msg['status'] = 2;
            $msg['msg'] = 'Incomplete data received';
        }
    } else {
        $msg['status'] = 3;
        $msg['msg'] = 'Request type not supported';
    }
    
    header('Content-type: application/json');
        echo json_encode($msg);