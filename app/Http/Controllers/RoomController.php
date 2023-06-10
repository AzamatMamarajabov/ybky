<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->only(['name', 'type']);
        $room = Room::create($data);
        return response()->json($room, 201);
    }

    public function index(Request $request)
    {
        $type = $request->input('type');
        $page = $request->input('page',1);
        $pageSize = $request->input('page_size', 10);
    
        $query = Room::query();
    
        if ($type) {
            $query->where('type', $type);
        }
    
        $totalRooms = $query->count();
    
        $rooms = $query->skip(($page - 1) * $pageSize)->take($pageSize)->get();
    
        $formattedRooms = $rooms->map(function ($room) {
            $formattedRoom = [
                'id' => $room->id,
                'name' => $room->name,
                'type' => $room->type,
            ];
    
            if ($room->type == 'focus') {
                $formattedRoom['capacity'] = 1;
            } elseif ($room->type == 'team') {
                $formattedRoom['capacity'] = 5;
            }elseif ($room->type == 'conference') {
                $formattedRoom['capacity'] = 15;
            }
    
            return $formattedRoom;
        });
    
        $response = [
            'page' => $page,
            'count' => count($rooms),
            'page_size' => $pageSize,
            'results' => $formattedRooms,
        ];
    
        return response()->json($response);
    }
   


public function getRoomById($id)
{
    $room = Room::find($id);

    if ($room) {
        $formattedRoom = [
            'id' => $room->id,
            'name' => $room->name,
            'type' => $room->type,
            'capacity' => 0, // Default qiymat
        ];

        if ($room->type == 'focus') {
            $formattedRoom['capacity'] = 1;
        } elseif ($room->type == 'team') {
            $formattedRoom['capacity'] = 5;
        } elseif ($room->type == 'conference') {
            $formattedRoom['capacity'] = 15;
        }

        return response()->json($formattedRoom);
    } else {
        return response()->json([
            'error' => 'Xona topilmadi!',
        ], 404);
    }
}





public function bookRoom($id, Request $request)
{
    $room = Room::findOrFail($id);
    $residentName = $request->input('resident.name');
    $startDateTime = $request->input('start');
    $endDateTime = $request->input('end');

    // Band qilish vaqtini tekshirish
    if (Carbon::parse($startDateTime)->isAfter($endDateTime)) {
        return response()->json(['message' => 'Boshlanish vaqti tugash vaqtidan keyin bo\'lishi kerak'], 400);
    }

    // Xona band qilishini tekshirish
    $conflictingBooking = $room->bookings()->where(function ($query) use ($startDateTime, $endDateTime) {
        $query->where(function ($q) use ($startDateTime, $endDateTime) {
            $q->where('start_datetime', '>=', $startDateTime)->where('start_datetime', '<', $endDateTime);
        })->orWhere(function ($q) use ($startDateTime, $endDateTime) {
            $q->where('end_datetime', '>', $startDateTime)->where('end_datetime', '<=', $endDateTime);
        })->orWhere(function ($q) use ($startDateTime, $endDateTime) {
            $q->where('start_datetime', '<=', $startDateTime)->where('end_datetime', '>=', $endDateTime);
        });
    })->first();

    if ($conflictingBooking) {
        return response()->json(['message' => 'Xona berilgan vaqt oralig\'ida band qilingan'], 400);
    }

    // Xonani band qilish
    $booking = new Booking();
    $booking->room_id = $id;
    $booking->resident_name = $residentName;
    $booking->start_datetime = $startDateTime;
    $booking->end_datetime = $endDateTime;
    $booking->save();

    return response()->json(['message' => 'Xona band qilindi'], 200);
}




    public function type(Request $request)
{
    $type = $request->input('type');

    $query = Room::query();

    if ($type) {
        $query->where('type', $type);
    }

    $rooms = $query->get();

    return response()->json($rooms);
}




public function availability(Request $request, $id)
{
    $date = $request->input('date', date('Y-m-d')); // Agar sana ko'rsatilmagan bo'lsa, bugungi sanani olib olamiz

        $room = Room::find($id);
        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $bookings = $room->bookings()->whereDate('start_datetime', $date)->get(['start_datetime', 'end_datetime']);

        $availability = [];

        $startTime = date('Y-m-d 00:00:00', strtotime($date));
        $endTime = date('Y-m-d 23:59:59', strtotime($date));

        // Boshlang'ich va oxirgi bo'sh vaqtni qo'shamiz
        if ($bookings->isEmpty()) {
            $availability[] = [
                'start' => $startTime,
                'end' => $endTime,
            ];
        } else {
            $startDateTime = $startTime;

            foreach ($bookings as $booking) {
                $endDateTime = $booking->start_datetime;

                if ($startDateTime < $endDateTime) {
                    $availability[] = [
                        'start' => $startDateTime,
                        'end' => $endDateTime,
                    ];
                }

                $startDateTime = $booking->end_datetime;
            }

            // Oxirgi bo'sh vaqt
            if ($startDateTime < $endTime) {
                $availability[] = [
                    'start' => $startDateTime,
                    'end' => $endTime,
                ];
            }
        }

        return response()->json($availability);
    
}


}

