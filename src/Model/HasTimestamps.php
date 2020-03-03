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

namespace KStupak\RAD\Model;

trait HasTimestamps
{
    private \DateTimeImmutable $created;
    private \DateTimeImmutable $updated;

    public function stamp(): void
    {
        if (empty($this->created)) {
            $this->created = new \DateTimeImmutable();
        }

        $this->updated = new \DateTimeImmutable();
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): \DateTimeImmutable
    {
        return $this->updated;
    }
}
