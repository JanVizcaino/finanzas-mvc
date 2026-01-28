DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS plan_members;
DROP TABLE IF EXISTS financial_plans;
DROP TABLE IF EXISTS users;

-- TABLA USERS
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    currency VARCHAR(3) DEFAULT 'EUR', 
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user'
);

-- TABLA FINANCIAL_PLANS
CREATE TABLE financial_plans (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_by INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    detail text,
    currency VARCHAR(3)
);

-- TABLA PLAN_MEMBERS
CREATE TABLE plan_members (
    plan_id INT REFERENCES financial_plans(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    role VARCHAR(20) DEFAULT 'member',
    PRIMARY KEY (plan_id, user_id)
);

-- TABLA EXPENSES
CREATE TABLE expenses (
    id SERIAL PRIMARY KEY,
    plan_id INT REFERENCES financial_plans(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id),
    title VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    detail text,
    receipt_path VARCHAR(255) -- <--- ¡ESTA ES LA COLUMNA QUE FALTABA!
);

-- DATOS INICIALES
INSERT INTO users (username, email, password, role)
VALUES (
  'jan',
  'jan@gmail.com',
  '$2y$10$wL.F.1i.O.1i.O.1i.O.1ieO8y1.1i.O.1i.O.1i.O.1i.O.1i.O', 
  'admin'
);