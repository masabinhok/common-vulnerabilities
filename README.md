# Lab 5 - Common Web Vulnerabilities in PHP

This lab demonstrates three common web vulnerabilities and secure coding practices:

- Cross-Site Scripting (XSS)
- SQL Injection
- Cross-Site Request Forgery (CSRF)

## Files

- `index.php` - Landing page with links and summary
- `security.php` - Shared security helpers (headers, escaping, mode parsing, validation, session hardening)
- `init_db.php` - Creates `lab5.sqlite` and seeds sample users
- `xss_demo.php` - Vulnerable and secure output rendering
- `sqli_demo.php` - Vulnerable and secure database query handling
- `csrf_demo.php` - Vulnerable and secure form submission checks

## Run Locally

From `lab5` folder:

```bash
php -S localhost:8000
```

Then open:

http://localhost:8000/index.php

Run `init_db.php` once before testing SQL injection examples.

## Vulnerability Notes

### 1) Cross-Site Scripting (XSS)

- Vulnerable mode prints user input directly into HTML.
- Secure mode escapes output and constrains max input length.

### 2) SQL Injection

- Vulnerable mode concatenates user input into SQL.
- Secure mode uses prepared statements with bound parameters and strict username validation.

### 3) Cross-Site Request Forgery (CSRF)

- Vulnerable mode accepts state-changing POST without CSRF token validation.
- Secure mode verifies a session-bound token via `hash_equals`, rotates token after use, and checks request origin.

## Security Best Practices

- Escape untrusted data at output time with context-aware encoding.
- Use prepared statements everywhere; never concatenate untrusted SQL input.
- Add CSRF tokens to all state-changing requests.
- Set secure session cookies (`HttpOnly`, `Secure`, `SameSite`).
- Send defensive response headers (CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy).
- Validate and constrain all input (type, range, format, length).
- Enforce least privilege for database users and services.
- Log suspicious requests and review security events.
