<?php

require_once("../models/User.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountUser($row) {
    $user = new User($row["id"],
                     $row["username"],
                     $row["password"],
                     $row["profileId"],
                     $row["groupName"]);
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
    $sql = "SELECT * FROM User WHERE id = " . ((string) $id);
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
    $data["id"] = $id;
    $user = mountUser($data);
    global $connection;
    $sql = "INSERT INTO User (id, username, password, profileId, groupName) 
                        VALUES (" . $id .
                        ", '" . $user->getUsername() . 
                        "', '" . $user->getPassword() .
                        "', '" . $user->getProfileId() . 
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
        $user->setUsername($data["username"]);
        $user->setPassword($data["password"]);
        $user->setProfileId($data["profileId"]);
        $user->setGroup($data["groupName"]);
        
        global $connection;
        $sql = "UPDATE User SET username = '" . $user->getUsername() . 
               "', password = '" . $user->getPassword() . 
               "', profileId = '" . $user->getProfileId() .
               "', groupName = '" . $user->getGroup() .
               "' WHERE id = " . ((string) $id);
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

    $sql = "DELETE FROM User WHERE id = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>
