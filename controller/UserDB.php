<?php

require_once("../models/User.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountUser($row) {
    $user = new User($row["UserID"],
                     $row["Username"],
                     $row["Password"],
                     $row["ProfileID"],
                     $row["Group"]);
    return $user;
}

function loadUsers() {
    global $connection;

    $sql = "SELECT * FROM User";
    $result = $connection->query($sql);

    $users = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $user = mountUser($row);
            $users[] = $user;
        }
        return $users;
    } else {
        return null;
    }
}

function loadUser($id) {
    global $connection;
    $sql = "SELECT * FROM User WHERE UserID = " . ((string) $id);
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $user = mountUser($row);
        return $user;
    } else {
        return null;
    }
}

function writeNewUser($data) {
    $id = time();
    $data["UserID"] = $id;
    $user = mountUser($data);
    global $connection;
    $sql = "INSERT INTO User (UserID, Username, Password, ProfileID, GroupName) 
                        VALUES (" . $user->getId() .
                        ", '" . $user->getUsername() . 
                        "', '" . $user->getPassword() .
                        "', '" . $user->getProfileID() . 
                        "', '" . $user->getGroup() . "')";
    if($connection->query($sql) === TRUE) {
        return $user;
    } else {
        $response["message"] = $connection->error;
        return $response;
    }
}

function writeUser($id, $data) {
    $user = loadUser($id);
    
    if(gettype($user) == "object") {
        $user->setUsername($data["Username"]);
        $user->setPassword($data["Password"]);
        $user->setProfileID($data["ProfileID"]);
        $user->setGroup($data["Group"]);
        
        global $connection;
        $sql = "UPDATE User SET Username = '" . $user->getUsername() . 
               "', Password = '" . $user->getPassword() . 
               "', ProfileID = '" . $user->getProfileID() .
               "', Group = '" . $user->getGroup() .
               "' WHERE UserID = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $user;
        } else {
            $response["message"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeUser($id) {
    global $connection;

    $sql = "DELETE FROM User WHERE UserID = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>
