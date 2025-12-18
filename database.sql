DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS plan_members;
DROP TABLE IF EXISTS financial_plans;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE financial_plans (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_by INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE plan_members (
    plan_id INT REFERENCES financial_plans(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    role VARCHAR(20) DEFAULT 'member', -- NUEVA COLUMNA
    PRIMARY KEY (plan_id, user_id)
);

CREATE TABLE expenses (
    id SERIAL PRIMARY KEY,
    plan_id INT REFERENCES financial_plans(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id),
    title VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuario admin por defecto
INSERT INTO users (username, email, password) VALUES ('admin', 'admin@test.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm');