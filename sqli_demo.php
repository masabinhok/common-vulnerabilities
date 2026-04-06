<?php
declare(strict_types=1);
require_once __DIR__ . '/security.php';

set_security_headers();

$mode = get_mode();
$username = trim((string)($_GET['username'] ?? ''));
$rows = [];
$error = '';
$dbFile = __DIR__ . '/lab5.sqlite';

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($username !== '') {
        if ($mode === 'vuln') {
            $sql = "SELECT id, username, role FROM users WHERE username = '$username'";
            $query = $db->query($sql);
            if ($query !== false) {
                $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            if (!is_valid_username($username)) {
                throw new InvalidArgumentException('Username must be 1-32 chars (letters, numbers, underscore).');
            }

            $stmt = $db->prepare('SELECT id, username, role FROM users WHERE username = :username');
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
} catch (Throwable $e) {
    $error = $mode === 'secure'
        ? 'Request failed. Check input format and database initialization.'
        : $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; background: #f9fafb; }
        .box { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
        .vuln { border-color: #ef4444; }
        .secure { border-color: #16a34a; }
        code { background: #eef2ff; padding: 0.1rem 0.3rem; }
    </style>
</head>
<body>
    <h1>SQL Injection Demo (Mode: <?php echo strtoupper($mode); ?>)</h1>
    <p><a href="index.php">Back to Home</a></p>

    <div class="box <?php echo $mode === 'vuln' ? 'vuln' : 'secure'; ?>">
        <form method="get">
            <input type="hidden" name="mode" value="<?php echo h($mode); ?>">
            <label for="username">Search by username:</label>
            <input id="username" name="username" value="<?php echo h($username); ?>" maxlength="32" pattern="[A-Za-z0-9_]{1,32}">
            <button type="submit">Search</button>
        </form>
        <p>Try input: <code>alice' OR '1'='1</code> in vulnerable mode.</p>
    </div>

    <?php if ($error !== ''): ?>
        <div class="box vuln">
            <strong>Error:</strong>
            <pre><?php echo h($error); ?></pre>
        </div>
    <?php endif; ?>

    <div class="box">
        <h2>Result</h2>
        <?php if ($username === ''): ?>
            <p>Enter a username to search.</p>
        <?php elseif (count($rows) === 0): ?>
            <p>No matching users found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>ID</th><th>Username</th><th>Role</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo (int)$row['id']; ?></td>
                            <td><?php echo h((string)$row['username']); ?></td>
                            <td><?php echo h((string)$row['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="box">
        <h2>Security Best Practices</h2>
        <ul>
            <li>Use prepared statements and parameter binding for all SQL queries.</li>
            <li>Use least-privilege database accounts.</li>
            <li>Validate and constrain input (length, type, format).</li>
            <li>Log suspicious query patterns and monitor for attacks.</li>
        </ul>
    </div>
</body>
</html>
