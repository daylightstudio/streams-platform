<?php namespace Streams\Core\Stream\Model;

use Streams\Core\Model\EloquentModel;
use Streams\Core\Field\Model\FieldModel;
use Streams\Core\Stream\Presenter\StreamPresenter;
use Streams\Core\Stream\Schema\StreamSchema;
use Streams\Core\Assignment\Model\AssignmentModel;
use Streams\Core\Stream\Collection\StreamCollection;
use Streams\Core\Assignment\Collection\AssignmentCollection;

class StreamModel extends EloquentModel
{
    /**
     * Use timestamp and meta columns.
     */
    public $timestamps = false;

    /**
     * The model's database table name.
     *
     * @var string
     */
    protected $table = 'streams_streams';

    /**
     * Return all streams with given namespace.
     *
     * @param $namespace
     * @return mixed
     */
    public function findAllByNamespace($namespace)
    {
        return $this->whereNamespace($namespace)->get();
    }

    /**
     * Return the entry table name.
     *
     * @return string
     */
    public function entryTable()
    {
        return $this->prefix . $this->slug;
    }

    /**
     * Return the translatable table name.
     *
     * @return string
     */
    public function translatableTable()
    {
        return $this->entryTable() . '_translations';
    }

    public function object($data)
    {
        $assignments = array();

        if (isset($data['assignments'])) {
            foreach ($data['assignments'] as $assignment) {
                if (isset($assignment['field'])) {
                    $fieldModel = new FieldModel($assignment['field']);

                    unset($assignment['field']);

                    $assignmentModel = new AssignmentModel($assignment);

                    $assignmentModel->setRawAttributes($assignment);

                    $assignmentModel->setRelation('field', $fieldModel);

                    $assignments[] = $assignmentModel;
                }
            }
        }

        $streamModel = new StreamModel();

        $streamModel->setRawAttributes($data);

        $assignmentsCollection = new AssignmentCollection($assignments);

        $streamModel->setRelation('assignments', $assignmentsCollection);

        $streamModel->assignments = $assignmentsCollection;

        return $streamModel;
    }

    /**
     * Get the view options attribute.
     *
     * @param  string $viewOptions
     * @return array
     */
    public function getViewOptionsAttribute($viewOptions)
    {
        return json_decode($viewOptions);
    }

    /**
     * Set the view options attribute.
     *
     * @param array $viewOptions
     */
    public function setViewOptionsAttribute($viewOptions)
    {
        $this->attributes['view_options'] = json_encode($viewOptions);
    }

    /**
     * Get the permissions attribute.
     *
     * @param  string $permissions
     * @return array
     */
    public function getPermissionsAttribute($permissions)
    {
        return json_decode($permissions);
    }

    /**
     * Set the permissions attribute.
     *
     * @param array $permissions
     */
    public function setPermissionsAttribute($permissions)
    {
        $this->attributes['permissions'] = json_encode($permissions);
    }

    /**
     * Return a new collection instance.
     *
     * @param array $items
     * @return \Illuminate\Database\Eloquent\Collection|StreamCollection
     */
    public function newCollection(array $items = [])
    {
        return new StreamCollection($items);
    }

    /**
     * Return a new presenter instance.
     *
     * @param $resource
     * @return \Streams\Presenter\EloquentPresenter|StreamPresenter
     */
    public function newPresenter($resource)
    {
        return new StreamPresenter($resource);
    }

    /**
     * Return a new stream schema instance.
     *
     * @return StreamSchema
     */
    public function newSchema()
    {
        return new StreamSchema($this);
    }

    /**
     * Return the assignments relationship.
     *
     * @return object
     */
    public function assignments()
    {
        return $this->hasMany('Streams\Core\Assignment\Model\AssignmentModel', 'stream_id')->orderBy('sort_order');
    }
}
