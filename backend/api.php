<?php
header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blueshirt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
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

$conn->close();

function getEvents($conn)
{
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);
    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    echo json_encode($events);
}

function addEvent($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $v_name_id = $data['v_names_id'];
    $v_member_id = $data['v_members_id'];
    $title = $data['title'];
    $start_date = $data['start_date'];

    $sql = "INSERT INTO events (v_name_id, v_member_id, title, start_date) VALUES ('$v_name_id','$v_member_id', '$title', '$start_date')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "New event created successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function updateEvent($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $v_name = $data['v_name'];
    $title = $data['title'];
    $start_date = $data['start_date'];

    $sql = "UPDATE events SET v_name='$v_name', title='$title', start_date='$start_date' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Event updated successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function deleteEvent($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "DELETE FROM events WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Event deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function getVNames($conn)
{
    $sql = "SELECT * FROM v_names";
    $result = $conn->query($sql);
    $v_names = [];

    while ($row = $result->fetch_assoc()) {
        $v_names[] = $row;
    }

    echo json_encode($v_names);
}

function addVName($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $color = $data['color'];

    $sql = "INSERT INTO v_names (name, color) VALUES ('$name', '$color')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "New name created successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function updateVName($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $name = $data['name'];
    $color = $data['color'];

    $sql = "UPDATE v_names SET name='$name', color='$color' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Name updated successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function deleteVName($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "DELETE FROM v_names WHERE id=$id";
    $sql2 = "DELETE FROM v_members WHERE v_names_id=$id";

    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
        echo json_encode(["message" => "Name deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function getVMembers($conn)
{
    // Get the v_names parameter from the GET request, if it exists
    $v_names_id = isset($_GET['v_names_id']) ? $_GET['v_names_id'] : '';

    if ($v_names_id) {
        // Prepare a SQL statement with a placeholder for the v_names value
        $stmt = $conn->prepare("SELECT * FROM v_members WHERE v_names_id = ?");
        // Bind the $v_names variable to the placeholder
        $stmt->bind_param('s', $v_names_id);
    } else {
        // If no v_names is provided, prepare a simple query to select all v_members
        $stmt = $conn->prepare("SELECT * FROM v_members");
    }

    // Execute the statement
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();
    $v_members = [];

    // Fetch each row in the result set as an associative array and store in $v_members
    while ($row = $result->fetch_assoc()) {
        $v_members[] = $row;
    }

    // Return the v_members array as a JSON object
    echo json_encode($v_members);
}


function addVMember($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $v_names_id = $data['v_names_id'];
    $name = $data['name'];

    $sql = "INSERT INTO v_members (v_names_id, name) VALUES ('$v_names_id', '$name')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "New member created successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function updateVMember($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $v_names_id = $data['v_names_id'];
    $name = $data['name'];

    $sql = "UPDATE v_members SET v_names_id='$v_names_id', name='$name' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Member updated successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function deleteVMember($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    $sql = "DELETE FROM v_members WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Member deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

?>