<?php

namespace DTO;

class OrderCreateDTO
{


    // БАГ: OrderController::handleCheckout() всегда вызывал этот конструктор с 5-м
    // аргументом $user->getId(), а тут было объявлено только 4 параметра. PHP не выдаёт
    // ошибку на лишние аргументы (в отличие от нехватки обязательных) — он их просто
    // молча отбрасывает. В итоге userId никуда не попадал, а OrderService приходилось
    // заново лезть в authService за текущим пользователем, хотя DTO для того и нужен,
    // чтобы нести все данные заказа одним объектом. Добавили $userId как полноценное
    // поле DTO с геттером.
    public function __construct(private string $contactName,
                                private string $contactPhone,
                                private string $comment,
                                private string $address,
                                private int $userId

    ){
    }

    public function getContactName(): string
    {
        return $this->contactName;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
    public function getAddress(): string
    {
        return $this->address;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }



}