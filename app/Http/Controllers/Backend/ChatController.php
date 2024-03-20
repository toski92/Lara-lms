<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chats = ChatMessage::orderBy('id','DESC')
                ->where('sender_id',auth()->id())
                ->orWhere('receiver_id',auth()->id())
                ->get();

        $users = $chats->flatMap(function($chat){
            if ($chat->sender_id === auth()->id()) {
                return [$chat->sender, $chat->receiver];
            }
            return [$chat->receiver, $chat->sender];
        })->unique();

        return $users;
    }
    public function UserMsgById($userId){

        $user = User::find($userId);

        if ($user) {
           $messages = ChatMessage::where(function($q) use ($userId){
                     $q->where('sender_id',auth()->id());
                     $q->where('receiver_id',$userId);
                     })->orWhere(function($q) use ($userId){
                        $q->where('sender_id',$userId);
                        $q->where('receiver_id',auth()->id());
                     })->with('user')->get();

                     return response()->json([
                        'user' => $user,
                        'messages' => $messages,
                     ]);
        }else {
            abort(404);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'msg' => 'required'
        ]);

        ChatMessage::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->receiver_id,
            'msg' => $request->msg,
            'created_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Message Send Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
