# Ze Yu

## A. My topic
> One sentence describing your app:
>
> `Clothing Shop`

## B. My tables (aim for 4+)

| Table name | What it stores | Columns (incl. primary key) |
|---|---|---|
| Users | | id, username, email, password, role |
| Products | | id, product_name, price |
| Orders | | id, order_by (FK) |
| Order_Products | | id, order_id (FK), product_id (FK) |

## C. My relationships (aim for 3+)

Write each as a "belongs to" sentence, then name the foreign key.

| # | Relationship sentence | Foreign key column | Points to |
|---|---|---|---|
| 1 | Every order belongs to a user | `order.order_by` | `user.id` |
| 2 | Every order_product belongs to a order | `order_product.order_id` | `order.id` |
| 3 | Every order_product has a product | `order_product.product_id` | `product.id` |

## D. My roles & permissions (2+ roles)

| Feature | Role 1: admin | Role 2: user |
|---|---|---|
| Manage Products | YES | NO |
| View Products & Make purchase | YES | YES |
| Manage orders (UD) | YES | NO |
| | | |

## E. My pages & access

| Page | What it does | Is it a form? | Who can access |
|---|---|---|---|
| | | | |
| | | | |
| | | | |
| | | | |