<?php
//
abstract class Savable
{
    //keeps track is its a new entity
    private $new = false;
    //keeps track if the entity has changes that need to be persisted
    private $changed = false;

    //protected, so that only the subclasses know about these functions to implement them
    abstract protected function insertIntoDB();
    abstract protected function updateInDB();

    // only public function , what is used to persist the entity into the DB
    public function save()
    {
        if ($this->new) {
            //is new , insert into the db
            $this->insertIntoDB();
        } elseif ($this->changed) {
            //has changes , update in the db
            $this->updateInDB();
        }

        //finished saving, set variables so future saves dont call the DB
        $this->changed = false;
        $this->new = false;
    }

    //to be used if an entity is created that has never been inserted into the db
    protected function setNewTrue()
    {
        $this->new = true;
    }

    //to be used inside any setter of an entity that changed a value of a variable
    protected function setChangedTrue()
    {
        $this->changed = true;
    }
}
