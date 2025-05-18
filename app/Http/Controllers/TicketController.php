<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use App\Service\FileUploadService;
use App\Service\ChatService;
use App\Service\TicketService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use ApiResponse;

    public static ?Ticket $ticket = null;
    public static TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        self::$ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        $filter = $request->only(['status', 'priority', 'limit']);
        $tickets = self::$ticketService->getTickets($filter);

        if(!empty($tickets)){
            return $this->success($tickets);
        }
        return $this->error();
    }

    /**
     * Store a newly created resource in storage.
     * @param TicketRequest$request
     * @return JsonResponse
     *@throws ValidationException
     */
    public function store(TicketRequest $request): JsonResponse
    {
        $id = $request->get('id');
        $file = $request->get('attachment', [])['src'] ?? null;
        $data = $request->except('_token', 'id', 'attachment');

        if (!blank($id)) {
            self::$ticket = Ticket::find($id);
        }
        if (!empty($file)) {
            $url = FileUploadService::uploadBase64($file['src']);
            $data['attachment'] = $url;
        }

        self::$ticket = TicketService::updateOrCreate($data, self::$ticket);
        $message = $id ? "updated" : "created";
        return self::success(self::$ticket, "Ticket $message successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = self::$ticketService->findTicket($id);

        if (!empty($ticket)) {
            return $this->success($ticket);
        }
        return $this->error();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(self::$ticketService->deleteTicket($id)){
            return self::success(message: "ticket $id successfully deleted");
        }
        return $this->error();
    }
}
