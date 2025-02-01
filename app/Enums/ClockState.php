<?php

namespace App\Enums;

enum ClockState: string {
    case WORK = 'WORK';
    case FREE_TIME = 'FREE_TIME';
    case ENDED = 'ENDED';

    public function getNextState(): ClockState {
        return match ($this) {
            self::WORK =>self::FREE_TIME,
            self::FREE_TIME, self::ENDED => self::ENDED,
        };
    }
}
