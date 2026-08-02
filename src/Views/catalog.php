<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог товаров</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f5f5;
            padding: 20px;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Шапка */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .header h3 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }

        .header-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-links a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 15px;
        }

        .header-links a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .header-links .cart-link {
            position: relative;
        }

        .cart-badge {
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }

        /* Сетка товаров */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        /* Карточка товара */
        .product-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .product-image-placeholder {
            width: 100%;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            color: #999;
            font-size: 16px;
        }

        .product-body {
            padding: 20px;
            flex: 1;
        }

        .product-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 50px;
        }

        .product-description {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 63px;
            margin-bottom: 10px;
        }

        /* Футер карточки */
        .product-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background: #fafafa;
        }

        .footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-price {
            font-size: 20px;
            font-weight: 700;
            color: #e74c3c;
            white-space: nowrap;
        }

        /* Кнопки + и - */
        .btn-qty {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            text-decoration: none;
        }

        .btn-qty:hover {
            transform: scale(1.1);
        }

        .btn-qty:active {
            transform: scale(0.95);
        }

        .btn-minus {
            background: #fee;
            color: #e74c3c;
        }

        .btn-minus:hover {
            background: #fdd;
        }

        .btn-plus {
            background: #efe;
            color: #27ae60;
        }

        .btn-plus:hover {
            background: #dfd;
        }

        .amount-display {
            font-size: 18px;
            font-weight: 700;
            min-width: 30px;
            text-align: center;
            color: #2c3e50;
            user-select: none;
        }

        /* Кнопка Подробнее */
        .btn-detail {
            background: #2ecc71;
            color: white;
            padding: 6px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-detail:hover {
            background: #27ae60;
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Сообщения */
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        /* Адаптив */
        @media (max-width: 768px) {
            body { padding: 10px; }

            .header {
                flex-direction: column;
                gap: 12px;
                padding: 15px;
            }

            .header-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }

            .product-image {
                height: 180px;
            }

            .product-image-placeholder {
                height: 180px;
            }

            .footer-row {
                flex-wrap: wrap;
                justify-content: center;
            }

            .product-price {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Шапка -->
    <div class="header">
        <h3>🛍️ Каталог товаров</h3>
        <div class="header-links">
            <a href="/profile">👤 Мой профиль</a>
            <a href="/cart" class="cart-link">
                <a href="/cart">🛒 Корзина</a>
                <a href="/user-orders">Заказы</a>


            <?php if (isset($_SESSION['userId'])): ?>
                <a href="/login">🚪 Выйти</a>
            <?php else: ?>
                <a href="/login">🔐 Войти</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Сообщения -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-error">❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success">✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <!-- Сетка товаров -->
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">

                <!-- Изображение -->
                <?php if ($product->getImageUrl()): ?>
                    <img class="product-image" src="<?php echo htmlspecialchars($product->getImageUrl()); ?>"
                         alt="<?php echo htmlspecialchars($product->getName()); ?>">
                <?php else: ?>
                    <div class="product-image-placeholder">🖼️ Нет фото</div>
                <?php endif; ?>

                <!-- Тело карточки -->
                <div class="product-body">
                    <h5 class="product-title"><?php echo htmlspecialchars($product->getName()); ?></h5>
                    <p class="product-description">
                        <?php echo htmlspecialchars(substr($product->getDescription(), 0, 120)); ?>
                        <?php if (strlen($product->getDescription()) > 120): ?>...<?php endif; ?>
                    </p>
                </div>

                <!-- Футер с кнопками -->
                <div class="product-footer">
                    <div class="footer-row">
                        <div class="footer-left">
                            <!-- Кнопка − (с перезагрузкой) -->
                            <form action="/decrease-amount" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                <button type="submit" class="btn-qty btn-minus">−</button>
                            </form>

                            <!-- Количество -->
                            <span class="amount-display">
                                    <?php
                                    $amount = 0;
                                    if (isset($cartItems) && isset($cartItems[$product->getId()])) {
                                        $amount = $cartItems[$product->getId()];
                                    }
                                    echo $amount;
                                    ?>
                                </span>

                            <!-- Кнопка + (с перезагрузкой) -->
                            <form action="/increase-amount" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                <button type="submit" class="btn-qty btn-plus">+</button>
                            </form>
                        </div>

                        <!-- Цена -->
                        <span class="product-price">
                                <?php echo number_format($product->getPrice(), 0, '.', ' '); ?> ₽
                            </span>

                        <!-- Кнопка Подробнее -->
                        <a href="/reviews?product_id=<?php echo $product->getId(); ?>" class="btn-detail">
                            Подробнее →
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>