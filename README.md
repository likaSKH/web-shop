## Local Setup
#### Clone Repository
    git clone git@github.com:likaSKH/web-shop.git
    cd web-shop
#### Install Dependencies
    composer install
#### Setup env
    cp .env.example .env
Make sure to set database variables values correctly 
#### Set up Sail
    php artisan sail:install
#### Run Sail
    ./vendor/bin/sail up -d
#### Generate app key
    ./vendor/bin/sail artisan key:generate
#### Run migrations and seeders
    ./vendor/bin/sail artisan migrate --seed
#### Compile Frontend Assets
    ./vendor/bin/sail npm install && ./vendor/bin/sail npm run build

## Filament Admin Access
- URL : {your-host}/admin
- Credentials:
  - **Email**: admin@domain.com
  - **Password**: password // All Users will have this as a password 

## Features
### Admin Panel - Admin user
- Categories
  - Listing of Categories
  - Creating new Category with option to choose parent category
  - Edit Category
 - Orders
   - Listing of orders
     - With ability to filter Canceled Orders
     - Cancel Order
   - Create new Order with multiple products
   - View existing Order
 - Products
   - List of Products
   - Create new Product
   - Edit existing Product
 - Customers
   - List of Customers
   - Edit Balance 
   - View customers orders
   - Create new customer
### WebShop Front
- Listing of Products
- Ability to filter products by categories
- Ability to search products by name
- Ability to sort products by:
  - Name Ascending
  - Name Descending
  - Price Ascending
  - Price Descending
- Pagination for products
- If product quantity is available Add To Cart button appears
  - On Add To Cart modal 
    - You choose quantity of product to add to cart
    - You can see total price and available balance on account
    - Cancel - cancels action
    - Confirm - Will add item(s) to users cart
- Cart - User can:
  - Remove product from cart
    - Outputs confirmation modal
  - Change quantity
  - See their balance
  - See Total price of order as well as total price calculated by each item
  - See Order button
    - Outputs order confirmation
      - Cancel - cancels an action
      - Confirmation - places an order, which user can see in their Orders page
- Orders - on this page user can:
  - See list of placed orders with:
    - Status
    - Date
    - Order number
    - Products list
    - Total and individual price by product
  - Cancel order if status is still pending - this action requires approval
- Notifications are available for successfully and unsuccessfully actions, with description texts
- Users can navigate to Profile where they can see their balance and additional information
- Orders link is available in navigation which will link to Filament dashboard Orders list

