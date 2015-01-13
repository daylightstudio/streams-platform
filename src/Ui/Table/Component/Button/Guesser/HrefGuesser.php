<?php namespace Anomaly\Streams\Platform\Ui\Table\Component\Button\Guesser;

use Illuminate\Http\Request;

/**
 * Class HrefGuesser
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Ui\Table\Component\Button\Guesser
 */
class HrefGuesser
{

    /**
     * The request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * Create a new HrefGuesser instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Guess the HREF for a button.
     *
     * @param array $button
     */
    public function guess(array &$button)
    {
        // If we already have an HREF then skip it.
        if (isset($button['attributes']['href'])) {
            return;
        }

        // Determine the HREF based on the button type.
        switch (array_get($button, 'button')) {

            case 'edit':
                $button['attributes']['href'] = $this->guessEditHref();
                break;

            case 'delete':
                $button['attributes']['href'] = $this->guessDeleteHref();
                break;
        }
    }

    /**
     * Guess the edit URL.
     *
     * Since this is for tables we can assume the
     * last segment is index so we can simply append
     * the action and the ID.
     *
     * @return string
     */
    protected function guessEditHref()
    {
        $segments = $this->request->segments();

        return '/' . implode('/', $segments) . '/edit/{{ entry.id }}';
    }

    /**
     * Guess the delete URL.
     *
     * Since this is for tables we can assume the
     * last segment is index so we can simply append
     * the action and the ID.
     *
     * @return string
     */
    protected function guessDeleteHref()
    {
        $segments = $this->request->segments();

        return '/' . implode('/', $segments) . '/delete/{{ entry.id }}';
    }
}
