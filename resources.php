<link rel = "stylesheet" type = "text/css" href = "../css/resources.css">

<?php
include '../config/database.php';

$resources = [
    ['id' => 1, 'name' => 'Resume Template', 'file' => 'resume_template.pdf'],
    ['id' => 2, 'name' => 'Interview Guide', 'file' => 'interview_guide.pdf'],
    ['id' => 3, 'name' => 'Career Planning Worksheet', 'file' => 'career_planning.pdf']
];

echo '<div class="resources-list">';
foreach ($resources as $resource) {
    echo '<div class="resource-item">';
    echo '<h3>' . htmlspecialchars($resource['name']) . '</h3>';
    echo '<a href="downloads/' . htmlspecialchars($resource['file']) . '" class="download-btn">';
    echo '<i class="fas fa-download"></i> Download</a>';
    echo '</div>';
}
echo '</div>';
?>
