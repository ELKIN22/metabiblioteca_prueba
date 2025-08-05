<?php

namespace App\DTOs;

class OrcidData
{
    public function __construct(
        public readonly string $orcid,
        public readonly ?string $givenNames,
        public readonly ?string $familyName,
        public readonly array $keywords,
        public readonly ?string $primaryEmail
    ) {}

    public function toArray(): array
    {
        return [
            'orcid' => $this->orcid,
            'given_names' => $this->givenNames,
            'family_name' => $this->familyName,
            'keywords' => $this->keywords,
            'primary_email' => $this->primaryEmail,
        ];
    }
}
