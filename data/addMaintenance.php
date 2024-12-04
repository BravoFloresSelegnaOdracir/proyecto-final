<?php
include "../includes/headerO.php";
require "connection.php";
$db = connection();
var_dump($_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['description']) && isset($_POST['type']) && isset($_POST['incident'])) {
        $description = $_POST["description"];
        $type = $_POST["type"];
        $incident = $_POST["incident"];

        $query = "CALL insertarMantenimiento('$description', '$type', '$incident');";
        
        $response = mysqli_query($db, $query);
        if ($response) {
            echo "<script>alert('Maintenance added successfully.'); 
            window.history.back();</script>";
            exit();
        } else {
            echo "<script>alert('Maintenance Not Created: " . mysqli_error($db) . "'); 
            window.history.back();</script>";
            exit();

        }
    } else {
        echo "<script>alert('Missing form fields!'); 
        window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('Form not submitted correctly!'); 
    window.history.back();</script>";
    exit();
}

?>
<section>
    <h2>Add a new maintenance</h2>
    <div>
        <form action="addMaintenance.php" method="post">
            <fieldset>
                <legend>Fill all form fields</legend>
                <div>
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description">
                </div>
                <div>
                    <label for="type">Maintenance Type</label>
                    <select id="type" name="type" required>
                        <option value="COR">Corrective</option>
                        <option value="PRE">Preventive</option>
                    </select>
                </div>
                <div>
                    <label for="incident">Incident</label>
                    <input type="number" id="incident" name="incident" required>
                </div>
                <div>
                    <button type="submit">Open a new maintenance</button>
                </div>
            </fieldset>
        </form>
    </div>
</section>

<?php
include "../includes/footers.php";
?>