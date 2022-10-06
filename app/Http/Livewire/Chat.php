<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class Chat extends Component
{

    public $messageText;
   // public $username;

    public function render()
    {
        $messages = Message::with('user')->latest()->take(100)->get()->sortBy('id');
      //  $username =User::where('id',1)->first();
    return view('livewire.chat', compact('messages' /*,'username'*/ ));
    }

    public function sendMessage()
    {
        Message::create([
            'user_id' => auth()->user()->id,
            'message_text' => $this->messageText,
        ]);

        $this->reset('messageText');
    }

}
