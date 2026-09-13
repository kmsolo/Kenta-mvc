<?php

declare(strict_types=1);

namespace App\Enum;

enum GameStatus: string
{
    case PLAYER_TURN = 'player_turn';
    case FINISHED = 'finished';
}
