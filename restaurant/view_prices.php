<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";
include "../includes/header.php";

/* Query with yesterday price */
$query = "
SELECT 
    p.product_id,
    p.product_name,
    v.vendor_id,
    v.vendor_name,
    d.price AS today_price,
    d.price_date,
    (
        SELECT dp2.price 
        FROM daily_prices dp2
        WHERE dp2.product_id = d.product_id
          AND dp2.vendor_id = d.vendor_id
          AND dp2.price_date < d.price_date
        ORDER BY dp2.price_date DESC
        LIMIT 1
    ) AS yesterday_price
FROM daily_prices d
JOIN products p ON d.product_id = p.product_id
JOIN vendors v ON d.vendor_id = v.vendor_id
ORDER BY p.product_name, d.price ASC
";

$result = mysqli_query($conn, $query);
?>

<div class="container">

    <div class="page-header">
        <h1 class="page-title">Daily Prices</h1>
        <p class="page-subtitle">
            Compare today’s prices with historical trends.
        </p>
    </div>

    <!-- CARD START -->
    <div class="card-elevated">

        <div class="table-wrapper">
            <table>
            <tr>
                <th>Product</th>
                <th>Vendor</th>
                <th>Today Price</th>
                <th>Yesterday</th>
                <th>Trend</th>
                <th>Action</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                $bestPrices = [];

$bestQuery = "
SELECT product_id, MIN(price) AS min_price
FROM daily_prices
GROUP BY product_id
";

$bestResult = mysqli_query($conn, $bestQuery);

while ($bp = mysqli_fetch_assoc($bestResult)) {
    $bestPrices[$bp['product_id']] = $bp['min_price'];
}
                while ($row = mysqli_fetch_assoc($result)) {

                        $trend = "<div class='trend stable'><i class='ph ph-minus'></i> Stable</div>";

                        if ($row['yesterday_price'] !== null) {
                            if ($row['today_price'] > $row['yesterday_price']) {
                                $trend = "<div class='trend up'><i class='ph ph-trend-up'></i> Increased</div>";
                            } elseif ($row['today_price'] < $row['yesterday_price']) {
                                $trend = "<div class='trend down'><i class='ph ph-trend-down'></i> Decreased</div>";
                            }
                        }

                        $isBest = ($row['today_price'] == $bestPrices[$row['product_id']]);

                        echo "<tr>";
                    echo "<td>{$row['product_name']}</td>";
                    echo "<td>{$row['vendor_name']}</td>";
                        echo "<td><strong>₹{$row['today_price']}</strong>";
    if ($isBest) {
        echo "<span class='badge badge-best-price'>Best Price</span>";
    }
    echo "</td>";
                        echo "<td>" . ($row['yesterday_price'] ?? '-') . "</td>";
                        echo "<td>$trend</td>";
                        echo "<td>
                                <form action='place_order.php' method='POST' style='margin:0;display:flex;gap:0.5rem;align-items:center;'>
                                    <input type='hidden' name='product_id' value='{$row['product_id']}'>
                                    <input type='hidden' name='vendor_id' value='{$row['vendor_id']}'>
                                    <input type='hidden' name='price' value='{$row['today_price']}'>
                                    <input type='number' name='quantity' class='input-base' value='1' min='1' style='width:70px; padding:0.25rem 0.5rem;' required>
                                    <button type='submit' class='btn-primary btn-sm'>Order</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No price data available</td></tr>";
                }
                ?>

            </table>
        </div>

    </div>
    <!-- CARD END -->

</div>
</main>
</div>
</body>
</html>