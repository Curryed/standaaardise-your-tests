<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\UseCase;

enum BookResult
{
    case AlreadyBooked;
    case Booked;
    case NotAnAdult;
}
