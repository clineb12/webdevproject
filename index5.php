<?php

session_start();
require_once 'auth.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$host = 'localhost'; 
$dbname = 'horror'; 
$user = 'brooke'; 
$pass = 'passwd';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Handle book search
$search_results = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';
    $search_sql = 'SELECT id, form, subgenre, title, releaseyear FROM media WHERE title LIKE :search';
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->execute(['search' => $search_term]);
    $search_results = $search_stmt->fetchAll();
}

//something

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['form']) && isset($_POST['subgenre']) && isset($_POST['title']) && isset($_POST['releaseyear'])) {
        // Insert new entry
        $form = htmlspecialchars($_POST['form']);
        $subgenre = htmlspecialchars($_POST['subgenre']);
        $title = htmlspecialchars($_POST['title']);
        $releaseyear = htmlspecialchars($_POST['releaseyear']);

        
        $insert_sql = 'INSERT INTO media (form, subgenre, title, releaseyear) VALUES (:form, :subgenre, :title, :releaseyear)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['form' => $form, 'subgenre' => $subgenre, 'title' => $title, 'releaseyear' => $releaseyear]);
    } elseif (isset($_POST['delete_id'])) {
        // Delete an entry
        $delete_id = (int) $_POST['delete_id'];
        
        $delete_sql = 'DELETE FROM media WHERE id = :id';
        $stmt_delete = $pdo->prepare($delete_sql);
        $stmt_delete->execute(['id' => $delete_id]);
    }
}

// Get all media for main table
$sql = 'SELECT id, form, subgenre, title, releaseyear FROM media';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nightmare Nexus</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">Nightmare Nexus</h1>
        <p class="hero-subtitle">"Escape the Ordinary, Embrace the Macabre"</p>
        <h4 class="hero-option"><a href="about.php">About</a> <a href="admin.php">Admin</a></h4>
        
        <!-- Search moved to hero section -->
        <div class="hero-search">
            <h2>Explore the Database</h2>
            <form action="" method="GET" class="search-form">
                <label for="search">Search by Title:</label>
                <input type="text" id="search" name="search" required>
                <input type="submit" value="Search">
            </form>
            
            <?php if (isset($_GET['search'])): ?>
                <div class="search-results">
                    <h3>Search Results</h3>
                    <?php if ($search_results && count($search_results) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Form</th>
                                    <th>Subgenre</th>
                                    <th>Title</th>
                                    <th>Release Year</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($search_results as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['form']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subgenre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['releaseyear']); ?></td>
                                    <td>
                                        <form action="index5.php" method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                            <input type="submit" value="Whoops!">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No media found matching your search.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table section with container -->
    <div class="table-container">
        <h2>Horrors Currently in Database</h2>
        <table class="half-width-left-align">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Form</th>
                    <th>Subgenre</th>
                    <th>Title</th>
                    <th>Release Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['form']); ?></td>
                    <td><?php echo htmlspecialchars($row['subgenre']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['releaseyear']); ?></td>
                    <td>
                        <form action="index5.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="Whoops!">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Form section with container -->
    <div class="form-container">
        <h2>Unearth the Unknown</h2>
        <form action="index5.php" method="post">
            <label for="form">Form:</label>
            <input type="text" id="form" name="form" required>
            <br><br>
            <label for="subgenre">Subgenre:</label>
            <input type="text" id="subgenre" name="subgenre" required>
            <br><br>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <br><br>
            <label for="releaseyear">Release Year:</label>
            <input type="int" id="releaseyear" name="releaseyear" required>
            <br><br>
            <input type="submit" value="Expand our Knowledge">
        </form>
    </div>
</body>
</html>