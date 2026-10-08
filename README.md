# Bright Star Public Elementary School

PHP site for Hostinger. MySQL settings live in `.env`. Do not commit that file.

## Hostinger

1. Create a MySQL database in hPanel and note the host, database name, user, and password.
2. Upload this folder to `public_html`.
3. Copy `.env.example` to `.env` and paste the Hostinger database values.
4. Open the site once. Tables are created automatically. `database/schema.sql` is there if you prefer phpMyAdmin.
5. Admin: `/admin/login.php` — username and password come from `.env` on the first visit only.

Change `SCHOLARSHIP_FEE`, `SCHOLARSHIP_DATE`, JazzCash, and Easypaisa numbers in `.env`, then replace that file. No code change is needed.

## Scholarship test

The home page announces the free scholarship test. Parents register at `scholarship.php`, pay the registration fee, and enter the transaction ID. Admin marks each form pending, paid, verified, or rejected.

The scholarship seat is free. The registration fee is separate and can be set to `0`.
