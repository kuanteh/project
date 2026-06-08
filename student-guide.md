# Project Planning Guide: PHP + MySQL Application

> **Read this guide before our planning session.** Your job is to come to me with a *draft* — not a blank page. Work through the thinking below, then fill in the template at the end. We'll refine it together.

---

## 1. What You're Building (in plain terms)

You're building a small web application backed by a database. Before you write a single line of PHP, you need to answer three questions:

1. **What "things" does my app keep track of?** → These become your **tables**.
2. **How are those things connected?** → These become your **relationships**.
3. **Who uses the app, and what is each person allowed to do?** → These become your **roles** and **permissions**.

If you can answer those three clearly, the code becomes much easier. Most students who struggle later struggle because they skipped this step.

---

## 2. The Requirements, Translated

Here's what the project asks for, and what it actually means in practice:

| Requirement | What it really means |
|---|---|
| HTML, CSS, PHP, MySQL (JS optional) | The core stack. You don't need JavaScript to pass. |
| At least **4 tables** | You track at least 4 distinct "things." |
| At least **3 relationships** between tables | At least 3 of those things are connected via foreign keys. |
| **User authentication** with **2+ roles** | People log in, and there's more than one *kind* of user. |
| **Role-based access** | Different roles can do different things (read vs. edit vs. delete). |
| **Data management** (add/edit/delete) with **2+ forms** | At least two forms write data into the database. |

---

## 3. Step One — Find Your Tables

A **table** stores one type of thing. Each row is one instance of that thing; each column is a fact about it.

A good way to find your tables: **write a sentence describing your app, then circle the nouns.**

> *"A **user** writes a **review** about a **movie**, and each movie belongs to a **genre**."*

Circled nouns → `users`, `reviews`, `movies`, `genres`. That's four tables already.

**Tips for choosing tables:**
- A table is usually a *plural noun*: `students`, `bookings`, `products`.
- If a "thing" has its own set of facts (a name, a date, a price...), it deserves its own table.
- Every table should have a **primary key** — a unique ID for each row (usually `id`).

---

## 4. Step Two — Connect Your Tables (Relationships)

A **relationship** means one table points to another using a **foreign key** — a column that stores the `id` of a row in another table.

The most common type you'll use is **"one-to-many"**, which you can spot using the phrase **"belongs to":**

> *Every **review** belongs to a **user**.* → the `reviews` table needs a `user_id` column.
> *Every **review** belongs to a **movie**.* → the `reviews` table needs a `movie_id` column.
> *Every **movie** belongs to a **genre**.* → the `movies` table needs a `genre_id` column.

That's **three relationships** — enough to meet the requirement.

**The rule of thumb:** the table on the "many / belongs-to" side holds the foreign key. A review belongs to a user, so `user_id` lives in `reviews` — *not* the other way around.

**Watch out for:**
- A relationship needs a real foreign-key column. "These two tables are *about* similar things" is **not** a relationship.
- Don't force relationships that don't make sense just to hit the number. Pick a topic rich enough that 3+ links happen naturally.

---

## 5. Worked Example — Event Booking System

Let's design one fully so you can see the whole process. (Your topic will differ — don't copy this one.)

**The sentence:** *"A user books a seat for an event, and each event is held at a venue."*

### Tables

| Table | Purpose | Key columns |
|---|---|---|
| `users` | People who log in | `id`, `name`, `email`, `password`, `role` |
| `venues` | Places events happen | `id`, `name`, `address`, `capacity` |
| `events` | Things people book | `id`, `title`, `date`, `venue_id` |
| `bookings` | A user reserving a spot at an event | `id`, `user_id`, `event_id`, `seats`, `created_at` |

### Relationships (3)

1. Every **event** belongs to a **venue** → `events.venue_id` → `venues.id`
2. Every **booking** belongs to a **user** → `bookings.user_id` → `users.id`
3. Every **booking** belongs to an **event** → `bookings.event_id` → `events.id`

### Roles & permissions

| Feature | Member | Admin |
|---|---|---|
| View events | ✅ | ✅ |
| Make a booking | ✅ | ✅ |
| Add / edit events | ❌ | ✅ |
| Delete events / bookings | ❌ | ✅ |
| Manage venues | ❌ | ✅ |

### Pages & which role can reach them

| Page | What it does | Who can access |
|---|---|---|
| `login.php` / `register.php` | Authentication | Everyone |
| `events.php` | List all events | Member, Admin |
| `book.php` | **Form** → creates a booking | Member, Admin |
| `my-bookings.php` | View / cancel own bookings | Member, Admin |
| `admin/event-form.php` | **Form** → add/edit an event | Admin only |
| `admin/manage.php` | Edit/delete events & venues | Admin only |

Notice this design hits **every** requirement: 4 tables, 3 relationships, 2 roles, role-based access, and 2+ forms (`book.php` and `admin/event-form.php`).

---

## 6. Step Three — Map Schema to App Structure

Once your tables and roles are set, your *pages* almost write themselves. For each table that users manage, you typically need:

- A **list page** (read) — show the rows.
- A **form page** (create/edit) — at least two of these across your whole app.
- A **delete action** — usually a button, restricted by role.

Then ask, for **every** page: *"Which roles are allowed here?"* That single question is your entire role-based access system. In PHP you enforce it by checking the logged-in user's role at the top of each protected page.

---

## 7. Your Turn — Fill This In Before Our Session

Copy the template below and complete it as best you can. Gaps are fine — that's what our session is for.


### A. My topic
> One sentence describing your app:
>
> ` A Malaysia local fashion brand clothing store`

### B. My tables (aim for 4+)

| Table name | What it stores | Columns (incl. primary key) |
|---|---|---|
| users | users data | user_id (PK), username, email, password, role |
|products | clothes data |product_id (PK), product_name, brand, price, stock ，category, image|
|orders | |order_id (PK), user_id(FK), purchase_date, total_price ,status|
|order_Products |  | order_products_id（PK）, order_id(FK)， product_id(FK) ,quantity| 

### C. My relationships (aim for 3+)

Write each as a "belongs to" sentence, then name the foreign key.

| # | Relationship sentence | Foreign key column | Points to |
|---|---|---|---|
| 1 | Every order belongs to a user | `order.user_id` | `user.user_id` |
| 2 | Every order_product belongs to a order | `order_product.order_id` | `order.order_id` |
| 3 | Every order_product has a product | `order_product.product_id` | `product.product_id` |

### D. My roles & permissions (2+ roles)

| Feature | Customer | Admin |
|---|---|---|
| View the product | ✅ | ✅ |
| order the product |✅ |✅ |
| manage product(EDIT,DELETE,ADD)|❌ |✅ |
| manage orders |✅ |✅|

### E. My pages & access

| Page | What it does | Is it a form? | Who can access |
|---|---|---|---|
| register.php/login.php| Authentication | YES | Everyone|
| | | | |
| | | | |
| | | | |

---

## 8. Quick Self-Check Before You Submit

- [ ] I have **4 or more** tables.
- [ ] I have **3 or more** relationships, each with a real foreign key.
- [ ] I have **2 or more** roles.
- [ ] Different roles can do **different things** (not everyone can delete).
- [ ] I have **at least 2 forms** that write data to the database.
- [ ] Every page has an answer to "who is allowed here?"

If all six are checked, you're in great shape. Bring this to our session and we'll tighten it up.

---
---
---
---
---

