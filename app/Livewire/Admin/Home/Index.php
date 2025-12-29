<?php

namespace App\Livewire\Admin\Home;

use App\Models\Answer;
use App\Models\Message;
use Livewire\Component;

class Index extends Component
{
    public $test;
    public $prices = [];

    public $user_id= 1;

    protected $rules = [
        'prices'     => 'required|array|min:1',
        'prices.*'   => 'required|numeric|min:0',
    ];

    protected $messages = [
        'prices.required'   => 'حداقل یک قیمت وارد کنید',
        'prices.array'      => 'فرمت قیمت‌ها نامعتبر است',
        'prices.min'        => 'حداقل یک قیمت وارد کنید',
        'prices.*.required' => 'قیمت نمی‌تواند خالی باشد',
        'prices.*.numeric'  => 'قیمت باید عدد باشد',
        'prices.*.min'      => 'قیمت نمی‌تواند منفی باشد',
    ];


    public function submit()
    {
//        $this->validate();

        Message::query()->create([
            'user_id' => '1',
            'code' => $this->test,
            'chat_in_progress' => '2',
        ]);
    }

    public function submit_answer($id)
    {
        $this->validate();

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

    public function save_for_ad_price($messageId)
    {
        Message::query()->where('id', $messageId)->update([
            'chat_in_progress' => '3',
        ]);
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
            'respondent_by_code' => '1',
        ]);
        Message::query()->where('id', $id)->update([
            'chat_in_progress' => '1',
        ]);
    }

    public function i_had_it($messageId)
    {
        $answer = Answer::where('message_id', $messageId)->first();

        $name = $answer->respondent_name = 'ادمین 3';
        $id = $answer->respondent_id = $this->user_id;
        Answer::query()->where('message_id', $messageId)->update([
            'respondent_name' => $name,
            'respondent_id' => $id,
        ]);

    }


    public function render()
    {
//        $messages = Message::query()->where('chat_in_progress', '2')->get();
        $messages = Message::query()
            ->where('chat_in_progress', '2')
            ->orderBy('created_at', 'desc') // جدیدترین پیام‌ها بالا
            ->get();
        $wait_for_price = Message::query()->where('chat_in_progress', '3')->get();
        $ended_chats = Message::query()->where('chat_in_progress', '0')->get();
        $answers = Answer::query()
            ->whereHas('message', function ($q) {
                $q->where('chat_in_progress', '1');
            })
            ->get();

        return view('livewire.admin.home.index', compact('messages', 'ended_chats', 'answers','wait_for_price'))->layout('layouts.admin.app');
    }
}
