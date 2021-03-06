<?php

/*
 * This file is part of YaEtl.
 *     (c) Fabrice de Stefanis / https://github.com/fab2s/YaEtl
 * This source file is licensed under the MIT license which you will
 * find in the LICENSE file or at https://opensource.org/licenses/MIT
 */

namespace fab2s\YaEtl\Transformers;

/**
 * Class NoOpTransformer
 */
class NoOpTransformer extends TransformerAbstract
{
    /**
     * @param mixed $record
     *
     * @return mixed
     */
    public function exec($record)
    {
        return $record;
    }
}
