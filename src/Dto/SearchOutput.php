<?php

namespace App\Dto;

final class SearchOutput
{
    public function __construct(
        public array $meta,
        public array $data,
    ) {
    }
}
