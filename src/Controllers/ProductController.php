<?php

namespace Controllers;

use Model\Product;
use Model\Review;
use Model\UserProducts;
use Request\AddProductRequest;
use Request\ReviewRequest;
use Service\CartService;
class ProductController extends BaseController
{
    private Product $productModel;
    private UserProducts $userProductModel;

    private CartService $cartService;

    private Review $reviewModel;
    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->userProductModel = new UserProducts();
        $this->reviewModel = new Review();
        $this->cartService = new CartService($this->userProductModel , $this->productModel);
    }

    public function catalog()
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $products = $this->productModel->getProducts();

        $user = $this->authService->getCurrentUser();

        $userProducts = $this->userProductModel->getAllProductsByUserId($user->getId());

        $cartItems = [];
        foreach ($userProducts as $userProduct) {

            $cartItems[$userProduct->getProductId()] = $userProduct->getAmount();
        }

        require_once '../Views/catalog.php';
    }

    public function increaseAmount(AddProductRequest $request)
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId = $request->getProductId();

        $this->cartService->increaseAmount($productId, $user->getId());

        header("Location: /catalog");
        exit();
    }

    public function decreaseAmount(AddProductRequest $request)
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId = $request->getProductId();

        $this->cartService->decreaseAmount($productId, $user->getId());
        header("Location: /catalog");
        exit();
    }

    public function getReviews(): void
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        // БАГ 1: product_id из $_GET передавался в модель без каста к int — вместе со
        // старой SQL-инъекцией в Product::getOneById() (уже исправлено там) это делало
        // /reviews?product_id=... точкой атаки. Кастуем к int здесь тоже, для защиты
        // "в глубину" — даже если модель когда-нибудь снова случайно склеит SQL руками.
        $productId = (int) ($_GET['product_id'] ?? 0);

        // БАГ 2: getOneById() вызывался дважды подряд с одним и тем же id — лишний
        // запрос к БД без всякой цели, второй результат просто перезаписывал первый.
        $product = $this->productModel->getOneById($productId);

        // БАГ 3: если товар с таким id не найден, $product был null, но вьюха
        // reviews_form.php всё равно рендерилась и сразу падала на
        // $product->getImageUrl() (вызов метода на null). Теперь при отсутствии товара
        // просто уходим в каталог вместо фатальной ошибки.
        if ($product === null) {
            header("Location: /catalog");
            exit();
        }

        $reviews = $this->reviewModel->getAllByProductId($productId);

        require_once "../Views/reviews_form.php";
    }

    public function addReview(ReviewRequest $request): void
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $productId = $request->getProductId();
        $comment = $request->getComment();
        $user = $this->authService->getCurrentUser();


        $userId = $user->getId();
        $result = $this->reviewModel->create($productId, $userId, $comment);

        header("Location: /reviews?product_id=" . $productId );
        exit();

    }
}












