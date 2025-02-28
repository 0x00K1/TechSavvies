<div class="content" id="Transactions_display">
                    <div class="search_div">
                        <input class="search-field_style" name="product_search_field" id="search_field" type="text"
                            placeholder="Search.. attribute:key ex(name:mustafa)" />
                        <input class="search-button_style" name="search_button" id="search_button" type="submit"
                            value="Search" />
                        <span>Filter:</span>
                        <select name="filter_value" id="filter_value" class="filter_value_style"
                            placeholder="Chose a value">
                            <option value="">None</option>
                            <option name="f_category" value="category">Category</option>
                            <option name="f_price" value="price">Price</option>
                            <option name="f_stock" value="stock">Stock</option>
                            <option name="f_size" value="size">Size</option>
                            <option name="f_update_date" value="update_date">update Date</option>
                        </select>
                        <button name="order_toggler" id="order_toggler">^</button>
                        <!-- some crazy js for ico change-->
                    </div>
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