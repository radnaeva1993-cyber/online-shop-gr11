<div class="container">
    <a href="/profile">Мой профиль</a>
    <a href="/catalog">Каталог</a>
    <a href="/create-order">Оформить заказа</a>
    <h3>Корзина</h3>
    <div class="container">
        <div class="card-deck">
            <?php foreach ($products as $product): ?>
                <div class="card text-center" style="width: 18rem; margin: 10px;">
                    <div class="card-header">
                        <!-- Название товара -->
                        <h5 class="card-title"><?php echo $product->getName(); ?></h5>
                    </div>

                    <!-- Картинка -->
                    <img class="card-img-top" src="<?php echo $product->getImageUrl(); ?>" alt="Image" style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <!-- Описание -->
                        <p class="card-text text-muted"><?php echo $product->getDescription(); ?></p>

                        <!-- Цена -->
                        <p class="card-text"><strong>Цена:</strong> <?php echo $product->getPrice(); ?> ₽</p>

                        <!-- Блок управления количеством -->
                        <div class="controls-wrapper" style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-top: 10px;">

                            <!-- Кнопка минус -->
                            <form action="/decreaseAmount" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">-</button>
                            </form>

                            <!-- Цифра количества -->
                            <span class="badge badge-info" style="font-size: 18px; padding: 5px 15px;">
                            <?php echo $product->amount; ?>
                        </span>

                            <!-- Кнопка плюс -->
                            <form action="/increaseAmount" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                <button type="submit" class="btn btn-success btn-sm">+</button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- БЛОК ИТОГОВОЙ СУММЫ -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-12 text-right">
                <h4>
                    Итоговая сумма:
                    <span class="badge badge-success" style="font-size: 24px; padding: 10px;">
                                <?php echo $totalPrice; ?> ₽
                            </span>
                </h4>
            </div>
        </div>
    </div>


</form>


    </div>
</div>



<style>
    body {
        font-style: sans-serif;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }

    h3 {
        line-height: 3em;
    }

    .card {
        max-width: 16rem;
    }

    .card:hover {
        box-shadow: 1px 2px 10px lightgray;
        transition: 0.2s;
    }

    .card-header {
        font-size: 13px;
        color: gray;
        background-color: white;
    }

    .text-muted {
        font-size: 11px;
    }

    .card-footer{
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }
</style>



