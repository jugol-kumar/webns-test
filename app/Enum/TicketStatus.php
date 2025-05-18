<?php

namespace App\Enum;

interface TicketStatus
{
    const OPEN = 'open';
    const CLOSED = 'closed';
    const IN_PROGRESS = 'in_progress';
    const RESOLVED = 'resolved';

}
