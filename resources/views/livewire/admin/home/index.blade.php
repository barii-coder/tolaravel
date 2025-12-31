<div class="w-full" style="margin:10px" wire:poll.1s>

    {{-- Header --}}
    <div class="mb-4 flex justify-between items-center m-5" dir="rtl">
        <h1 class="text-2xl font-bold text-gray-700">پنل پیام‌های ادمین‌ها</h1>
        <div class="flex gap-3">
            <button class="bg-white p-2 rounded-xl shadow">📩</button>
            <a href="/register" class="bg-white p-2 rounded-xl shadow">👤</a>
        </div>
    </div>

    {{-- Error --}}
    @error('prices')
    <div class="w-full max-w-md mx-auto">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 text-center rounded relative">
            <strong class="font-bold">خطا! </strong>
            <span>{{ $message }}</span>
            <!--<button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"-->
            <!--        onclick="this.parentElement.remove()">X</button>-->
        </div>
    </div>
    @enderror
{{--    @dd($user)--}}

    {{-- Chat In Progress --}}
    <div class="bg-gray-200 rounded-2xl float-left m-2 w-[24%] max-h-[800px] overflow-auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center sticky top-0">
            چت‌های در جریان
        </div>

        <ul class="space-y-2 text-sm">
            @foreach($messages as $message)
                <li class="p-2 rounded-lg" wire:key="message-{{ $message->id }}-{{ $loop->index }}">
                    <img class="w-10 rounded-full inline-block" src="{{$message->user->profile_image_path}}">

                    <div class="inline-block">
                        <p class="inline-block text-lg m-1">{{ $message->code }}</p>

                        @foreach(['a','k','h','x'] as $c)
                            <button
                                wire:click="code_answer('{{ $c }}',{{ $message->id }})"
                                class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                                {{ $c }}
                            </button>
                        @endforeach
                    </div>

                    <div class="bg-white mt-3 rounded-lg h-10 relative overflow-hidden">
                        <input type="text"
                               class="h-full w-full pl-2"
                               wire:model.defer="prices.{{ $message->id }}"
                               placeholder="قیمت"
                               wire:keydown.enter="submit_answer({{ $message->id }})">

                        <button
                            wire:click="submit_answer({{ $message->id }})"
                            class="absolute right-0 top-0 h-full px-4 bg-blue-600 text-white">
                            ➤
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Waiting Review --}}
    <div class="bg-gray-200 rounded-2xl float-left m-2 w-[24%] max-h-[800px] overflow-auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center sticky top-0">
            منتظر بررسی
        </div>

        <ul class="space-y-2 text-sm">
            @foreach($answers as $answer)
                <li class="p-2 rounded-lg" wire:key="answer-{{ $answer->id }}">
                    <img class="w-10 rounded-full inline-block" src="/IMG/prof.jpg">

                    <span onclick="copyText(this)"
                          class="inline-flex px-4 py-2 bg-slate-400 text-white rounded-xl cursor-pointer">
                        {{ $answer->message->code }}
                    </span>

                    <span class="inline-flex px-4 py-2 bg-green-500 text-white rounded-full">
                        {{ $answer->price }}
                    </span>

                    @if($answer->respondent_by_code)
                        @if($answer->respondent_id)
                            <button wire:click="save_for_ad_price({{ $answer->message->id }})"
                                    class="px-3 py-2 bg-blue-600 text-white rounded-xl float-right">
                                ➜
                            </button>

                            <span class=" m-1 text-white rounded-xl float-right">
                                <img width="30px" class="rounded-2xl" src="{{$answer->respondent_profile_image_path}}" alt="">
                            </span>
                        @else
                            @if($answer->price === 'x')
                                <button
                                    wire:click="check_answer({{$answer->message->id }})"
                                    class="px-4 py-2 rounded-xl float-right bg-blue-600 text-white cursor-pointer z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </button>
                                <span class="px-3 py-2 rounded-xl float-right mx-1 bg-red-600 text-white">
                                    محصول نا موجود
                                </span>
                            @else
                                <button wire:click="i_had_it({{ $answer->message->id }})"
                                        class="px-3 py-2 bg-blue-600 text-white rounded-xl float-right">
                                    من برداشتم
                                </button>
                            @endif
                        @endif
                    @else
                        <button wire:click="check_answer({{ $answer->message->id }})"
                                class="px-3 py-2 bg-blue-600 text-white rounded-xl float-right">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Waiting Price --}}
    <div class="bg-gray-200 rounded-2xl float-left m-2 w-[24%] max-h-[800px] overflow-auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center sticky top-0 z-10">
            منتظر قیمت
        </div>

        <ul class="space-y-2 text-sm">
            @foreach($wait_for_price as $message)
                <li class="p-2 rounded-lg" wire:key="wait-{{ $message->id }}">
                    <img class="w-10 rounded-full inline-block" src="/IMG/prof.jpg">
                    <span onclick="copyText(this)" class="cursor-pointer">{{ $message->code }}</span>

                    <div class="bg-white mt-3 rounded-lg h-10 relative overflow-hidden">
                        <input type="text"
                               class="h-full w-full pl-2"
                               wire:model.defer="prices.{{ $message->id }}"
                               wire:keydown.enter="submit_answer({{ $message->id }})"
                               placeholder="قیمت">

                        <button wire:click="submit_answer({{ $message->id }})"
                                class="absolute right-0 top-0 h-full px-4 bg-blue-600 text-white">
                            ➤
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Finished --}}
    <div class="bg-gray-200 rounded-2xl float-left m-2 w-[24%] max-h-[500px] overflow-auto">
        <div class="bg-blue-600 text-white p-4 rounded-t-2xl font-bold text-center sticky top-0">
            تکمیل شده
        </div>

        <ul class="space-y-2 text-sm">
            @foreach($ended_chats as $chat)
                <li class="p-2 rounded-lg">
                    <img class="w-10 rounded-full inline-block" src="/IMG/prof.jpg">
                    <span>{{ $chat->code }}</span>
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
            <button onclick="sendMessage()">➤</button>
        </div>
    </form>

    <script>
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

        function copyText(element) {
            const text = element.innerText;
            navigator.clipboard.writeText(text)
                .then(() => {
                    alert("کد کپی شد ✅");
                })
                .catch(err => {
                    console.error("خطا در کپی:", err);
                });
        }
    </script>

</div>
