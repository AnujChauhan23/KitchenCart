CREATE TABLE IF NOT EXISTS orders (
  order_id INT(11) AUTO_INCREMENT PRIMARY KEY,
  restaurant_id INT(11) NOT NULL,
  vendor_id INT(11) NOT NULL,
  product_id INT(11) NOT NULL,
  quantity INT(11) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Pending', 'Accepted', 'Delivered') DEFAULT 'Pending',
  FOREIGN KEY (restaurant_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (vendor_id) REFERENCES vendors(vendor_id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);
