<?php
include '../config/database.php';


$user_id = 1; // Replace with actual session user_id
$query = "SELECT * FROM appointments WHERE user_id = ? ORDER BY appointment_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo '<div class="appointments-list">';
while ($row = $result->fetch_assoc()) {
    echo '<div class="appointment-item">';
    echo '<h3>' . htmlspecialchars($row['service_type']) . '</h3>';
    echo '<p>Date: ' . htmlspecialchars($row['appointment_date']) . '</p>';
    echo '<p>Time: ' . htmlspecialchars($row['appointment_time']) . '</p>';
    echo '<p>Status: ' . htmlspecialchars($row['status']) . '</p>';
    echo '</div>';
}
echo '</div>';
?>
