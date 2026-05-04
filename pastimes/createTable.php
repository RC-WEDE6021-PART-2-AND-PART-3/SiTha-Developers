<?php
$conn = mysqli_connect("localhost", "root", "", "ClothingStore");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// Disable foreign key checks so we can drop freely
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");
mysqli_query($conn, "DROP TABLE IF EXISTS tblUser");
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

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

$file = fopen("userData.txt", "r");

if(!$file){
    die("❌ Cannot open userData.txt");
}

$count = 0;
while(($line = fgets($file)) !== false){
    $line = trim($line);
    if(empty($line)) continue;

    $data = explode("\t", $line);
    if(count($data) < 8) continue;

    $first  = mysqli_real_escape_string($conn, $data[0]);
    $last   = mysqli_real_escape_string($conn, $data[1]);
    $email  = mysqli_real_escape_string($conn, $data[2]);
    $uname  = mysqli_real_escape_string($conn, $data[3]);
    $pass   = mysqli_real_escape_string($conn, $data[4]);
    $role   = mysqli_real_escape_string($conn, $data[5]);
    $seller = (int)$data[6];
    $status = mysqli_real_escape_string($conn, $data[7]);

    $sql = "INSERT INTO tblUser (first_name, last_name, email, username, password, role, seller_status, verification_status)
            VALUES ('$first','$last','$email','$uname','$pass','$role',$seller,'$status')";

    if(mysqli_query($conn, $sql)){
        $count++;
    } else {
        echo "⚠️ Skipped: " . mysqli_error($conn) . "<br>";
    }
}

fclose($file);
echo "✅ $count users loaded.<br>";
echo "<br><a href='index.php'>Go to Homepage →</a>";
?>