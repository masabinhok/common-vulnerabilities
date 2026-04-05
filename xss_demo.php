<?php
declare(strict_types=1);

$mode = ($_GET['mode'] ?? 'vuln') === 'secure' ? 'secure' : 'vuln';
$comment = $_POST['comment'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; background: #f9fafb; }
        .box { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .vuln { border-color: #ef4444; }
        .secure { border-color: #16a34a; }
        code { background: #eef2ff; padding: 0.1rem 0.3rem; }
    </style>
</head>
<body>
    <h1>XSS Demo (Mode: <?php echo strtoupper($mode); ?>)</h1>
    <p><a href="index.php">Back to Home</a></p>

    <div class="box <?php echo $mode === 'vuln' ? 'vuln' : 'secure'; ?>">
        <form method="post" action="?mode=<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
            <label for="comment">Leave a comment:</label><br>
            <textarea id="comment" name="comment" rows="4" cols="60"><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></textarea><br>
            <button type="submit">Post</button>
        </form>
        <p>Try payload in vulnerable mode: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></p>
    </div>

    <div class="box">
        <h2>Rendered Output</h2>
        <?php if ($comment === ''): ?>
            <p>No comment posted yet.</p>
        <?php elseif ($mode === 'vuln'): ?>
            <p><?php echo $comment; ?></p>
        <?php else: ?>
            <p><?php echo htmlspecialchars($comment, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </div>

    <div class="box">
        <h2>Security Best Practices</h2>
        <ul>
            <li>Escape all untrusted output with context-aware encoding (HTML, attribute, JS, URL).</li>
            <li>Validate input but do not rely on validation alone for XSS defense.</li>
            <li>Use Content Security Policy (CSP) to reduce script execution impact.</li>
            <li>Prefer framework templating that auto-escapes by default.</li>
        </ul>
    </div>
</body>
</html>
