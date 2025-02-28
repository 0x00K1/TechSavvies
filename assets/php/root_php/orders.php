<div id="orders_display" class="content">
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
