<?php

namespace Request;

class ReviewRequest
{
    public function __construct(private array $data){
    }

    public function getProductId(): int
    {
        return $this->data['product_id'];
    }

    public function getComment(): string
    {
        return $this->data['comment'];
    }
}