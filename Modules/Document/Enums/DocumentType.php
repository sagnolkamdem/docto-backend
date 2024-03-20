<?php

namespace Modules\Document\Enums;

use BenSampo\Enum\Enum;

class DocumentType extends Enum
{
    const ORDONANCES = 'Ordonnances';
    const RESULTS = 'Résultats d’examen';
    const ANALYSIS_FEEDBACK = 'Compte rendu d’analyses médicales';
    const IMAGING_FEEDBACK = 'Compte rendu d’imagerie médicale';
    const CERTIFICATE = 'Certificat';
    const VACCINE = 'Vaccin';
    const OTHERS = 'Autres';
}
