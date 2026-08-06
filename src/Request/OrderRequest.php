<?php

namespace Request;

class OrderRequest
{
    public function __construct(private array $data)
    {
    }

    public function getContactName(): string
    {
        return $this->data['contact_name'];
    }
    public function getContactPhone(): string
    {
        return $this->data['contact_phone'];
    }
    public function getComment(): string
    {
        return $this->data['comment'];
    }
    public function getAddress(): string
    {
        return $this->data['address'];
    }

    public function validate()
    {
        $errors = [];

        if (isset($this->data['contact_name'])) {
            $name = $this->data['contact_name'];

            if (empty($name)) {

                $errors['contact_name'] = "Имя должно быть заполнено";
            }
        }

        if (isset($this->data['contact_phone'])) {
            $contactPhone = $this->data['contact_phone'];


            if (!preg_match('/^[0-9]{11}$/', $contactPhone)) {
                $errors['contact_phone'] = 'номер телефона должен содержать только цифры и быть длиной больше 10 символов';
            }
        }

        if (isset($this->data['address'])) {
            $address = $this->data['address'];

            if (empty($address)) {
                $errors['address'] = 'Адрес должен быть заполнен';
            }

            if (strlen($address) < 5) {
                $errors['address'] = 'Слишком короткий адрес';
            }

        }

        return $errors;
    }


}