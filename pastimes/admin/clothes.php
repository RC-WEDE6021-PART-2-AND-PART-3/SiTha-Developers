<?php
session_start();


$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

// Only admin can access
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$message = "";

// Add new clothing item
if(isset($_POST['add_item'])){
    $title      = $_POST['title'];
    $desc       = $_POST['description'];
    $price      = $_POST['price'];
    $size       = $_POST['size'];
    $brand      = $_POST['brand'];
    $category   = $_POST['category'];
    $condition  = $_POST['condition_type'];
    $image_url  = $_POST['image_url'];
    $user_id    = $_SESSION['user_id'];

    $sql = "INSERT INTO tblClothes (user_id, title, description, price, size, brand, category, condition_type, image_url)
            VALUES ($user_id, '$title', '$desc', $price, '$size', '$brand', '$category', '$condition', '$image_url')";

    if(mysqli_query($conn, $sql)){
        $message = "✅ Item added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

// Update clothing item
if(isset($_POST['update_item'])){
    $product_id = $_POST['product_id'];
    $title      = $_POST['title'];
    $desc       = $_POST['description'];
    $price      = $_POST['price'];
    $size       = $_POST['size'];
    $brand      = $_POST['brand'];
    $category   = $_POST['category'];
    $condition  = $_POST['condition_type'];
    $image_url  = $_POST['image_url'];

    $sql = "UPDATE tblClothes SET
                title='$title',
                description='$desc',
                price=$price,
                size='$size',
                brand='$brand',
                category='$category',
                condition_type='$condition',
                image_url='$image_url'
            WHERE product_id=$product_id";

    if(mysqli_query($conn, $sql)){
        $message = "✅ Item updated successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

// Delete clothing item
if(isset($_POST['delete_item'])){
    $product_id = $_POST['product_id'];
    $sql = "DELETE FROM tblClothes WHERE product_id=$product_id";
    if(mysqli_query($conn, $sql)){
        $message = "✅ Item deleted.";
    }
}

// Fetch all clothes
$result = mysqli_query($conn, "SELECT * FROM tblClothes ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clothes – Pastimes Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .modal-bg {
            display:none;
            position:fixed;
            top:0; left:0;
            width:100%; height:100%;
            background:rgba(0,0,0,0.45);
            z-index:999;
            justify-content:center;
            align-items:center;
        }
        .modal-bg.active { display:flex; }
        .modal {
            background:#fff;
            padding:36px;
            border-radius:14px;
            width:500px;
            max-width:95%;
            max-height:90vh;
            overflow-y:auto;
        }
        .modal h3 { color:#d4537e; margin-bottom:24px; font-size:20px; }
        .thumb {
            width:60px; height:60px;
            object-fit:cover;
            border-radius:8px;
        }
    </style>
</head>
<body>

<div class="announcement-bar">Pastimes Admin Panel</div>

<nav class="navbar">
    <a href="../index.php" class="logo">Pastimes</a>
    <div class="nav-links">
        <a href="dashboard.php">Users</a>
        <a href="clothes.php" style="color:#d4537e; font-weight:700;">Clothes</a>
        <a href="messages.php">Messages</a>
        <a href="reports.php">Reports</a>
    </div>
    <div class="nav-right">
        <span style="font-size:14px; color:#d4537e;">Admin: <?php echo $_SESSION['username']; ?></span>
        <a href="../logout.php" class="btn btn-pink">Sign Out</a>
    </div>
</nav>

<div class="admin-container">
    <h2>Manage Clothing Items</h2>
    <p style="color:#555; margin-bottom:20px;">Add, edit or remove items from the shop.</p>

    <?php if($message != ""): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Add Item Button -->
    <button onclick="document.getElementById('addModal').classList.add('active')"
            class="btn btn-pink" style="margin-bottom:24px;">
        + Add New Item
    </button>

    <!-- Clothes Table -->
    <table class="data-table">
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Size</th>
            <th>Condition</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <img src="../<?php echo $row['image_url']; ?>"
                     class="thumb"
                     onerror="this.src='https://placehold.co/60x60/fce4ee/d4537e?text=Item'">
            </td>
            <td><strong><?php echo $row['title']; ?></strong></td>
            <td><?php echo $row['brand']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['size']; ?></td>
            <td><?php echo $row['condition_type']; ?></td>
            <td style="color:#d4537e; font-weight:700;">
                R<?php echo number_format($row['price'], 2); ?>
            </td>
            <td style="display:flex; gap:6px;">
                <!-- Edit button -->
                <button onclick="openEdit(
                    '<?php echo $row['product_id']; ?>',
                    '<?php echo addslashes($row['title']); ?>',
                    '<?php echo addslashes($row['description']); ?>',
                    '<?php echo $row['price']; ?>',
                    '<?php echo $row['size']; ?>',
                    '<?php echo addslashes($row['brand']); ?>',
                    '<?php echo $row['category']; ?>',
                    '<?php echo $row['condition_type']; ?>',
                    '<?php echo $row['image_url']; ?>'
                )" class="btn btn-outline" style="padding:5px 10px; font-size:12px;">Edit</button>

                <!-- Delete button -->
                <form method="post" style="display:inline;"
                      onsubmit="return confirm('Delete this item?');">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                    <input type="submit" name="delete_item" value="Delete"
                           class="btn btn-outline"
                           style="padding:5px 10px; font-size:12px; color:#c00; border-color:#c00;">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- ADD ITEM MODAL -->
<div class="modal-bg" id="addModal">
    <div class="modal">
        <h3>Add New Clothing Item</h3>
        <form method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description">
            </div>
            <div class="form-group">
                <label>Price (R)</label>
                <input type="number" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Size</label>
                <select name="size">
                    <?php foreach(["XS","S","M","L","XL","XXL","26","28","30","32","34","36","38","40"] as $s): ?>
                    <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Brand</label>
                <input type="text" name="brand">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <?php foreach(["Dresses","Tops","Jeans","Jackets","Shoes","Accessories"] as $c): ?>
                    <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Condition</label>
                <select name="condition_type">
                    <option value="Like New">Like New</option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                </select>
            </div>
            <div class="form-group">
                <label>Image path (e.g. images/item1.jpeg)</label>
                <input type="text" name="image_url" placeholder="images/item1.jpeg">
            </div>
            <div style="display:flex; gap:12px; margin-top:10px;">
                <input type="submit" name="add_item" value="Add Item" class="btn btn-pink">
                <button type="button"
                        onclick="document.getElementById('addModal').classList.remove('active')"
                        class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT ITEM MODAL -->
<div class="modal-bg" id="editModal">
    <div class="modal">
        <h3>Edit Clothing Item</h3>
        <form method="post">
            <input type="hidden" name="product_id" id="edit_product_id">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" id="edit_title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" id="edit_desc">
            </div>
            <div class="form-group">
                <label>Price (R)</label>
                <input type="number" name="price" id="edit_price" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Size</label>
                <input type="text" name="size" id="edit_size">
            </div>
            <div class="form-group">
                <label>Brand</label>
                <input type="text" name="brand" id="edit_brand">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" id="edit_category">
                    <?php foreach(["Dresses","Tops","Jeans","Jackets","Shoes","Accessories"] as $c): ?>
                    <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Condition</label>
                <select name="condition_type" id="edit_condition">
                    <option value="Like New">Like New</option>
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                </select>
            </div>
            <div class="form-group">
                <label>Image path</label>
                <input type="text" name="image_url" id="edit_image">
            </div>
            <div style="display:flex; gap:12px; margin-top:10px;">
                <input type="submit" name="update_item" value="Update Item" class="btn btn-pink">
                <button type="button"
                        onclick="document.getElementById('editModal').classList.remove('active')"
                        class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes</div>
    </div>
</footer>

<script>
function openEdit(id, title, desc, price, size, brand, category, condition, image){
    document.getElementById('edit_product_id').value = id;
    document.getElementById('edit_title').value      = title;
    document.getElementById('edit_desc').value       = desc;
    document.getElementById('edit_price').value      = price;
    document.getElementById('edit_size').value       = size;
    document.getElementById('edit_brand').value      = brand;
    document.getElementById('edit_category').value   = category;
    document.getElementById('edit_condition').value  = condition;
    document.getElementById('edit_image').value      = image;
    document.getElementById('editModal').classList.add('active');
}
</script>

</body>
</html>