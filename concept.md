# Projek Concept
可以base on 潮牌网站做参考
- tntco
- swaganz
- double 7
- against lab
- Hype
- Stoned & CO
- society

# User Role
### USER
可以：
- view  products
- make purchase
- view Own Orders / Purchase History

不可以
- manage products （ Edit , Delete , Add ）
- Manage order (Delete Order)

### ADMIN 
可以：
- view products
- make purchase
- Manage Products (Add ,Edit ,Delete)
- Manage Order (View Order, Update Order status , Delete)
- view users

--- 

# Table

### **Table users** :
### Column 
- user_id
- username
- email
- password
- role ( admin & user)
- address (NULL)
- phone (NULL)

### **Table Orders** :
### Column
- order_id 
- user_id (FK 跟 user table 的user_id , 可以就可以check 看这个Order 是哪一个user order 的)
- purchase_date （购买日期）
- total_price (算这个 Order id 的总共购买多少个product 的 total price)
- status (Pending ,Processing ,Shipped,Completed)
- delivery_address (NOT NULL)
- contact_phone (NOT NULL)

### **Table order_product** :
### Column 
- order_product_id
- order_id (FK)
- product_id(FK)
- size_id (FK 去 product_size 的 size_id)
- quantity
 
### **Table Products** :
### Column
- product_id
- product_name
- brand (分这个衣服是什么潮牌牌子)
- price (product 多少钱)
- category （hoodie ， cap ，t shirt , accesories）
- image (product 的展示图)
- description (NULL) (TEXT)
- washing_instruction(NULL)(TEXT)
- size_guide (NULL) (TEXT(可以写html table 做size guide (参考stone)))

### **Table product_size**
### Column
- size_id
- product_id (FK 去 product 的 product_id)
- size
- stock

# My Page
### 1. **AUTHENTICATION**
1. **login-form.php** → Email / Username , Password , 验证密码, 登录成功进入网站
2. **register-form.php** → 输入 Username ,Email, Password , create new account
3. **logout.php** → ( Not Sure )

### 2. **USER SIDE**
1. **index.php** → 显示全部商品,图片,名称,价格 (做一个)
2. **product.php** → 查看商品详情:
- show image , product name ,brand, price ,category ,stock
- 会显示你要几个 quantity 可以让customer 写 在按check out
- 显示Size Button（S/M/L/XL） 就可以show 对应的size 的stock
- 显示Detail , shipping ,washing instrution
- size guide 
- "you might also like" Section
- footer - show copyright , about , contact , delivery & shipping , privacy policy , terms of user
3. **checkout.php** → 购买商品
- 在product.php click check out button 就会去到 checkout.php
- show order summary → show product name , image , brand , price , subtotal ,shipping fee RM10, Total Price 
- Show Contact → Email , Delivery (Delivery Address , Contact Phone)
- show Payment (Payment Method : Online Banking , Touch 'n Go)
4. **profile.php** → 用户中心（给user 看自己的order status 和 history 和 profile detail
- 1. Section Profile
- - 看自己的 username , Email , Adress 
- - change Pastword Button 就连去change-password.php
- 2. Section Order
- - 看自己的order status （如果没有order 就show “No Order Yet”
- - 看自己的order history
5. **profile-edit.php** → customer 可以edit 自己的 username 和 email 和address 和 phone
6. **change-password.php** → customer 可以change 自己的password

### 3. **ADMIN SIDE**
1. **dashboard.php**
- show Total users,product , order
- Example : 
- User 20 , Products 50 , Orders 100
- 同时提供button 进入 Manage User , Manage Product , Manage Order
2. **manage-user.php** → 查看所有用户
- show 全部user （user_id, username,email , password , role , address ， phone,address）
- delete user
3. **manage-user-add.php** → Add New User
- Username ,Email , Password ,Role
4. **manage-user-edit.php** → Edit User Detail
- Username , Email , Role (admin,user) 
5. **manage-user-changepwd.php** → Admin 帮user 重设密码
6. **manage-product.php** → 查看所有商品
- show all product (product_id,product_name,brand,price,stock,category,image)
- delete product
7. **manage-product-add.php** → Add New Product
- product_name,brand,price,stock,category,image,Description ,Washing Instruction , Size Guide , Size , Stock
8. **manage-product-edit.php** → Edit Product Detail
- product_name,brand,price,stock,category,image,Description ,Washing Instruction , Size Guide ,Size , Stock
9. **manage-order.php** → 查看和管理order
- show Order_id ,User , Date , Status , Total Price ,size 
- 可以看买了什么商品 ， 买多少个
- 可以Edit Status (Pending ,Processing ,Shipped , Completed)

### 4. **Footer**
1. **about.php**
2. **contact.php**
3. **privacyPolicy.php**
4. **terms-of-use.php**
5. **shipping-policy.php**

# Product
**每一个brand** (TntCo , Keynote ,Against Lab , Stone Co) at least 都**要有 8 个 Product**
Product 包括( Top , Buttom , Acessories)
1. **All** → sale all product 
2. **Top** → sale categories Tees , Hoodie , Jacket / Outwear (Double 7)
3. **Bottom** → sale 裤子
4. **Accesories** → sale categories keychain & charms 钥匙扣和挂饰 (Rf Double 7 , TntCo) , socks (Tntco ,Keynote) , Jewellery 珠宝 (double 7) 

## Product List
1. **Tees** 
- **Product 1** 

<img src="./image/productImage/photo_1_2026-06-03_14-25-41.jpg" style="aspect-ratio:1/1" alt="Product 1"  />

