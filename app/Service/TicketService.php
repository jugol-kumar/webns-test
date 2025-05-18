<?php

namespace App\Service;

use App\Enum\TicketPriority;
use App\Enum\TicketStatus;
use App\Models\Ticket;

class TicketService
{
    public static array $priority = [
        TicketPriority::HIGH,
        TicketPriority::MEDIUM,
        TicketPriority::LOW,
    ];
    public static array $status = [
        TicketStatus::OPEN,
        TicketStatus::CLOSED,
        TicketStatus::IN_PROGRESS,
        TicketStatus::RESOLVED,
    ];
    public static ?Ticket $ticket = null;


    public function getTickets(array $filter)
    {
        $tickets = Ticket::with(['comments', 'comments.user'])
        ->when(!empty($filter['status']), function ($query)use($filter) {
            $query->where('status', $filter['status']);
        })->when(!empty($filter['priority']), function ($query)use($filter) {
            $query->where('priority', $filter['priority']);
        })->cursorPaginate($filter['limit'] ?? 10);

        if(!empty($tickets)) {
            return $tickets;
        }
        return false;
    }
    public static function updateOrCreate($data, ?Ticket $ticket): ?Ticket
    {
        self::$ticket = $ticket;
        if (empty(self::$ticket)){
            self::$ticket = new Ticket();
        }
        self::$ticket->fill($data);
        self::$ticket->save();
        return self::$ticket->fresh();
    }

    public function findTicket(string $id)
    {
        return Ticket::find($id) ?? false;
    }

    public function deleteTicket(string $id)
    {
        return Ticket::find($id)->delete() ?? false;
    }

}
