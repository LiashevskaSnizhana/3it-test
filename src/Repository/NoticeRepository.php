<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class NoticeRepository extends EntityRepository
{
    public function findAllFilteredByDate(): array {
        return $this->findBy([], [
            'date' => 'DESC'
        ]);
    }
}