<?php

namespace Modules\Appointment\Enums;

use BenSampo\Enum\Enum;

class Status extends Enum
{
    const NEW = 'new';
    const IN_PROGRESS = 'in_progress';
    const PENDING = 'pending';
    const SOLVED = 'solved';
    const CANCEL = 'canceled';
}
