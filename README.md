# API Calls using Laravel 5.3

The app is able to do the following,

1. Internal API Calls (GET/POST x-www-form-urlencoded)
2. External API Call (POST usin JSON(application/json))

## API Endpoints implemented

###Internal

1. http://115.248.209.92/api/public/api/products<br>

   Lists all products, the respective variants and the associated attributes

   Method: GET<br>
   Response Status: 200 OK<br><br>
   {
     "products": [
       {
         "id": 1,
         "name": "2 Seater",
         "Collection_name": "Ake",
         "variants": []
       },
       {
         "id": 2,
         "name": "3 Seater",
         "Collection_name": "Ake",
         "variants": []
       },
       {
         "id": 3,
         "name": "Ottoman",
         "Collection_name": "Ake",
         "variants": [
           {
             "id": 6658,
             "sku": "OTTMANBLAC",
             "cost_price": 148,
             "is_active": "yes",
             "attributes": [
               {
                 "id": 40,
                 "attribute_name": "Colour",
                 "value": "Black & White"
               }
             ]
           }
         ]
       },
       {
         "id": 4,
         "name": "Armchair",
         "Collection_name": "Ake",
         "variants": [
           {
             "id": 6649,
             "sku": "ARMCHBLUE",
             "cost_price": 100,
             "is_active": "yes",
             "attributes": [
               {
                 "id": 39,
                 "attribute_name": "Colour",
                 "value": "Pink"
               }
             ]
           }
         ]
       },
       {
         "id": 5,
         "name": "Shelf",
         "Collection_name": "Alf",
         "variants": []
       }
     ],
     "count": 5,
     "pages": 1,
     "current_page": 1
   }

2. http://115.248.209.92/api/public/api/products/{id}/variants<br>
   
   List all variants of a product specified by product_id
   
   Method: GET<br>
   Response Status: 200 OK<br><br>
   {
     "product_variants": [
       {
         "id": 6658,
         "product_id": 3,
         "variant_id": 6658,
         "sku": "OTTMANBLAC",
         "cost_price": 148,
         "is_active": "yes"
       }
     ],
     "count": 1,
     "pages": 1,
     "current_page": 1
   }

3. http://115.248.209.92/api/public/api/variants/{id}/attributes<br>

   Update attribute value associated with a product variant. If attribute does not exist, create attribute and associate with the product variant
   
   Method: POST<br>
   Input Params (x-www-form-urlencoded): attribute[name],attribute[value]<br>
   Response Status: 200 OK<br><br>
   {
   "id": 1,
   "variant_id": 1,
   "attribute_id": X,
   "value": "Shape",
   "attribute_name": "Round"
   }

3. http://115.248.209.92/api/public/api/variants/{id}/attributes/{name}<br>

   Update attribute value associated with a product variant. If attribute does not exist, display an error

   Method: POST<br>
   Input Params (x-www-form-urlencoded): attribute[value]<br>
   Response Status: 200 OK<br><br>
   {
      "id": 1,
      "variant_id": 1,
      "attribute_id": 1,
      "value": "Yellow",
      "attribute_name": "colour"
   }

###External

1. http://115.248.209.92/api/public/api/order/make<br>
   Method: GET<br>
   JSON Payload (application/json):<br>
   {
    "Order": {
        "customer": "Gabriel Jaramillo",
        "address": "test address",
        "total": 100,
        "source": "ShopClues",
        "group_dispatch": "yes",
        "Status": "new",
        "payment": "credit",
        "items": [{
          "sku": "OTTMANBLAC",
          "quantity": 2
        }, {
          "sku": "ARMCHBLUE",
          "quantity": 1
        }]
      }
    }<br>
    
    Response Status: 200 OK<br>
    

## Installation 

1. Checkout HEAD rivision of this repo to the webserver's root folder 
2. Create MySql Database, brosa_test
3. Import SQL (http://115.248.209.92/api/api.sql)
4. Modify DB_ parameters in the env file to reflect the connection to the database
5. Use Postman or similar API Client to consume webservices

## Assumptions

1. The database dump provided had the structure followed ('brosa\Models\ProductVariant') in the tables with polymorphic relation. This has been changed to reflect the structure of the test app  
2. Because the default value of created_at and updated_at was showing up as invalid, it is channged to CURRENT_TIMESTAMP from 0000-00-00 00:00:00
3. created_by is assumed to be 1 throughout the app
4. It is assumed that the JSON schema will not changed and hence the validation of the JSON object is not implemented
5. Pagination is implemented for the results of the GET endpoints. It is assumed that 'page' parameter will be passed in the query string to increment the page
6. In the endpoint /api/products/{id}/variants/ response expected, id and variant_id are the same
7. In the endpoint /api/products/ response expected, under variant, name must not exist since variant does not have name as per the product_variant table

## To do

1. Since this is my first production/staging level implementation, I have not fully realised the potential of Laravel. I would like to study more and look at the possibilties to make the app more structured and optimised
2. Considering the size of the app some model functions are included and called from within the controller itself. That can be refactored. 
3. The code is compliant to the PSR-2 standards which I am experienced with. I have attempted to comply to PSR-4. If there is room for improvement, kindly suggest. 
4. Unit test the make Order API call
5. The test app can have the user Auth implemented to streamline the transactions, especially the make Order
