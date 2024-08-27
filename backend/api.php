<?php
header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blueshirt";

try {
    // Create connection with PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'events':
        if ($method == 'GET') {
            getEvents($conn);
        } elseif ($method == 'POST') {
            addEvent($conn);
        } elseif ($method == 'PUT') {
            updateEvent($conn);
        } elseif ($method == 'DELETE') {
            deleteEvent($conn);
        }
        break;

    case 'v_names':
        if ($method == 'GET') {
            getVNames($conn);
        } elseif ($method == 'POST') {
            addVName($conn);
        } elseif ($method == 'PUT') {
            updateVName($conn);
        } elseif ($method == 'DELETE') {
            deleteVName($conn);
        }
        break;

    case 'v_members':
        if ($method == 'GET') {
            getVMembers($conn);
        } elseif ($method == 'POST') {
            addVMember($conn);
        } elseif ($method == 'PUT') {
            updateVMember($conn);
        } elseif ($method == 'DELETE') {
            deleteVMember($conn);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid endpoint"]);
}

$conn = null; // Close the connection

function getEvents($conn)
{
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ถ้าไม่พบ event ที่ตรงกับ id ที่ให้มา
        if ($event) {
            echo json_encode($event);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Event not found']);
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM events");
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($events);
    }
}


function addEvent($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $v_name_id = $data['v_names_id'];
    $v_member_id = $data['v_members_id'];
    $title = $data['title'];
    $start_date = $data['start_date'];

    $stmt = $conn->prepare("INSERT INTO events (v_name_id, v_member_id, title, start_date) VALUES (:v_name_id, :v_member_id, :title, :start_date)");
    $stmt->bindParam(':v_name_id', $v_name_id);
    $stmt->bindParam(':v_member_id', $v_member_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':start_date', $start_date);

    if ($stmt->execute()) {
        echo json_encode(["message" => "New event created successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}

function updateEvent($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $v_name = $data['v_name'];
    $title = $data['title'];
    $start_date = $data['start_date'];

    $stmt = $conn->prepare("UPDATE events SET v_name=:v_name, title=:title, start_date=:start_date WHERE id=:id");
    $stmt->bindParam(':v_name', $v_name);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Event updated successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}

function deleteEvent($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $stmt = $conn->prepare("DELETE FROM events WHERE id=:id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Event deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}

function getVNames($conn)
{
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $stmt = $conn->prepare("SELECT * FROM v_names WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // ถ้าไม่พบ event ที่ตรงกับ id ที่ให้มา
        if ($event) {
            echo json_encode($event);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Event not found']);
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM v_names");
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($events);
    }
}

function addVName($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $color = $data['color'];

    $stmt = $conn->prepare("INSERT INTO v_names (name, color) VALUES (:name, :color)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':color', $color);

    if ($stmt->execute()) {
        echo json_encode(["message" => "New name created successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}

function updateVName($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $name = $data['name'];
    $color = $data['color'];

    $stmt = $conn->prepare("UPDATE v_names SET name=:name, color=:color WHERE id=:id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Name updated successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}

function deleteVName($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $stmt2 = $conn->prepare("DELETE FROM v_members WHERE v_names_id=:id");
    $stmt2->bindParam(':id', $id);
    
    $stmt1 = $conn->prepare("DELETE FROM v_names WHERE id=:id");
    $stmt1->bindParam(':id', $id);

    if ($stmt2->execute() && $stmt1->execute()) {
        echo json_encode(["message" => "Name deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt1->errorInfo()) . " " . implode(" ", $stmt2->errorInfo())]);
    }
}

function getVMembers($conn)
{
        
    if (isset($_GET['v_names_id'])) {
        $v_names_id = isset($_GET['v_names_id']) ? $_GET['v_names_id'] : null;
        $stmt = $conn->prepare("SELECT * FROM v_members WHERE v_names_id = :v_names_id");
        $stmt->bindParam(':v_names_id', $v_names_id);
    } else {
        $stmt = $conn->prepare("SELECT * FROM v_members");
    }

    $stmt->execute();
    $v_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($v_members);
}

function addVMember($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $v_names_id = $data['v_names_id'];
    $name = $data['name'];

    $stmt = $conn->prepare("INSERT INTO v_members (v_names_id, name) VALUES (:v_names_id, :name)");
    $stmt->bindParam(':v_names_id', $v_names_id);
    $stmt->bindParam(':name', $name);

    if ($stmt->execute()) {
        echo json_encode(["message" => "New member created successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}

function updateVMember($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $v_names_id = $data['v_names_id'];
    $name = $data['name'];

    $stmt = $conn->prepare("UPDATE v_members SET v_names_id=:v_names_id, name=:name WHERE id=:id");
    $stmt->bindParam(':v_names_id', $v_names_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Member updated successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}

function deleteVMember($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $stmt = $conn->prepare("DELETE FROM v_members WHERE id=:id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Member deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . implode(" ", $stmt->errorInfo())]);
    }
}
?>
