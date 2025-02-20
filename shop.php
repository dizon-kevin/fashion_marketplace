<?php
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];

    // Get product price
    $query = $conn->query("SELECT price FROM girl_dresses WHERE id = $product_id");
    $product = $query->fetch_assoc();
    $price = $product['price'];
    $total_price = $price * $quantity;

    // Insert order
    $sql = "INSERT INTO orders (product_id, customer_name, email, address, size, quantity, total_price)
            VALUES ('$product_id', '$customer_name', '$email', '$address', '$size', '$quantity', '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Order placed successfully!');</script>";
    } else {
        echo "<script>alert('Error placing order.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Fashion Marketplace</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: #fff; color: #000; }

        /* Header */
        header { display: flex; justify-content: space-between; align-items: center; padding: 20px; }
        .logo { font-size: 20px; font-weight: bold; }
        
        /* Centered Navigation */
        nav { display: flex; gap: 30px; justify-content: center; flex-grow: 1; }
        nav a { text-decoration: none; font-weight: 600; color: black; font-size: 14px; text-transform: uppercase; }

        /* Shop Now Button */
        .shop-btn { background-color: black; color: white; padding: 10px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; text-decoration: none; }

        /* Page Content */
        .content { text-align: center; padding: 50px 20px; }
        .content h1 { font-size: 48px; font-weight: 600; }
        .content p { font-size: 18px; margin-top: 10px; color: #444; }

        /* Product Grid */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; max-width: 1200px; margin: auto; padding: 40px 20px; }
        .product-card { border: 1px solid #ddd; padding: 20px; text-align: center; border-radius: 10px; }
        .product-card img { width: 100%; border-radius: 10px; }
        .product-card h3 { font-size: 20px; margin: 10px 0; }
        .product-card p { font-size: 14px; color: #777; }
        .product-card .price { font-size: 18px; font-weight: bold; color: black; margin: 10px 0; }
        .product-card .buy-btn { background-color: black; color: white; padding: 10px 15px; text-decoration: none; font-weight: bold; border-radius: 5px; display: inline-block; }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 20px; width: 40%; border-radius: 10px; text-align: center; }
        .close { float: right; font-size: 24px; cursor: pointer; }
    </style>
</head>
<body>

<header>
    <div class="logo">MARKETPLACE</div>
    <nav>
        <a href="index.php">HOME</a>
        <a href="catalogue.html">CATALOGUE</a>
        <a href="fashion.html">FASHION</a>
        <a href="favourite.html">FAVOURITE</a>
        <a href="lifestyle.html">LIFESTYLE</a>
    </nav>
</header>

<section class="content">
    <h1>EXPLORE OUR SHOP</h1>
    <p>Discover exclusive fashion pieces and accessories.</p>
</section>

<!-- Product Grid -->
<div class="product-grid">
    <?php
    $result = $conn->query("SELECT * FROM girl_dresses");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product-card'>
                <img src='".$row['image']."' alt='".$row['name']."'>
                <h3>".$row['name']."</h3>
                <p>".$row['description']."</p>
                <p class='price'>$".$row['price']."</p>
                <button class='buy-btn' onclick='openOrderForm(".$row['id'].", \"".$row['name']."\", ".$row['price'].")'>BUY NOW</button>
              </div>";
    }
    ?>
</div>

<!-- Order Form Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeOrderForm()">&times;</span>
        <h2>Place Your Order</h2>
        <form method="POST" action="">
            <input type="hidden" id="product_id" name="product_id">
            <p id="product_name"></p>
            <p>Price: $<span id="product_price"></span></p>
            <label>Name:</label>
            <input type="text" name="customer_name" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Address:</label>
            <textarea name="address" required></textarea>
            <label>Size:</label>
            <select name="size">
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>
            <label>Quantity:</label>
            <input type="number" name="quantity" min="1" required>
            <button type="submit">Place Order</button>
        </form>
    </div>
</div>

<script>
function openOrderForm(id, name, price) {
    document.getElementById('product_id').value = id;
    document.getElementById('product_name').innerText = name;
    document.getElementById('product_price').innerText = price;
    document.getElementById('orderModal').style.display = 'flex';
}

function closeOrderForm() {
    document.getElementById('orderModal').style.display = 'none';
}
</script>

</body>
</html>
