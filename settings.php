<?php
include '../config/database.php';

$user_id = 1; // Replace with actual session user_id
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

echo '<div class="settings-form">';
echo '<h2>Account Settings</h2>';
echo '<form id="settings-form">';
echo '<div class="form-group">';
echo '<label>Username</label>';
echo '<input type="text" name="username" value="' . htmlspecialchars($user['username']) . '">';
echo '</div>';
echo '<div class="form-group">';
echo '<label>Email</label>';
echo '<input type="email" name="email" value="' . htmlspecialchars($user['email']) . '">';
echo '</div>';
echo '<button type="submit">Save Changes</button>';
echo '</form>';
echo '</div>';
?>
