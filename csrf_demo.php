<?php
declare(strict_types=1);
require_once __DIR__ . '/security.php';

set_security_headers();
start_secure_session();

$mode = get_mode();

if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 1000;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (int)($_POST['amount'] ?? 0);

    if ($amount <= 0) {
        $message = 'Invalid transfer amount.';
    } else {
        if ($mode === 'secure') {
            $token = $_POST['csrf_token'] ?? '';
            $origin = (string)($_SERVER['HTTP_ORIGIN'] ?? '');
            $host = (string)($_SERVER['HTTP_HOST'] ?? '');
            $hasValidOrigin = $origin === '' || strpos($origin, '://' . $host) !== false;

            if (!$hasValidOrigin) {
                http_response_code(403);
                $message = 'Origin check failed. Request blocked.';
            } elseif (!hash_equals($_SESSION['csrf_token'], $token)) {
                http_response_code(403);
                $message = 'CSRF token invalid. Request blocked.';
            } else {
                $_SESSION['balance'] -= $amount;
                $message = 'Secure transfer completed: $' . $amount;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        } else {
            $_SESSION['balance'] -= $amount;
            $message = 'Vulnerable transfer completed: $' . $amount;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; background: #f9fafb; }
        .box { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .vuln { border-color: #ef4444; }
        .secure { border-color: #16a34a; }
        .msg { font-weight: bold; }
        code { background: #eef2ff; padding: 0.1rem 0.3rem; }
    </style>
</head>
<body>
    <h1>CSRF Demo (Mode: <?php echo strtoupper($mode); ?>)</h1>
    <p><a href="index.php">Back to Home</a></p>

    <div class="box <?php echo $mode === 'vuln' ? 'vuln' : 'secure'; ?>">
        <p><strong>Current Balance:</strong> $<?php echo (int)$_SESSION['balance']; ?></p>
        <?php if ($message !== ''): ?>
            <p class="msg"><?php echo h($message); ?></p>
        <?php endif; ?>

        <form method="post" action="?mode=<?php echo h($mode); ?>">
            <label for="amount">Transfer Amount:</label>
            <input id="amount" type="number" name="amount" min="1" required>

            <?php if ($mode === 'secure'): ?>
                <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
            <?php endif; ?>

            <button type="submit">Transfer</button>
        </form>
    </div>

    <div class="box">
        <h2>How Attack Works</h2>
        <p>In vulnerable mode, a malicious website can submit this form from another origin using the victim's active session cookie.</p>
    </div>

    <div class="box">
        <h2>Security Best Practices</h2>
        <ul>
            <li>Require unpredictable CSRF tokens for every state-changing request.</li>
            <li>Use <code>SameSite=Lax</code> or <code>SameSite=Strict</code> cookies where possible.</li>
            <li>Validate origin and referer headers as additional defense.</li>
            <li>Avoid state-changing operations via GET requests.</li>
        </ul>
    </div>
</body>
</html>
