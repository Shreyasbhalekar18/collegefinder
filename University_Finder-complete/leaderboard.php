<?php
// Database connection
$host = "localhost:3307";
$user = "root";
$pass = "";
$db = "university_finder";
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch leaderboard (Top 5 users with the highest points)
$getLeaderboardQuery = "SELECT username, points FROM users ORDER BY points DESC LIMIT 5";
$result = $conn->query($getLeaderboardQuery);

$leaderboard = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Leaderboard</h2>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($leaderboard)): ?>
                    <?php foreach ($leaderboard as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo $user['points']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No users in the leaderboard yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
