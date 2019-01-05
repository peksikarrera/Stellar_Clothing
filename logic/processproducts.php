<?php
include "connection.php";
if(isset($_POST['filterbtn'])){
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    if(!empty($_POST['pricemin'])&&!empty($_POST['pricemax'])){
        $minprice = $_POST['pricemin'];
        $maxprice = $_POST['pricemax'];
        $sort = $_POST['selectedsort'];
        switch($sort){
            case "pasc":
            $query="SELECT p.product_id as productID, cat.name as category, type_id as type, pp.source as source,alt,net_price as price, discount FROM ((((products p INNER JOIN productphotos pp ON pp.product_id = p.product_id) INNER JOIN prices pr ON pr.product_id = p.product_id) INNER JOIN categories_subcategories_types cst ON cst.product_id = p.product_id) INNER JOIN categories cat ON cat.category_id = cst.category_id) INNER JOIN subcategories s ON s.subcategory_id = cst.subcategory_id WHERE net_price >= $minprice AND net_price <= $maxprice AND cat.name = '$category' and s.name = '$subcategory' GROUP BY p.product_id ORDER BY net_price";
            break;
            case "pdesc":
            $query="SELECT p.product_id as productID, cat.name as category, type_id as type, pp.source as source,alt,net_price as price, discount FROM ((((products p INNER JOIN productphotos pp ON pp.product_id = p.product_id) INNER JOIN prices pr ON pr.product_id = p.product_id) INNER JOIN categories_subcategories_types cst ON cst.product_id = p.product_id) INNER JOIN categories cat ON cat.category_id = cst.category_id) INNER JOIN subcategories s ON s.subcategory_id = cst.subcategory_id WHERE net_price >= $minprice AND net_price <= $maxprice AND cat.name = '$category' and s.name = '$subcategory' GROUP BY p.product_id ORDER BY net_price DESC";
            break;
            case "discasc":
            $query="SELECT p.product_id as productID, cat.name as category, type_id as type, pp.source as source,alt,net_price as price, discount FROM ((((products p INNER JOIN productphotos pp ON pp.product_id = p.product_id) INNER JOIN prices pr ON pr.product_id = p.product_id) INNER JOIN categories_subcategories_types cst ON cst.product_id = p.product_id) INNER JOIN categories cat ON cat.category_id = cst.category_id) INNER JOIN subcategories s ON s.subcategory_id = cst.subcategory_id WHERE net_price >= $minprice AND net_price <= $maxprice AND cat.name = '$category' and s.name = '$subcategory' GROUP BY p.product_id ORDER BY discount";
            break;
            case "discdesc":
            $query="SELECT p.product_id as productID, cat.name as category, type_id as type, pp.source as source,alt,net_price as price, discount FROM ((((products p INNER JOIN productphotos pp ON pp.product_id = p.product_id) INNER JOIN prices pr ON pr.product_id = p.product_id) INNER JOIN categories_subcategories_types cst ON cst.product_id = p.product_id) INNER JOIN categories cat ON cat.category_id = cst.category_id) INNER JOIN subcategories s ON s.subcategory_id = cst.subcategory_id WHERE net_price >= $minprice AND net_price <= $maxprice AND cat.name = '$category' and s.name = '$subcategory' GROUP BY p.product_id ORDER BY discount DESC";
            break;
            default:
            $query="SELECT p.product_id as productID, cat.name as category, type_id as type, pp.source as source,alt,net_price as price, discount FROM ((((products p INNER JOIN productphotos pp ON pp.product_id = p.product_id) INNER JOIN prices pr ON pr.product_id = p.product_id) INNER JOIN categories_subcategories_types cst ON cst.product_id = p.product_id) INNER JOIN categories cat ON cat.category_id = cst.category_id) INNER JOIN subcategories s ON s.subcategory_id = cst.subcategory_id WHERE net_price >= $minprice AND net_price <= $maxprice AND cat.name = '$category' and s.name = '$subcategory' GROUP BY p.product_id ORDER BY net_price";
            break;
        }
        $result = $connection->query($query)->fetchAll();
        echo json_encode($result);
}
}