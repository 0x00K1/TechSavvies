<div class="div" id="Reviews_Display">
                    <div class="content">
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
            </div>
        </div>