# Pastimes – Pre-loved Clothing Store
## WEDE6021 Final POE

### Group Members:
- Sibongakonke Singo – ST10451618
- Thabile Ramushu – ST10444757

### Software Required:
- XAMPP (Apache + MySQL)
- PHP 8.x
- Web browser

### How to Run:
1. Install XAMPP from https://www.apachefriends.org
2. Place the pastimes folder in C:\xampp\htdocs\
3. Start Apache and MySQL in XAMPP Control Panel
4. Open phpMyAdmin at http://localhost/phpmyadmin
5. Create a database called ClothingStore
6. Run: http://localhost/pastimes/loadClothingStore.php
7. Run: http://localhost/pastimes/createTable.php
8. Visit: http://localhost/pastimes/index.php

### Database Setup:
- Database name: ClothingStore
- SQL script: myClothingStore.sql (included in project folder)
- Import via phpMyAdmin if needed

### Login Credentials:
| Role  | Username              | Password    |
|-------|-----------------------|-------------|
| Admin | admin@pastimes.co.za  | password123 |
| Buyer | johndoe               | password123 |
| Buyer | janesmith             | password123 |
| Seller| thabonkosi            | password123 |

### Features:
- User registration with admin verification
- Login with MD5 password hashing
- Shopping cart with add, edit, remove
- Checkout with order reference number
- Purchase history
- Admin dashboard - manage users and clothing
- Seller listing page
- Admin messaging system
