<!DOCTYPE html>
<html lang="ru">
<head>
    <style>
        /* Сброс стандартных отступов */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Стили для навигации */
        .nav {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .nav-link {
            display: inline-block;
            padding: 10px 25px;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: #f0f2f5;
        }

        .nav-link:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .nav-link:active {
            transform: translateY(0);
        }

        /* Иконки для кнопок (опционально) */
        .nav-link.profile::before {
            content: "👤 ";
        }

        .nav-link.catalog::before {
            content: "📦 ";
        }

        /* Стили для товара */
        .product-wrapper {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .product-wrapper img {
            max-width: 100%;
            border-radius: 8px;
        }

        .product-wrapper p {
            color: #e74c3c;
            font-size: 18px;
            font-weight: 500;
            margin-top: 15px;
        }

        .reviews-section {
            margin-bottom: 40px;
        }
        .review-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 4px solid #6c757d;
        }
        .review-user {
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }
        .review-text {
            margin: 5px 0 0 0;
            font-size: 15px;
            line-height: 1.5;
        }
        .no-reviews {
            color: #777;
            font-style: italic;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .form-section textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            min-height: 80px;
            resize: vertical;
        }
        .btn-submit {
            background-color: #6c757d;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background-color: #5a6268;
        }
        @media (max-width: 600px) {
            .product-wrapper {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Навигация -->
    <nav class="nav">
    <a href="/profile">👤 Мой профиль</a>
    <a href="/catalog">Каталог</a>
    </nav>


    <!-- 1. Блок с товаром -->
    <div class="product-wrapper">
        <img src="<?php echo htmlspecialchars($product->getImageUrl() ?: '/img/no-image.png'); ?>" alt="Фото товара">
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product->getName()); ?></h1>
            <p class="price"><?php echo number_format($product->getPrice(), 0, '.', ' '); ?> ₽</p>
            <p style="line-height: 1.6; color: #555;">
                <?php echo nl2br(htmlspecialchars($product->getDescription())); ?>
            </p>
        </div>
    </div>

    <!-- 2. Блок с отзывами -->
    <div class="reviews-section">
        <h3 style="border-bottom: 2px solid #eee; padding-bottom: 10px;">Отзывы о товаре</h3>

        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-user">Пользователь ID: <?php echo htmlspecialchars($review->getUserId()); ?></div>
                    <div class="review-text"><?php echo nl2br(htmlspecialchars($review->getComment())); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-reviews">У этого товара пока нет отзывов. Будьте первым!</p>
        <?php endif; ?>
    </div>

    <!-- 3. Блок с формой -->
    <div class="form-section">
        <h3 style="margin-top: 0;">Оставить отзыв</h3>

        <form action="/reviews" method="POST">

            <!-- Скрытое поле для ID товара (Без него addReview не поймет, куда писать) -->
            <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">

            <div style="margin-bottom: 5px;">
                <textarea name="comment" placeholder="Напишите ваш отзыв..." required></textarea>
            </div>

            <button type="submit" class="btn-submit">Отправить отзыв</button>
        </form>
    </div>

</div>

</body>
</html>