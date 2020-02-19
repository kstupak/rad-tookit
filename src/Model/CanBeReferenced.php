<?php

/*
 * This file is part of "rad-tookit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KStupak\RAD;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait CanBeReferenced
{
    /** @var UuidInterface */
    private $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function init(): void
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString(): string
    {
        return $this->id->toString();
    }
}
