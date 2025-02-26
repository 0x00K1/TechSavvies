<div id="orders_display" class="content">
    <div class="div">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price per Unit</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
            
            <!-- 
                COMMENT FOR DATABSE FILL (REMOVE FROM LINE 17 AND LINE 36 WHEN READY FOR IMPLEMENTING)
            <?php if ($orders): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($order['price_per_unit'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($order['quantity'] * $order['price_per_unit'], 2)); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No recent orders found.</td>
                    </tr>
                <?php endif; ?> -->
            </tbody>
        </table>
    </div>
</div>
<!--ADD PHP CODE FOR DATABASE FETCH-->

