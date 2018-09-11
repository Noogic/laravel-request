<?php

namespace Noogic\LaravelRequest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property-read array $data
 */
abstract class ApplicationRequest
{
    /** @var array */
    protected $data = null;

    /** @var Request */
    protected $request;

    protected $user;
    protected $associate_to_user = false;

    protected $related = null;

    /** @var ApplicationRequestPluginContainer */
    protected $plugins;
    protected $use = [];

    public function __construct(Request $request, ApplicationRequestPluginContainer $plugins)
    {
        $this->request = $request;
        $this->plugins = $plugins;

        $this->data();
    }

    public function data()
    {
        if ($this->data) {
            return $this->data;
        }

        $this->preLoadData();
        $this->loadData();

        $this->handleAssociatedUser();
        $this->handleRelatedModel();
        $this->handlePlugins();

        $this->postLoadData();

        return $this->data;
    }

    public function all()
    {
        return $this->data();
    }

    public function __get($name)
    {
        if ($name == 'data') {
            return $this->data();
        }

        return $this->$name ?? array_get($this->data, $name);
    }

    /**
     * @return User
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        if (!is_a($this->request, Request::class)) {
            return null;
        }

        return $this->request->user();
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    protected abstract function rules();

    protected function preLoadData(){}
    protected function postLoadData(){}

    protected function loadData()
    {
        $this->data = $this->request->validate($this->rules());
    }

    protected function handleAssociatedUser()
    {
        if ($this->associate_to_user === true) {
            $this->data['user_id'] = $this->user()->id;
        }
    }

    protected function handleRelatedModel()
    {
        if ($this->related) {
            $model = $this->request->{$this->related};
            $id = is_a($model, Model::class) ? $model->id : $model;

            $this->data[$this->related . '_id'] = $id;
        }
    }

    protected function handlePlugins()
    {
        foreach ($this->use as $key) {
            $plugin = $this->plugins->get($key);
            $data = $plugin::boot()->run($this->data, $this->user(), $this->request);
            $this->data = array_merge($this->data, $data);
        }
    }
}
