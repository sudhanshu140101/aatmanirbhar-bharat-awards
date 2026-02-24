# Aatmanirbhar MSME & Startup Awards 2026

**Chamber of Indian Micro, Small & Medium Enterprises (CIMSME)**

A nomination and admin platform for the Aatmanirbhar Bharat Awards — recognizing excellence in MSMEs and startups across innovation, growth, sustainability, and social impact.



## Live site

- **Website:** [https://aatmanirbharbharatawards.indiansmechamber.com/](https://aatmanirbharbharatawards.indiansmechamber.com/)
- **Admin panel:** [https://aatmanirbharbharatawards.indiansmechamber.com/admin/index.php](https://aatmanirbharbharatawards.indiansmechamber.com/admin/index.php)

---

## Features

- **Public nomination form** — Categories include Aatmanirbhar Innovation, Startup of the Year, MSME Growth Champion, Women Entrepreneur Excellence, Sustainable Business Impact, Tech & Digital Transformation, Export & Global Impact, Social Innovation, Young Entrepreneur Rising Star, Best Rural Enterprise.
- **Admin panel** — Login, view/manage nominations, and related admin tasks.
- **PHP + MySQL** — Simple, server-side stack with PDO.



## Tech stack

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL  
- **Config:** `config.php` (not in repo; use example configs)

## Project Structure

   ├── index.html              # Main landing & nomination form
├── config.php              # DB config (create from config.example.php)
├── config.example.php      # Example config (safe to commit)
├── api/
│   ├── submit_nomination.php
│   └── update_payment.php
├── admin/
│   ├── index.php           # Admin dashboard
│   ├── login.php
│   ├── logout.php
│   └── config.php          # Create from admin/config.example.php
├── sql/
│   └── schema.sql          # Database schema
└── images/                 # Uploads 
