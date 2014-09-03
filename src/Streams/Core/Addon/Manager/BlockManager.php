<?php namespace Streams\Core\Addon\Manager;

use Streams\Core\Addon\Model\BlockModel;
use Streams\Core\Addon\Collection\BlockCollection;

class BlockManager extends AddonManager
{
    /**
     * The folder within addons locations to load blocks from.
     *
     * @var string
     */
    protected $folder = 'blocks';

    /**
     * Return a new model instance.
     *
     * @return mixed
     */
    protected function newModel()
    {
        return new BlockModel();
    }

    /**
     * Return a new collection instance.
     *
     * @param array $blocks
     * @return null|BlockCollection
     */
    protected function newCollection(array $blocks = [])
    {
        return new BlockCollection($blocks);
    }
}
