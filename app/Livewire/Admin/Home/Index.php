<?php

namespace App\Livewire\Admin\Home;

use App\Models\Answer;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use function PHPUnit\Framework\isFalse;

class Index extends Component
{
    public $test;
    public $prices = [];

    protected $rules = [
        'prices' => 'required|array|min:1',
        'prices.*' => 'required|string|min:0',
    ];

    protected $messages = [
        'prices.required' => 'حداقل یک قیمت وارد کنید',
        'prices.array' => 'فرمت قیمت‌ها نامعتبر است',
        'prices.min' => 'حداقل یک قیمت وارد کنید',
    ];


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
        $this->validate();
        $a = Answer::query()->where('message_id', $id)->get();

        if ($a->isEmpty()) {
            Answer::query()->create([
                'user_id' => '1',
                'message_id' => $id,
                'price' => $this->prices[$id] ?? null,
            ]);
            Message::query()->where('id', $id)->update([
                'chat_in_progress' => '1',
            ]);
            $this->prices = [];
        } else {
            Answer::query()->where('message_id', $id)->update([
                'price' => $this->prices[$id] ?? null,
                'respondent_by_code' => '',
                'respondent_name' => '',
            ]);
            Message::query()->where('id', $id)->update([
                'chat_in_progress' => '1',
            ]);
            $this->prices = [];

        }
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

    public function code_answer($chat_code, $id)
    {
        $user = Auth::user();
//        dd($user);
//        $user_id = $user->id;
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

        // نام و آیدی کاربر فعلی
        $user = Auth::user();
        $name = $user->name;
        $id = $user->id;

        // بروزرسانی رکورد
        $answer->update([
            'respondent_name' => $name,
            'respondent_id' => $id,
        ]);
        Message::query()->where('id', $id)->update([
            'chat_in_progress' => '1',
        ]);
    }

    public function render()
    {
        $messages = Message::query()
            ->where('chat_in_progress', '2')
            ->orderBy('created_at', 'desc') // جدیدترین پیام‌ها بالا
            ->get();
        $wait_for_price = Message::query()
            ->where('chat_in_progress', '3')
            ->orderBy('updated_at', 'desc')
            ->get();
        $ended_chats = Message::query()->where('chat_in_progress', '0')->get();
        $answers = Answer::query()
            ->whereHas('message', function ($q) {
                $q->where('chat_in_progress', '1');
            })
            ->orderBy('created_at', 'desc')
            ->get();


        return view('livewire.admin.home.index', compact('messages', 'ended_chats', 'answers', 'wait_for_price'));
    }
}
