<?php
        $msg = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $uid = isset($_POST['uid'])?$_POST['uid']:null;
        $profpic = isset($_FILES['profpic'])?file_get_contents($_FILES["profpic"]["tmp_name"]):null;
        $fname = isset($_POST['fname'])?$_POST['fname']:null;
        $lname = isset($_POST['lname'])?$_POST['lname']:null;
        if (isset($uid)) {
            $access = true;
            include_once('config.php');
    
            $link = new mysqli($dbaddr, $dbuser, $dbpass, $dbname);
            
            $query = '';
            if (isset($profpic)) {
                $query = "UPDATE users SET users.fname='$fname', users.lname='$lname', users.profpic='$profpic' WHERE users.uid = '$uid'";
            } else {
                $query = "UPDATE users SET users.fname='$fname', users.lname='$lname' WHERE users.uid = '$uid'";                
            }
            
            if ($link->query($query)) {
                $msg['status'] = 0;
                $msg['msg'] = 'Update success';
            } else {
                $msg['status'] = 1;
                $msg['msg'] = 'Internal Error';
            }
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