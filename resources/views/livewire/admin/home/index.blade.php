<div class="w-full" style="margin: 10px" wire:poll.1s>
    <div class="mb-4 flex justify-between items-center m-5" dir="rtl">
        <h1 class="text-2xl font-bold text-gray-700">پنل پیام‌های ادمین‌ها</h1>
        <div class="flex gap-3">
            <button class="bg-white p-2 rounded-xl shadow">📩</button>
            <button class="bg-white p-2 rounded-xl shadow">👤</button>
        </div>
    </div>
    @error('prices')
    <div class="relative w-full max-w-md mx-auto">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">خطا! </strong>
            <span class="block sm:inline">{{ $message }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    onclick="this.parentElement.remove()">
                <svg class="fill-current h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                </svg>
            </button>
        </div>
    </div>
    @enderror
    <br>
    <div class="bg-gray-200 border-l rounded-2xl float-left m-2 md:w-[24%] w-full max-h-[800px]" style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center z-10"
             style="position: sticky;top: 0">
            چت‌های در جریان
        </div>
        <ul class="space-y-2 text-sm overflow-scroll">
            @foreach($messages as $message)
                <li class="p-2 rounded-lg" wire:key="message-{{ $message->id }}">
                    <p class="text-right">
                        کاربر شماره {{$message->user_id}}
                    </p>
                    <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                    <div id="productCode" class="inline-block">
                        <p class="inline-block">
                            {{$message->code}}
                        </p>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
                                wire:click="code_answer('a',{{ $message->id }})">a
                        </button>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
                                wire:click="code_answer('k',{{ $message->id }})">k
                        </button>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
                                wire:click="code_answer('h',{{ $message->id }})">h
                        </button>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
                                wire:click="code_answer('x',{{ $message->id }})">x
                        </button>
                    </div>
                    <div class="bg-white"
                         style="margin: 15px 5px 0 5px;padding: 0;border-radius: 10px;height: 40px;overflow: hidden;position:relative;">
                        <input type="text"
                               class="h-[40px] pl-1 w-full bg-white"
                               wire:model.defer="prices.{{$message->id}}"
                               placeholder="قیمت"
                               wire:keydown.enter="submit_answer({{ $message->id }})">
                        <button type="submit"
                                wire:click="submit_answer({{ $message->id }})"
                                class="inline-block px-4 py-2 bg-blue-600 text-white"
                                style="position:absolute;right: 0;height: 100%">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-gray-200 border-l h-full rounded-2xl float-left m-2 md:w-[24%] w-full max-h-[800px]"
         style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center z-10"
             style="position: sticky;top: 0">
            منتظر برسی
        </div>
        <ul class="space-y-2 text-sm">
            @foreach($answers as $answer)
                <li class="p-2 rounded-lg relative" wire:key="answer-{{ $answer->id }}">
                    <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                    <div class="inline-block text-center">
                        <p class="z-0">کد محصول : {{ $answer->message->code }}</p>
                        <p class="z-0">پاسخ : {{ $answer->price }}</p>
                    </div>
                    @if($answer->respondent_by_code != null)
                        @if($answer->respondent_id != null)
                            <button
                                wire:click="save_for_ad_price({{$answer->message->id }})"
                                class="px-3 py-2 rounded-xl float-right mx-1 bg-blue-600 text-white cursor-pointer z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21C12 21 5 14 5 9C5 6.23858 7.23858 4 10 4C11.6569 4 13 5.34315 13 7C13 5.34315 14.3431 4 16 4C18.7614 4 21 6.23858 21 9C21 14 12 21 12 21Z"/>
                                </svg>
                            </button>
                            <span class="px-3 py-2 rounded-xl float-right mx-1 bg-green-600 text-white">
  پاسخ از :                  {{$answer->respondent_name}}
        </span>
                        @else
                            <button
                                wire:click="i_had_it({{$answer->message->id }})"
                                class="px-3 py-2 rounded-xl float-right mx-1 bg-blue-600 text-white cursor-pointer z-10">
                                من برداشتم
                            </button>
                        @endif
                    @else
                        <button
                            wire:click="check_answer({{$answer->message->id }})"
                            class="px-4 py-2 rounded-xl float-right bg-blue-600 text-white cursor-pointer z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-gray-200 border-l rounded-2xl float-left m-2 md:w-[24%] w-full max-h-[800px]" style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center z-10"
             style="position: sticky;top: 0">
            منتظر قیمت
        </div>
        <ul class="space-y-2 text-sm">
            @foreach($wait_for_price as $message)
                <li class="p-2 rounded-lg" wire:key="wait-message-{{ $message->id }}">
                    <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                    <p class="inline-block">{{$message->code}}</p>
                    <div class="bg-white"
                         style="margin: 15px 5px 0 5px;padding: 0;border-radius: 10px;height: 40px;overflow: hidden;position:relative;">
                        <input type="text"
                               class="h-[40px] pl-1 w-full bg-white"
                               wire:model.defer="prices.{{$message->id}}"
                               placeholder="قیمت"
                               wire:keydown.enter="submit_answer({{ $message->id }})">
                        <button type="button"
                                wire:click="submit_answer({{@$message->id }})"
                                class="inline-block px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition active:scale-95 m-0"
                                style="position:absolute;right: 0;height: 100%">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </div>
                </li>
            @endforeach

        </ul>
    </div>

    <div class="bg-gray-200 border-l max-h-[500px] rounded-2xl float-left m-2 md:w-[24%] w-full" style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center z-10"
             style="position: sticky;top: 0">
            تکمیل شده
        </div>
        <ul class="space-y-2 text-sm">
            @foreach($ended_chats as $chat)
                <li class="p-2 rounded-lg">
                    <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                    <p class="inline-block">{{$chat->code}}</p>
                </li>
            @endforeach
        </ul>
    </div>

    <form wire:submit.prevent="submit" id="chat-box">
        <div id="chat-header">ارسال پیام</div>

        <div id="chat-body">
            <div class="msg bot">.کد محصول مورد نظر را وارد بنمایید</div>
        </div>

        <div id="chat-input">
            <input type="text" wire:model.defer="test" id="messageInput" placeholder="پیام..."/>
            <button type="submit" onclick="sendMessage()">➤</button>
        </div>
    </form>

    <script>
        const chatBtn = document.getElementById("chat-btn");
        const chatBox = document.getElementById("chat-box");
        const chatBody = document.getElementById("chat-body");
        const input = document.getElementById("messageInput");
        const productCodeElement = document.getElementById("productCode");

        chatBtn.onclick = () => {
            chatBox.style.display = chatBox.style.display === "flex" ? "none" : "flex";
        };

        function sendMessage() {
            if (input.value.trim() === "") return;

            const userMsg = document.createElement("div");
            userMsg.className = "msg user";
            userMsg.innerText = input.value;
            chatBody.appendChild(userMsg);

            setTimeout(() => {
                const botMsg = document.createElement("div");
                botMsg.className = "msg bot";
                botMsg.innerText = "کد محصول ثبت شد☑️";
                chatBody.appendChild(botMsg);
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 600);

            input.value = "";
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    </script>
</div>
