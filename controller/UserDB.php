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
                        "', '" . $doc->getProfileID() . 
                        "', '" . $doc->getGroup() . "')";
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
        $sql = "UPDATE User SET Username = '" . $user->getName() . 
               "', Address = '" . $doc->getAddress() . 
               "', Phone = '" . $doc->getPhone() .
               "', Email = '" . $doc->getEmail() .
               "', Specialty = '" . $doc->getSpecialty() . 
               "', CRM = '" . $doc->getCRM() .
               "' WHERE DoctorID = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $doc;
        } else {
            $response["message"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeUser($id, $data) {
    $user = loadUser($id);
    if(gettype($user) == "object") {

        if(!array_key_exists("username", $data) || 
            !array_key_exists("password", $data) || 
            !array_key_exists("profileId", $data) || 
            !array_key_exists("group", $data)) 
        {
            return "HTTP/1.0 400 Bad Request";
        }

        $user->setUsername((string) $data["username"]);
        $user->setPassword((string) $data["password"]);
        $user->setProfileId((string) $data["profileId"]);
        $user->setGroup((string) $data["group"]);

        $xmlusers = simplexml_load_file("../xml/users.xml");
        foreach($xmlusers as $userNode) {
            if($userNode->id == $user->getId()) {
                $userNode->username = $user->getUsername();
                $userNode->password = $user->getPassword();
                $userNode->profileId = $user->getProfileId();
                $userNode->group = $user->getGroup();
            }
        }

        $write = simplexml_import_dom($xmlusers);
        $write->saveXML("../xml/users.xml");

        return $user;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeAttributeUser($id, $data) {
    $user = loadUser($id);
    if(gettype($user) == "object") {
        foreach($data as $key => $value) {
            switch ($key) {
                case 'username':
                    $user->setUsername((string) $value);
                    break;
                case 'password':
                    $user->setPassword((string) $value);
                    break;
                case 'profileId':
                    $user->setProfileId((string) $value);
                    break;
                case 'group':
                    $user->setGroup((string) $value);
                    break;
                default:
                    return "HTTP/1.0 200 OK";
                    break;
            }
        }

        $xmlusers = simplexml_load_file("../xml/users.xml");
        foreach($xmlusers as $userNode) {
            if($userNode->id == $user->getId()) {
                $userNode->username = $user->getUsername();
                $userNode->password = $user->getPassword();
                $userNode->profileId = $user->getProfileId();
                $userNode->group = $user->getGroup();
            }
        }

        $write = simplexml_import_dom($xmlusers);
        $write->saveXML("../xml/users.xml");

        return $user;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeUser($id) {
    $user = loadUser($id);
    if(gettype($user) == "object") {
        $xmlusers = simplexml_load_file("../xml/users.xml");
        foreach($xmlusers as $userNode) {
            if($userNode->id == $user->getId()) {
                unset($userNode[0]);
                $write = simplexml_import_dom($xmlusers);
                $write->saveXML("../xml/users.xml");
                return "HTTP/1.0 200 OK";
            }
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>
