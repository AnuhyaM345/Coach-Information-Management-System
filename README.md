# 🚌 Coach Information Management System (CIMS)

The **Coach Information Management System (CIMS)** is a web-based application developed to manage coach-related information efficiently for Indian Railways. This system simplifies data handling, tracking, and reporting for administrative and user-level operations.

---

## 📌 Features

- 🧑‍💼 **Admin Module**  
  - Add, view, edit, and delete coach information  
  - Manage coach holdings and POH (Periodical Overhaul) progress  
  - Generate PDF reports  

- 👥 **User Module**  
  - View coach details and progress  
  - Access filtered reports and downloadable data  

- 📊 **Reports & Exports**  
  - Dynamic PDF generation  
  - Printable views of coach status  

- 🔒 **Authentication**  
  - Secure login for Admin and Users  
  - Session management and access control  

---

## 🛠️ Tech Stack

| Layer       | Technology               |
|-------------|---------------------------|
| Frontend    | HTML, CSS, JavaScript     |
| Backend     | PHP                       |
| Database    | MySQL, SSMS               |
| Reporting   | FPDF (for PDF generation) |
| Server      | XAMPP / Apache            |

---

## ⚙️ Installation & Setup

### 🔧 Requirements
- [XAMPP](https://www.apachefriends.org/index.html) (PHP, Apache, MySQL)
- Git (optional, for version control)

### 🪜 Steps

1. Clone the repository or download the ZIP  
   ```bash
   git clone https://github.com/AnuhyaM345/Coach-Information-Management-System.git
2. Place the project folder inside:

   C:/xampp/htdocs/

3. Import the MySQL database:

- Open http://localhost/phpmyadmin

- Create a new database named cims (or your preferred name)

- Import the .sql file from the DB_BACKUP/ folder (add it if you haven't)

4. Start Apache and MySQL from the XAMPP control panel

5. Run the project in your browser:

    http://localhost/Coach-Information-Management-System/

## 🧪 Sample Credentials

Update these based on your own configuration

    Admin Login:
    Username: admin
    Password: admin@123

    User Login:
    Username: user1
    Password: user@123

## 📂 Project Structure
    
    CIMS_FINAL/
    ├── ADMIN/
    ├── USER/
    ├── FPDF/
    ├── DB_BACKUP/            # Add your .sql file here
    ├── assets/
    ├── index.php
    ├── login.php
    ├── logout.php
    └── ...
  
---

## 👩‍💻 Developer

Anuhya Mattaparthi

🎓 B.Tech, CSE, IFHE

🌐 https://github.com/AnuhyaM345

---

## 📜 License

This project is **not open-source** and is intended strictly for academic purposes.  
No permission is granted to use, copy, distribute, or modify any part of this project for commercial or non-academic use.  
All rights reserved © Anuhya M.