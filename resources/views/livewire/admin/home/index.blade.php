<div class="w-full" style="margin: 10px" wire:poll.5s>
    <div class="mb-6 flex justify-between items-center m-5" dir="rtl">
        <h1 class="text-2xl font-bold text-gray-700">پنل پیام‌های ادمین‌ها</h1>
        <div class="flex gap-3">
            <button class="bg-white p-2 rounded-xl shadow">📩</button>
            <button class="bg-white p-2 rounded-xl shadow">👤</button>
        </div>
    </div>
    <div class="bg-gray-200 border-l rounded-b-2xl float-left m-2 w-[24%] max-h-[800px]" style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center">
            چت‌های در جریان
        </div>
        <ul class="space-y-2 text-sm overflow-scroll">
            @foreach($messages as $message)
                <li class="p-2 rounded-lg">
                    <p class="text-right">
                        کاربر شماره {{$message->user_id}}
                    </p>
                    <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                    <div id="productCode" class="inline-block">
                        <p class="inline-block">
                            {{$message->code}}
                        </p>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition" wire:click="code_answer('a',{{ $message->id }})">a</button>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition" wire:click="chat_answer('k',{{ $message->id }})">k</button>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition" wire:click="chat_answer('h',{{ $message->id }})">h</button>
                        <button class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition" wire:click="chat_answer('x',{{ $message->id }})">x</button>
                    </div>
                    <div class="bg-white"
                         style="margin: 15px 5px 0 5px;padding: 0;border-radius: 10px;height: 40px;overflow: hidden;position:relative;">
                        <input type="text"
                               class="h-[40px] pl-1 w-full bg-white"
                               wire:model.defer="prices.{{$message->id}}">
                        <button type="submit"
                                wire:click="submit_answer({{ $message->id }})"
                                class="inline-block px-4 py-2 bg-blue-600 text-white"
                                style="position:absolute;right: 0;height: 100%">
                            submit
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-gray-200 border-l h-full rounded-b-2xl float-left m-2 w-[24%]" style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center">
            منتظر برسی
        </div>
        <ul class="space-y-2 text-sm">
            @foreach($answers as $answer)
                <li class="p-2 rounded-lg relative">
                    <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                    <div class="inline-block text-center absolute w-4/5 left-0 right-0 z-0">
                        <p>کد محصول : {{ $answer->message->code }}</p>
                        <p>پاسخ : {{ $answer->price }}</p>
                    </div>
                    <button
                        wire:click="check_answer({{$answer->message->id }})"
                        class="px-4 py-2 rounded-xl float-right bg-blue-600 text-white cursor-pointer z-10">checked
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-gray-200 border-l rounded-b-2xl float-left m-2 w-[24%]" style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center">
            منتظر قیمت
        </div>
        <ul class="space-y-2 text-sm">
            <li class="p-2 rounded-lg">
                <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                <p class="inline-block">کد محصول</p>
                <div class="bg-white"
                     style="margin: 15px 5px 0 5px;padding: 0;border-radius: 10px;height: 40px;overflow: hidden;position:relative;">
                    <input type="text"
                           class="h-[40px] pl-1 w-full bg-white"
                           wire:model.defer="prices.{{@$message->id}}">
                    <button type="button"
                            wire:click="submit_answer({{@$message->id }})"
                            class="inline-block px-4 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 transition active:scale-95 m-0"
                            style="position:absolute;right: 0;height: 100%">
                        submit
                    </button>
                </div>
            </li>
        </ul>
    </div>

    <div class="bg-gray-200 border-l rounded-b-2xl float-left m-2 w-[24%]" style="overflow: auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center">
            تکمیل شده
        </div>
        <ul class="space-y-2 text-sm">
            @foreach($ended_chats as $chat)
                <li class="p-2 rounded-lg">
                    <img class="w-[40px] rounded-[100px] inline-block" src="/IMG/prof.jpg" alt="">
                    <p class="inline-block">{{$chat->code}}</p>
                    |
                    <p class="inline-block">قیمت: {{$chat->answer->price ?? '---'}}</p>
                </li>
            @endforeach
        </ul>
    </div>

    <button id="chat-btn">💬</button>

    <form wire:submit.prevent="submit" id="chat-box">
        <div id="chat-header">پشتیبانی آنلاین</div>

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
