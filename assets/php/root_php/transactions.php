<div class="content" id="Transactions_display">
    <div class="div">
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Transaction ID</th>
                    <th>Amount</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <!-- COMMENT FOR DATABSE FILL (REMOVE FROM LINE 17 AND LINE 36 WHEN READY FOR IMPLEMENTING)
            <?php if ($transactions): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['payment_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($transaction['amount'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No recent transactions found.</td>
                </tr>
            <?php endif; ?> -->
            </tbody>
        </table>
    </div>
</div>