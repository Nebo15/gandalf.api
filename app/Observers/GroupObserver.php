<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

use \App\Models\Group;
use Nebo15\Changelog\Changelog;
use Nebo15\LumenApplicationable\ApplicationableHelper;

class GroupObserver
{
    public function creating(Group $Group)
    {
    }

    public function created(Group $Group)
    {
    }

    public function updating(Group $Group)
    {
    }

    public function updated(Group $Group)
    {
    }

    public function saving(Group $Group)
    {
        ApplicationableHelper::addApplication($Group);
    }

    public function saved(Group $Group)
    {
        /** get user name */
        Changelog::createFromModel($Group, 'admin');
    }

    public function deleting(Group $Group)
    {
    }

    public function deleted(Group $Group)
    {
    }

    public function restoring(Group $Group)
    {
    }

    public function restored(Group $Group)
    {
    }
}
