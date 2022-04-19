<?php
    require_once('database.php');

    // Get category ID
    if (!isset($category_id)) {
        $category_id = filter_input(INPUT_GET, 'category_id', 
                FILTER_VALIDATE_INT);
        if ($category_id == NULL || $category_id == FALSE) {
            $category_id = 1;
        }
    }

    // Get name for current category
    $queryCategory = "SELECT * FROM categories
              WHERE categoryID = :category_id";
    $statement1 = $db->prepare($queryCategory);
    $statement1->bindValue(':category_id', $category_id);
    $statement1->execute();
    $category = $statement1->fetch();
    $statement1->closeCursor();
    $category_name = $category['categoryName'];

    // Get all categories
    $queryAllCategories = 'SELECT * FROM categories
              ORDER BY categoryID';
    $statement2 = $db->prepare($queryAllCategories);
    $statement2->execute();
    $categories = $statement2->fetchAll();
    $statement2->closeCursor();

    // Get products for selected category
    $queryProducts = "SELECT * FROM products
              WHERE categoryID = :category_id
              ORDER BY productID";
    $statement3 = $db->prepare($queryProducts);
    $statement3->bindValue(':category_id', $category_id);
    $statement3->execute();
    $products = $statement3->fetchAll();
    $statement3->closeCursor();
?>
<!DOCTYPE html>
<html>

<!-- the head section -->
<head>
    <title>My Guitar Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<!-- the body section -->
<body>
    <header><h1>Product Manager</h1></header>
    <main>
        <h1>Product List</h1>

        <aside>
            <!-- display a list of categories -->
            <h2>Categories</h2>
            <nav>
            <ul>
                <?php foreach ($categories as $category) : ?>
                <li><a href=".?category_id=<?php echo $category['categoryID']; ?>">
                        <?php echo $category['categoryName']; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            </nav>          
        </aside>

        <section>
            <!-- display a table of products -->
            <h2><?php echo $category_name; ?></h2>
            <table>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th class="right">Price</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo $product['productCode']; ?></td>
                    <td><?php echo $product['productName']; ?></td>
                    <td class="right"><?php echo $product['listPrice']; ?></td>
                    <td><form action="delete_product.php" method="post"
                              id="delete_product_form">
                        <input type="hidden" name="product_id"
                               value="<?php echo $product['productID']; ?>">
                        <input type="hidden" name="category_id"
                               value="<?php echo $product['categoryID']; ?>">
                        <input type="submit" value="Delete">
                    </form></td>
                    <td><form action="edit_product_form.php" method="post"
                              id="delete_product_form">
                        <input type="hidden" name="product_id"
                               value="<?php echo $product['productID']; ?>">
                        <input type="hidden" name="category_id"
                               value="<?php echo $product['categoryID']; ?>">
                        <input type="submit" value="Edit">
                    </form></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <p><a href="add_product_form.php">Add Product</a></p>
            <p><a href="category_list.php">List Categories</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> My Guitar Shop, Inc.</p>
    </footer>
</body>
</html>