<div class="div" id="Reviews_Display">
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Rating</th>
                    <th>Review Text</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <!-- COMMENT FOR DATABSE FILL (REMOVE FROM LINE 17 AND LINE 36 WHEN READY FOR IMPLEMENTING)
                <?php if ($reviews): ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review['review_id']); ?></td>
                            <td><?php echo htmlspecialchars($review['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($review['username']); ?></td>
                            <td><?php echo htmlspecialchars($review['product_id']); ?></td>
                            <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($review['rating']); ?></td>
                            <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                            <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No recent reviews found.</td>
                    </tr>
                <?php endif; ?>
                -->
            </tbody>
        </table>
    </div>
</div>