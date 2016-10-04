<?php

/**
 * Controller class to list all products and to list 
 * all produst_variants of a specified product.
 * 
 * 
 * @package    Http
 * @subpackage Controllers
 * @author     Vikas Thakur <vikascalls@gmail.com>
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Processors\ProductProcessors;

class ProductsController extends Controller 
{

    private $rpp = 1; //Results Per Page

    /**
     * List all products, their respective variants 
     * and the attributes of the variants
     * 
     * @return JSON object 
     */

    public function index() {

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });

        $products = new ProductProcessors();
        $result = $products->fetchAllProducts($this->rpp);

        return json_encode($result);
    }

    /**
     * Retrieve all product_variants of a product
     * 
     * @param  int  $id
     * @return JSON object  
     */
    public function getVariants($id) 
    {
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        Paginator::currentPageResolver(function() use ($currentPage) {

            return $currentPage;
        });

        $products = new ProductProcessors();
        $result = $products->fetchProductVariants($this->rpp,$id);
        
        return json_encode($result);
    }
}
?>
