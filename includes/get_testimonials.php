<?php
/** [Not implemented yet]
 * Function to get testimonials for display on the home page
 * Includes both product-specific reviews and general website testimonials
 * 
 * @param int $limit Maximum number of testimonials to return
 * @return array Array of testimonials with customer names and review text
 */
function getTestimonials($limit = 10) {
    global $pdo;
    
    // Get all testimonials - both product reviews and general website testimonials
    $query = "
        SELECT 
            r.review_text, 
            r.rating,
            c.username,
            CASE WHEN r.product_id IS NULL THEN 1 ELSE 0 END AS is_general_testimonial
        FROM 
            reviews r
        JOIN 
            customers c ON r.customer_id = c.customer_id
        WHERE 
            r.rating >= 4
        ORDER BY 
            r.created_at DESC
        LIMIT ?
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $testimonials = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $testimonials[] = [
            'text' => $row['review_text'],
            'author' => $row['username'],
            'rating' => $row['rating'],
            'is_general' => $row['is_general_testimonial']
        ];
    }
    
    return $testimonials;
}
?>