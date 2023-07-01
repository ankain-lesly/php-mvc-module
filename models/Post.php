<?php

namespace app\models;

use app\database\DbModel;

class Post extends DbModel
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

    public function save()
    {
        // $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    // public function getDisplayName(): string
    // {
    //     return $this->firstname . ' ' . $this->lastname;
    // }
}
