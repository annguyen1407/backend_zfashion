<?php
require_once('../config/Database.php');
require_once('../models/Product.php');
require_once('../models/Response.php');
/** ------------------------------------------------------------------------------------------------------------ */
/** 
 * *các api có dạng 
 * *{
    "success": true,
    "statusCode": 200,
    "message": [],
    "data": {
        "rows_return": 1,
        "product": {
            "id": "1",
            "title": "Áo thun nam chuột Mickeys",
            "status": "New",
            "imgSrc": "test",
            "price": "200000",
            "color": "yellow,white,navy,orange,pink",
            "size": "S,M,L,XL",
            "description": "Áo với form dáng thoải mái, với chất liệu vải 100% cotton dễ chịu khi mặc. Là trang phục hàng ngày hoàn hảo, dễ dàng kết hợp với mọi thứ"
        }
    }
}
* * Bao gồm 4 phần:
* * - success: trạng thái báo thành công hay thất bại
* * - statusCode: mã http code trả về
* * - message: message từ server trả về, có thể rỗng
* * - data: phần dữ liệu của server lấy từ database trả về
*/


/** ------------------------------------------------------------------------------------------------------------ */
try {
    //* kết nối tới cơ sở dữ liệu
    $database = new Database();
    $db = $database->getConnection();
} catch (PDOException $e) {
    //* kết nối thất bại
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("Kết nối thất bại");
    $response->send();
    exit();
}

//* array_key_exists('id', $_GET): nếu có giá trị id trong request gửi lên => thao tác cho 1 sản phẩm
if (array_key_exists('id', $_GET)) {

    $productId = $_GET['id'];
    if ($productId == '' || !is_numeric($productId)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("ID sản phẩm không được rỗng và phải là số");
        $response->send();
        exit();
    }

    /** 
     * * lấy sản phẩm theo id
     * * vd lấy thông tin sản phẩm có id = 1: http://localhost/backend_zfashion/products/1 
    */
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        try {
            $query = 'SELECT * FROM product WHERE product_id =:productId LIMIT 1';
            $stmt = $db->prepare($query);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();

            $rowCount = $stmt->rowCount();

            if ($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Không tìm thấy id sản phẩm");
                $response->send();
                exit();
            }
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new Product($row['product_id'], $row['title'], $row['state'], $row['imgSrc'], $row['price'], $row['color'], $row['size'], $row['description']);
                $prodArr = $product->returnProductArray();
            }

            $returnData = array();
            $returnData['rows_return'] = $rowCount;
            $returnData['product'] = $prodArr;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit;

        } catch (ProductException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;
        }
        catch(PDOException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;
        }
    } 
    elseif ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        //TODO: tạo sản phẩm
    } 
    elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') 
    {
        //TODO: xoá sản phẩm theo id
    } 
    else 
    {
        //* request method không hợp lệ
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit;
    }
}
//* empty($_GET): thao tác trên cả bảng dữ liệu products
else if (empty($_GET)) {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        /** 
        * * lấy tất cả sản phẩm 
        * * vd lấy thông tin tất cả sản phẩm: http://localhost/backend_zfashion/products
        */
        try 
        {
            $query = 'SELECT * FROM product ORDER BY product_id ASC';
            $stmt = $db->prepare($query);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $prodArr = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new Product($row['product_id'], $row['title'], $row['state'], $row['imgSrc'], $row['price'], $row['color'], $row['size'], $row['description']);
                $prodArr[] = $product->returnProductArray();
                
            }
           
            $returnData = array();
            $returnData['rows_return'] = $rowCount;
            $returnData['products'] = $prodArr;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->toCache(true);
            $response->send();
            exit;

        } catch (ProductException $e) {
            //* lấy danh sách sản phẩm không thành công
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;
        }
    } 
    else if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        //TODO: tạo danh sách sản phẩm (thêm nhiều sản phẩm 1 lúc)
    } 
    else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit;
    }
}
else
{
    //* khi người dùng nhập uri không đúng quy tắc vd /products/abc => trả về lỗi
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Không tìm thấy endpoint");
    $response->send();
    exit;
}
