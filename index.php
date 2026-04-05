<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 5 - Web Security Vulnerability Demo</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 2rem;
            line-height: 1.5;
            background: #f7f7fb;
            color: #1f2937;
        }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }
        h1, h2 {
            margin-top: 0;
        }
        ul {
            margin: 0.5rem 0 0 1.25rem;
        }
        code {
            background: #eef2ff;
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
        }
        .warn {
            background: #fff7ed;
            border-color: #fdba74;
        }
    </style>
</head>
<body>
    <h1>Lab 5: Common Web Vulnerabilities in PHP</h1>

    <div class="card warn">
        <strong>For learning only:</strong> This lab includes intentionally vulnerable examples.
        Do not deploy vulnerable mode in production systems.
    </div>

    <div class="card">
        <h2>Setup</h2>
        <ol>
            <li>Run <code>init_db.php</code> once to create the SQLite database.</li>
            <li>Open each demo and test <code>?mode=vuln</code> and <code>?mode=secure</code>.</li>
        </ol>
        <p>Example: <code>sqli_demo.php?mode=vuln</code></p>
    </div>

    <div class="card">
        <h2>Demos</h2>
        <ul>
            <li><a href="init_db.php">Initialize Database</a></li>
            <li><a href="xss_demo.php?mode=vuln">XSS Demo (Vulnerable)</a></li>
            <li><a href="xss_demo.php?mode=secure">XSS Demo (Secure)</a></li>
            <li><a href="sqli_demo.php?mode=vuln">SQL Injection Demo (Vulnerable)</a></li>
            <li><a href="sqli_demo.php?mode=secure">SQL Injection Demo (Secure)</a></li>
            <li><a href="csrf_demo.php?mode=vuln">CSRF Demo (Vulnerable)</a></li>
            <li><a href="csrf_demo.php?mode=secure">CSRF Demo (Secure)</a></li>
        </ul>
    </div>

    <div class="card">
        <h2>Best Practices Summary</h2>
        <ul>
            <li>XSS: Escape output with <code>htmlspecialchars</code>, validate input, and consider Content Security Policy.</li>
            <li>SQL Injection: Always use prepared statements with bound parameters. Never concatenate untrusted input into SQL.</li>
            <li>CSRF: Use anti-CSRF tokens, check request origin/referer where possible, and set <code>SameSite</code> cookies.</li>
        </ul>
    </div>
</body>
</html>
