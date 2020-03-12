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

namespace KStupak\RAD\Repository;

use Doctrine\Common\Collections\Collection;
use KStupak\RAD\Model\Search;

interface FilterableRepository
{
    public function filter(Search $search): Collection;
}
