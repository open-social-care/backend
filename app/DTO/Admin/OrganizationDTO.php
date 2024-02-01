<?php

namespace App\DTO\Admin;

class OrganizationDTO
{
    public string $name;

    public string $phone;

    public string $documentType;

    public string $document;

    public string $subjectRef;

    /**
     * Construct class set DTO attributes
     */
    public function __construct(array $data)
    {
        $this->name = data_get($data, 'name');
        $this->phone = data_get($data, 'phone');
        $this->documentType = data_get($data, 'document_type');
        $this->document = data_get($data, 'document');
        $this->subjectRef = data_get($data, 'subject_ref');
    }

    /**
     * Returns array of DTO attributes
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'document_type' => $this->documentType,
            'document' => $this->document,
            'subject_ref' => $this->subjectRef,
        ];
    }
}
