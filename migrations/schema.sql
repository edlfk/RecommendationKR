--schema.sql
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  age INT,
  gender VARCHAR(10),
  interests JSONB
);

CREATE TABLE content (
  id SERIAL PRIMARY KEY,
  title TEXT NOT NULL,
  description TEXT,
  tags JSONB,
  popularity INT DEFAULT 0
);

-- Индексы для быстрого поиска пересечений
CREATE INDEX idx_users_interests ON users USING GIN (interests);
CREATE INDEX idx_content_tags ON content USING GIN (tags);
