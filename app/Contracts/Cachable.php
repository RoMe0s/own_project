<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 10/24/16
 * Time: 10:44 PM
 */

namespace App\Contracts;

interface Cachable
{

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public static function getBaseQuery();


}