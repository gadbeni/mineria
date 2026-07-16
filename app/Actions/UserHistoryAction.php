<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class UserHistoryAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Historial';
    }

    public function getIcon()
    {
        return 'voyager-list';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-info pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        return route('users.history', $this->data->getKey());
    }

    /**
     * Mostrar solo en el BREAD de usuarios.
     */
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug === 'users';
    }
}
