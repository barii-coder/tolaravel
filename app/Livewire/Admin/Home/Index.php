<?php
//
//namespace App\Livewire\Admin\Home;
//
//use App\Models\answers;
//use App\Models\messages;
//use Livewire\Component;
//
//class Index extends Component
//{
//    public $test;
//    public $prices = [];
//
//    public function submit()
//    {
//        messages::query()->create([
//            'user_id' => '1',
//            'code' => $this->test,
//        ]);
//    }
//
//    public function submit_answer($id)
//    {
//        answers::query()->create([
//            'user_id' => '1',
//            'message_id' => $id,
//            'price' => $this->prices[$id],
//        ]);
//        return redirect()->back();
//    }
//
//
//    public function render()
//    {
//        $messages = messages::all();
//        return view('livewire.admin.home.index',compact('messages'));
//    }
//}


namespace App\Livewire\Admin\Home;

use App\Models\Answer;
use App\Models\Message;
use Livewire\Component;

class Index extends Component
{
    public $test;
    public $prices = [];

    public function submit()
    {
        Message::query()->create([
            'user_id' => '1',
            'code' => $this->test,
            'chat_in_progress' => '2',
        ]);
    }

    public function submit_answer($id)
    {
        Answer::query()->create([
            'user_id' => '1',
            'message_id' => $id,
            'price' => $this->prices[$id] ?? null,
        ]);
        Message::query()->where('id', $id)->update([
            'chat_in_progress' => '1',
        ]);
        $this->prices = [];
    }

    public function check_answer($id)
    {
        Message::query()->where('id', $id)->update([
            'chat_in_progress' => '0',
        ]);
    }

    public function code_answer($chat_code,$id)
    {
        Answer::query()->create([
            'user_id' => '1',
            'message_id' => $id,
            'price' => $chat_code,
        ]);
        Message::query()->where('id', $id)->update([
            'chat_in_progress' => '1',
        ]);    }

    public function render()
    {
        $messages = Message::query()->where('chat_in_progress', '2')->get();
        $ended_chats = Message::query()->where('chat_in_progress', '0')->get();
        $answers = Answer::query()
            ->whereHas('message', function ($q) {
                $q->where('chat_in_progress', '1');
            })
            ->get();
//        dd($answers);

        return view('livewire.admin.home.index', compact('messages', 'ended_chats', 'answers'));
    }
}
