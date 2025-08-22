<?php

namespace Tomrummet\Marker\Model;

enum MarkerTypeEnum
{
    case PAGE;
    case POST;

    public function folder(): string
    {
        return match($this)
        {
            MarkerTypeEnum::PAGE => 'pages',
            MarkerTypeEnum::POST => 'posts',
        };
    }
}
