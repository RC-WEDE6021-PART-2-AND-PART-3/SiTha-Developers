<?php
// loadClothingStore.php - Creates all tables, keeps clothes data safe
$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

// Disable foreign key checks so tables can be dropped safely
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

// Drop only user-related tables, NEVER tblClothes
$tables = ["tblOrderItems", "tblCart", "tblMessages", "tblAorder", "tblAdmin", "tblUser"];
foreach($tables as $table){
    mysqli_query($conn, "DROP TABLE IF EXISTS `$table`");
}

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
echo "✅ Tables dropped (clothes kept safe).<br>";

// tblUser
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'buyer',
    seller_status BOOLEAN DEFAULT FALSE,
    verification_status VARCHAR(20) DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "✅ tblUser created.<br>";

// tblAdmin
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tblAdmin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
)");
echo "✅ tblAdmin created.<br>";

// tblClothes - only create if it doesnt exist, never drop it
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tblClothes (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    size VARCHAR(10),
    brand VARCHAR(50),
    category VARCHAR(50),
    condition_type VARCHAR(50),
    image_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
echo "✅ tblClothes safe.<br>";

// tblAorder
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tblAorder (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2),
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    payment_status VARCHAR(30) DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
)");
echo "✅ tblAorder created.<br>";

// tblCart
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tblCart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES tblClothes(product_id) ON DELETE CASCADE
)");
echo "✅ tblCart created.<br>";

// tblOrderItems
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tblOrderItems (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT,
    FOREIGN KEY (order_id) REFERENCES tblAorder(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES tblClothes(product_id) ON DELETE CASCADE
)");
echo "✅ tblOrderItems created.<br>";

// tblMessages
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS tblMessages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message_text TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES tblUser(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
)");
echo "✅ tblMessages created.<br>";

echo "<br>🎉 Done! Clothes data is safe! <a href='createTable.php'>Load user data →</a>";
?>