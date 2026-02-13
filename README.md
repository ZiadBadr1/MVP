# 🧠 Backend Developer Technical Assessment
## People Management Platform

---

## 🛠 Technology Stack

- **Framework:** Laravel 11
- **Language:** PHP 8.2+
- **Database:** MySQL
- **Cache Store:** Redis
- **Authentication:** JWT
- **RBAC (Role-Based Access Control):** Spatie Laravel Permission
- **Architecture:** Service-Oriented Architecture (SOA)
- **Background Processing:** Jobs, Events, Listeners
- **Notifications:** Laravel Notifications (Simulated)
- **API Documentation:** Postman

---

## ⚙️ Architecture Decisions

### 1. RBAC Implementation

- **Tools Used:**
    - Spatie Laravel Permission
    - Middleware
    - Policies

- **Why Spatie?**
    - Mature and production-proven package
    - Database-driven roles & permissions
    - Easy extensibility
    - Optimized permission caching internally

**Explanation:**  
Spatie Laravel Permission is a powerful library for managing user roles and permissions. Using it reduces security bugs and makes permission management easy. Middleware and Policies enforce role-based access control throughout the application.

---

### 2. Caching Strategy

- **Cache store:** Redis (in-memory database for high performance)
- **Performance Improvement:**
    - Before caching: 7.09 ms
    - After caching: 0.55 ms

- **Why Cache Tags?**
    - Scoped invalidation (clear specific cache only)
    - Tenant-specific cache clearance
    - Avoid full cache flush

- **Why TTL (3600 seconds)?**
    - Prevent stale data
    - Reduce memory pressure
    - Safety net if invalidation fails

**Explanation:**  
Redis is used for fast in-memory caching. Cache tags allow clearing cache for a specific tenant without affecting others. TTL ensures that cached data doesn’t persist forever, acting as a fallback safety.

---

### 3. Multi-Tenancy

- **Approach:** Token-Based Tenant Resolution
- **How it works:**
    - `tenant_id` is embedded in the JWT token

- **Why this approach?**
    - No extra headers required
    - Secure (tenant ID signed inside JWT)
    - Prevents tenant spoofing
    - Works naturally with stateless APIs
    - Better performance (no extra tenant lookup)

- **Data Isolation:**
    - All tenant-related models include `tenant_id`
    - Tenant context resolved via `TenantService`
    - Tenant-based cache keys prevent cross-tenant data leakage

**Explanation:**  
Multi-tenancy allows the system to serve multiple clients while keeping their data isolated. Token-based tenant resolution is secure, fast, and stateless, embedding tenant information directly in the JWT.

---

### 4. Trade-offs

| Component      | Trade-off |
|----------------|-----------|
| **RBAC**       | Using Spatie increases dependency footprint but reduces security bugs and accelerates development |
| **Caching**    | Aggressive caching improves performance but requires careful invalidation strategy |
| **Multi-Tenancy** | Token-based tenant resolution is tightly coupled with JWT; simpler and faster but less flexible than header-based approach |

**Explanation:**  
Every architectural decision has pros and cons. Using libraries reduces development time but increases dependency. Aggressive caching boosts performance but needs careful management. JWT-based multi-tenancy improves security and speed but limits flexibility.

---

##  Database Diagram

  ![Database Diagram](public/schema.png)
- 
## Postman Collection
 [Open in Postman](https://lively-desert-628807.postman.co/workspace/Node-Js~b6630e98-43d0-4770-b159-4ccdd7c61d06/collection/29015347-4f0cf282-7c09-4400-9ed4-290a992e869b?action=share&creator=29015347)

## ⚙️ Project Setup & Run (Step by Step) ### 1️⃣ Clone the Repository
```bash

git clone https://github.com/ZiadBadr1/MVP.git
cd MVP

# Install dependencies
composer install
npm install && npm run dev

# Create .env file and configure your database
cp .env.example .env

# Generate app key
php artisan key:generate

# Run Migration
php artisan migrate

# Run Seeders
php artisan db:seed

# Start the development server
php artisan serve

# Start the Queue
php artisan queue:work


