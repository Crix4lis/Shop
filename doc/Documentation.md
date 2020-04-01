# Documentation

## REST API
There are two different resources:
 - **CATALOGUE** - contains products
 - **SHOP** - contains carts
 
 ### CATALOGUE
 #### Create Product
 - Request: `POST /catalogue/products`
 - Request Json Body:
     ```json
    {
        "name": "Title",        //required
        "price_value": "8.95",  //required
        "price_currency": "PLN" //required
    }
    ```
   - Requiriments:
        - `name`:
          - Must be unique - each string is converted to: "First Letter Capital" and trimmed
        - `price_value`: 
          - Must be convertable to float with precision 2
          - Precision over 2 will not be accepted ("8.879" will not be accepted)
        - `price_currency`:
          - The only available value so far is `"PLN"`
   - Return codes:
     - `200` - created
     - `400` - missing required field or wrong value
     - `409` - product with such name already exists
     - `500` - Oops...
   - Return header:
     - 'Location' - returns new product location and its uuid, example: `Location: /catalogue/products/6520249b-41a6-473f-84b7-5ae7fdd6e751`
 
 #### Get Product
 - Request: `GET /catalogue/products/{uuid}`
 - Return Json Body:
    ```json
   {
     "uuid": "6520249b-41a6-473f-84b7-5ae7fdd6e751",
     "name": "Title",
     "price_value": 8.95,
     "price_currency": "PLN"
   }
   ```
   - Return codes:
     - `200` - with content
     - `404` - not found product with such uuid
     - `500` - Oops...

#### Get Products
> Returns 3 products per page
- Request: `GET /catalogue/products/{uuid}?page=1`
- Return Json BOdy:
   ```json
  {
    "products": [
      {
        "uuid": "5ea75e00-e9d2-49a1-a9b8-7e0f93cf09ec",
        "name": "Title",
        "price_value": 8.95,
        "price_currency": "PLN"
      },
      {
        "uuid": "6520249b-41a6-473f-84b7-5ae7fdd6e751",
        "name": "Title 2",
        "price_value": 8.95,
        "price_currency": "PLN"
      }
    ],
    "page": 1
  }
   ```
   - Return codes:
     - `200` - with content (might return empty 'products' array)
     - `400` - page is less than 1
     - `500` - Oops...

#### Remove product
> This method will not return 404 if product already does not exist
- Request: `DELETE /catalogue/products/{uuid}`
- Return codes:
  - `200` - deleted
  - `500`

#### Edit product
> Note: This method at current implementation is idempotent 
- Request: `PATCH /catalogue/products/{uuid}`
- Request Json Body:
    ```json
    {
      "new_name": "The Test Updated",
       "new_price": "5.99"
    }
    ```
     - Requiriments:
          - one of the fields must be present, or both for request to be valid
          - `new_price`:
             - Must be convertable to float with precision 2
             - Precision over 2 will not be accepted ("8.879" will not be accepted)
          
- Return codes:
  - `200` - updated
  - `409` - such name already exists
  - `400` - missing fields or wrong values
  - `500` - ;(

 ### SHOP
 #### Create Cart
 > This method will accept any uuid, event for product that does not exist in the catalogue.<br>
> That's because cart stores only references to real products
 - Request: `POST /catalogue/products`
 - Request Json Body:
     ```json
        {
            "product": "98ac056a-f5e6-495d-87ca-8618cd8b3c31"
        }
    ```
   - Requiriments:
        - To create product you MUST give it a product reference (product uuid)
   - Return codes:
     - `200` - created
     - `400` - missing required field or wrong value
     - `500` - Oops...
   - Return header:
     - 'Location' - returns created cart location and its uuid, example: `Location: /shop/carts/34d3cbe4-bb3c-4af9-9baa-497c26a55bf2`

#### Get Cart
 > Remember this endpoint returns cart and references to products. Those products may not exist<br>
> Following endpoint WILL return true products
 - Request: `GET /shop/carts/{uuid}`
 - Return Json Body:
    ```json
    {
      "cart_uuid": "18256181-500b-413a-9e52-42ccc560bd56",
      "products": [
        {
          "product_uuid": "b34198a2-b6d3-4f3b-9482-017b76a76ace",
          "quantity": 3
        },
        {
          "product_uuid": "5ea75e00-e9d2-49a1-a9b8-7e0f93cf09ec",
          "quantity": 3
        }
      ]
    }
   ```
   - Return codes:
     - `200` - with content
     - `404` - not found product with such uuid
     - `500` - Oops...

#### Get Cart's products
> References to products that do not exist in catalogue will not be returned<br>
> This endpoint will return up to 3 items in 'products' array per page<br>
> If on specified page there are no items 'products' array will be empty<br>
 - Request: `GET /shop/carts/{uuid}/products?page=1`
 - Return Json Body:
    ```json
    {
      "products": [
        {
          "product_uuid": "5ea75e00-e9d2-49a1-a9b8-7e0f93cf09ec",
          "product_name": "The Name",
          "product_price_value": 8.95,
          "product_price_currency": "PLN",
          "quantity": 3
        }
      ],
      "uuid": "18256181-500b-413a-9e52-42ccc560bd56", //cart Uuid
      "page": 1,
      "total_price_currency": "PLN",
      "total_price_value": "26.85"
    }
   ```
   - Return codes:
     - `200` - with content (might return empty 'products' array)
     - `404` - cart with such uuid does not exist
     - `400` - page is less than 1
     - `500` - Oops...
     
#### Get Cart's products
> This method is not idempotent, each time it will add another product reference or 
>references specified in body
 - Request: `PATCH /shop/carts/{cartUuid}/products`
 - Request Json Body:
    ```json
    {
        "products": ["98ac056a-f5e6-495d-87ca-8618cd8b3c31", "88ac056a-f5e6-495d-87ca-8618cd8b3c35"]
    }
   ```
   - Return codes:
     - `200` - ok
     - `404` - cart with such uuid does not exist
     - `400` - missing products key, empty key, or invalid data
     - `500` - Oops...

#### Remove Cart's product references
> Not idempotent<br>
> This method will not return 404 if product already does not exist<br>
> If there are 2 references to the same product, each call will delete one reference
- Request: `DELETE /shop/carts/{cartUuid}/products/{productUuid}`
- Return codes:
  - `200` - deleted
  - `404` - cart with such uuid does not exist
  - `500` - =(
  
  