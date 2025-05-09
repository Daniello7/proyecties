<?php

namespace App\Traits;

trait PackageTableConfig
{
    private function configurePackageAllColumns(): void
    {
        $this->columns = ['Type', 'Agency', 'Sender', 'Destination', 'Entry', 'Receiver', 'Exit', 'Deliver', 'Package Count', 'Retired By', 'Comment', 'Actions'];
        $this->select = ['packages.*'];
        $this->columnMap = [
            'Type' => 'type',
            'Agency' => 'agency',
            'Receiver' => 'receiver.name',
            'Deliver' => 'deliver.name',
            'Entry' => 'entry_time',
            'Exit' => 'exit_time',
            'Package Count' => 'package_count',
            'Retired By' => 'retired_by',
            'Sender' => null,
            'Destination' => null,
            'Comment' => null,
            'Actions' => null
        ];
        $this->relations = [
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
            'receiver:id,name',
            'deliver:id,name',
        ];
        $this->sortColumn = 'exit_time';
        $this->sortDirection = 'asc';
    }

    public function configurePackageHomeView(): void
    {
        $this->columns = ['Type', 'Agency', 'Sender', 'Destination', 'Entry', 'Comment', 'Actions'];
        $this->select = [
            'packages.id',
            'packages.type',
            'packages.agency',
            'packages.external_entity',
            'packages.internal_person_id',
            'packages.entry_time',
            'packages.comment',
        ];
        $this->columnMap = [
            'Type' => 'type',
            'Agency' => 'agency',
            'Entry' => 'entry_time',
            'Sender' => null,
            'Destination' => null,
            'Comment' => null,
            'Actions' => null
        ];
        $this->relations = [
            'internalPerson:id,person_id',
            'internalPerson.person:id,name,last_name',
        ];
        $this->sortColumn = 'packages.type';
        $this->sortDirection = 'asc';
    }
}
