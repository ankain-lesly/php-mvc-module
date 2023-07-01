<?php

namespace app\models;

use app\database\DBModel;

class Post extends DBModel
{
    public int $id = 0;
    public string $title = '';
    public string $category = '';
    public string $body = '';

    public static function tableName(): string
    {
        return 'posts';
    }

    public function attributes(): array
    {
        return ['title', 'category', 'body'];
    }

    public function rules()
    {
        return [
            'title' => [self::RULE_REQUIRED],
            'category' => [self::RULE_REQUIRED],
            'body' => [self::RULE_REQUIRED],
        ];
    }

    public function create(array $form_data)
    {
        // return parent::save($form_data);
        return $this->save($form_data);
    }

    // public function getDisplayName(): string
    // {
    //     return $this->firstname . ' ' . $this->lastname;
    // }
}
