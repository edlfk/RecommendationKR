-- Пользователи
INSERT INTO users (id, age, gender, interests) VALUES
(1, 25, 'мужской', '["IT","игры","кино"]'::jsonb)
ON CONFLICT (id) DO UPDATE SET age=EXCLUDED.age, gender=EXCLUDED.gender, interests=EXCLUDED.interests;

INSERT INTO users (id, age, gender, interests) VALUES
(2, 34, 'женский', '["кулинария","путешествия","кино"]'::jsonb)
ON CONFLICT (id) DO UPDATE SET age=EXCLUDED.age, gender=EXCLUDED.gender, interests=EXCLUDED.interests;

INSERT INTO users (id, age, gender, interests) VALUES
(3, 28, 'мужской', '["спорт","игры","кино"]'::jsonb)
ON CONFLICT (id) DO UPDATE SET age=EXCLUDED.age, gender=EXCLUDED.gender, interests=EXCLUDED.interests;

INSERT INTO users (id, age, gender, interests) VALUES
(4, 40, 'женский', '["музыка","путешествия","кулинария"]'::jsonb)
ON CONFLICT (id) DO UPDATE SET age=EXCLUDED.age, gender=EXCLUDED.gender, interests=EXCLUDED.interests;

INSERT INTO users (id, age, gender, interests) VALUES
(5, 22, 'мужской', '["кино","игры","технологии"]'::jsonb)
ON CONFLICT (id) DO UPDATE SET age=EXCLUDED.age, gender=EXCLUDED.gender, interests=EXCLUDED.interests;

-- Контент
INSERT INTO content (id, title, description, tags, popularity) VALUES
(1, 'Топ-10 языков программирования 2025', 'Описание статьи о популярных языках программирования.', '["IT","программирование"]'::jsonb, 150),
(2, 'Простые рецепты дома', 'Лёгкие рецепты для приготовления дома.', '["кулинария","дом"]'::jsonb, 90),
(3, 'Лучшие места для путешествий', 'Интересные направления для отдыха и путешествий.', '["путешествия","приключения"]'::jsonb, 70),
(4, 'Блокбастеры 2024 года', 'Обзор популярных фильмов.', '["кино","развлечения"]'::jsonb, 200),
(5, 'Обзор игр Q4', 'Новинки игровой индустрии.', '["игры","обзоры"]'::jsonb, 120),
(6, 'Музыкальные хиты 2024', 'Топовые песни и альбомы.', '["музыка","развлечения"]'::jsonb, 80),
(7, 'Технологические новинки', 'Последние новости из мира технологий.', '["IT","технологии"]'::jsonb, 110),
(8, 'Путеводитель по Европе', 'Лучшие маршруты для путешествий по Европе.', '["путешествия","путешествия"]'::jsonb, 95)
ON CONFLICT (id) DO NOTHING;
