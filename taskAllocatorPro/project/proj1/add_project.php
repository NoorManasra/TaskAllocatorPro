<?php
// Include the database connection file
include('db.php.inc');

$errors = [];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = $_POST['project_id'];
    $project_title = $_POST['project_title'];
    $project_description = $_POST['project_description'];
    $customer_name = $_POST['customer_name'];
    $total_budget = $_POST['total_budget'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $document_titles = $_POST['document_titles'];

    $files = $_FILES['supporting_documents'];
    $supported_files = [];

    foreach ($files['name'] as $key => $filename) {
        $file_size = $files['size'][$key];
        $file_tmp = $files['tmp_name'][$key];
        $file_error = $files['error'][$key];

        if ($file_error === UPLOAD_ERR_OK && $file_size <= 2 * 1024 * 1024) {  
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array(strtolower($file_ext), ['pdf', 'docx', 'png', 'jpg'])) {
            
                $file_new_name = uniqid('', true) . "." . $file_ext;
                move_uploaded_file($file_tmp, "uploads/" . $file_new_name);
                $supported_files[] = $file_new_name;
            } else {
                $errors[] = "Unsupported file type for file: $filename. Only PDF, DOCX, PNG, JPG are allowed.";
            }
        } else {
            $errors[] = "Error uploading file: $filename.";
        }
    }

    $supporting_documents_json = json_encode($supported_files);
    $document_titles_json = json_encode(explode(',', $document_titles));

    if (empty($errors)) {
        $sql = "INSERT INTO project (project_id, project_title, project_description, customer_name, total_budget, start_date, end_date, supporting_documents, document_titles)
                VALUES ('$project_id', '$project_title', '$project_description', '$customer_name', '$total_budget', '$start_date', '$end_date', '$supporting_documents_json', '$document_titles_json')";

        try {
     
            $pdo->exec($sql);
            $message = "Project successfully added."; 
        } catch (PDOException $e) {
          
            $message = "Error: " . $e->getMessage(); 
        }
    }
}

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-title">Task Allocator Pro</div>
        <div class="user-profile">
            <a href="profile.php" class="profile-link">My Profile</a>
        </div>
    </header>
    <main>
    
    <div class="nav-bar">
        <ul>
            <li><a href="allocate-team-leader.php" class="nav-link">Allocate Team Leader</a></li>
            <li><a href="view-projects.php" class="nav-link">View Projects</a></li>
            <li><a href="manage_users.php" class="nav-link">Manage Users</a></li>
        </ul>
    </div>

    <section class="signup-containor">
        <h2>Add Project</h2>
        
        <?php if ($message != ""): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="add_project.php" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <div class="form-group">
                    <label for="project_id">Project ID</label>
                    <input type="text" id="project_id" name="project_id" required>
                </div>

                <div class="form-group">
                    <label for="project_title">Project Title</label>
                    <input type="text" id="project_title" name="project_title" required>
                </div>

                <div class="form-group">
                    <label for="project_description">Project Description</label>
                    <textarea id="project_description" name="project_description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="customer_name">Customer Name</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>

                <div class="form-group">
                    <label for="total_budget">Total Budget</label>
                    <input type="number" id="total_budget" name="total_budget" required>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>

                <div class="form-group">
                    <label for="supporting_documents">Supporting Documents (PDF, DOCX, PNG, JPG)</label>
                    <input type="file" id="supporting_documents" name="supporting_documents[]" multiple>
                </div>

                <div class="form-group">
                    <label for="document_titles">Document Titles (comma separated)</label>
                    <input type="text" id="document_titles" name="document_titles" required>
                </div>

                <button type="submit">Add Project</button>
            </div>
        </form>
    </section>
<main/>
    <?php include 'footer.php'; ?>
</body>
</html>