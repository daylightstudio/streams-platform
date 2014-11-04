<?php namespace Anomaly\Streams\Platform\Ui\Form\Command;

use Anomaly\Streams\Platform\Ui\Form\Form;

/**
 * Class BuildFormSectionsCommand
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Form\Command
 */
class BuildFormSectionsCommand
{

    /**
     * The form UI object.
     *
     * @var \Anomaly\Streams\Platform\Ui\Form\Form
     */
    protected $ui;

    /**
     * Create a new BuildFormSectionsCommand instance.
     *
     * @param Form $ui
     */
    function __construct(Form $ui)
    {
        $this->ui = $ui;
    }

    /**
     * Get the form UI object.
     *
     * @return Form
     */
    public function getUi()
    {
        return $this->ui;
    }
}
 