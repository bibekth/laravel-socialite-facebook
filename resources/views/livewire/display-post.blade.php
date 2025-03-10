<div class="px-5 m-5">
    <div class="heading-of-post" style="display: flex; justify-content:center;">
        <flux:heading size="xl" class="mb-3">{{ $post->title }}</flux:heading>
    </div>
    <div class="image-section" style="display: flex; justify-content:center;">
        <img src="{{ asset('storage/'.$post->image) }}" alt="" width="" height="auto" srcset="">
    </div>
    <div class="sub-headings pt-3">
        By {{ $post->user->name }}
        <p>{{ $post->created_at->format('F j, Y g:i a') }}</p>
    </div>
    <p class="my-3">{{ $post->description }}</p>
    <div class="options-section my-3">
        <span style="text-decoration: underline">Voting options</span>
        @foreach($post->options as $option)
        @php
        $percentage = $totalCount > 0 ? ($option->counts / $totalCount) * 100 : 0;
        @endphp

        <div class="vote-option">
            <button class="vote-btn" wire:click='updateVote({{ $option->id }})'>
                {{ $option->option }} ({{ $option->counts }} votes)
                <div class="vote-fill" style="width: {{ $percentage }}%;"></div>
            </button>
        </div>
        @endforeach
        <div class="total-counts">Total Votes: {{ $totalCount }}</div>
    </div>
    <p style="margin: 48px 0 24px 0; text-decoration: underline;">Comment Section</p>
    <form wire:submit="saveComment" class="space-y-4" enctype="multipart/form-data">
        <flux:input wire:model.defer="comment" label="" description="" class="" />
        <flux:button type="submit">Submit</flux:button>
    </form>

    <div class="comments-section" style="display: flex; justify-content: space-between; gap: 20px; padding: 20px 0;">
        <!-- Top Comments Section -->
        <div class="top-comments"
            style="flex: 1; background-color: #1e1e1e; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <h3 style="margin-bottom: 15px; color: #e0e0e0; font-size: 1.2rem;">Top Comments</h3>
            @foreach($post->topComments() as $key => $comment)
            <div class="comment"
                style="margin-bottom: 10px; padding: 10px; background-color: #444; border-radius: 5px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-300 text-black dark:bg-neutral-700 dark:text-white">
                                    @if(auth()->user()->facebook_id !== null)
                                    <img src="https://graph.facebook.com/v18.0/me/picture?type=large&access_token={{ auth()->user()->token }}"
                                        alt="">
                                    @else
                                    {{ auth()->user()->initials() }}
                                    @endif
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <!-- Comment Text -->
                <p style="margin: 0; color: #ccc; font-size: 1rem;">{{ $comment->comment }}</p>

                <!-- Like and Dislike Buttons -->
                <div class="like-dislike-buttons"
                    style="margin-top: 10px; display: flex; gap: 15px; align-items: center;">
                    <!-- Like Button -->
                    <button class="like-btn" wire:click="likeComment({{ $comment->id }})"
                        style="padding: 5px 10px; border: none; background-color: #4caf50; color: white; border-radius: 5px; cursor: pointer;">
                        👍 {{ $comment->likes }}
                    </button>

                    <!-- Dislike Button -->
                    <button class="dislike-btn" wire:click="dislikeComment({{ $comment->id }})"
                        style="padding: 5px 10px; border: none; background-color: #f44336; color: white; border-radius: 5px; cursor: pointer;">
                        👎 {{ $comment->dislikes }}
                    </button>

                </div>
            </div>

            @endforeach
        </div>

        <!-- New Comments Section -->
        <div class="new-comments"
            style="flex: 1; background-color: #1e1e1e; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <h3 style="margin-bottom: 15px; color: #e0e0e0; font-size: 1.2rem;">New Comments</h3>
            @foreach($post->newComments() as $key => $comment)
            {{-- <div class="comment"
                style="margin-bottom: 10px; padding: 10px; background-color: #333; border-radius: 5px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <p style="margin: 0; color: #ccc; font-size: 1rem;">{{ $comment->comment }}</p>
            </div> --}}

            <div class="comment"
                style="margin-bottom: 10px; padding: 10px; background-color: #444; border-radius: 5px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-300 text-black dark:bg-neutral-700 dark:text-white">
                                    @if(auth()->user()->facebook_id !== null)
                                    <img src="https://graph.facebook.com/v18.0/me/picture?type=large&access_token={{ auth()->user()->token }}"
                                        alt="">
                                    @else
                                    {{ auth()->user()->initials() }}
                                    @endif
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <!-- Comment Text -->
                <p style="margin: 0; color: #ccc; font-size: 1rem;">{{ $comment->comment }}</p>

                <!-- Like and Dislike Buttons -->
                <div class="like-dislike-buttons"
                    style="margin-top: 10px; display: flex; gap: 15px; align-items: center;">
                    <!-- Like Button -->
                    <button class="like-btn" wire:click="likeComment({{ $comment->id }})"
                        style="padding: 5px 10px; border: none; background-color: #4caf50; color: white; border-radius: 5px; cursor: pointer;">
                        👍 {{ $comment->likes }}
                    </button>

                    <!-- Dislike Button -->
                    <button class="dislike-btn" wire:click="dislikeComment({{ $comment->id }})"
                        style="padding: 5px 10px; border: none; background-color: #f44336; color: white; border-radius: 5px; cursor: pointer;">
                        👎 {{ $comment->dislikes }}
                    </button>

                </div>
            </div>
            @endforeach
        </div>
    </div>


    <style>
        .options-section {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 400px;
            /* Adjust as needed */
        }

        .vote-option {
            position: relative;
            width: 100%;
        }

        .vote-btn {
            position: relative;
            width: 100%;
            padding: 10px;
            color: black;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: left;
            font-weight: bold;
            overflow: hidden;
        }

        .vote-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: rgba(0, 123, 255, 0.4);
            /* Light blue shade */
            transition: width 0.5s ease-in-out;
        }

        @media (max-width: 768px) {
            .comments-section {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</div>
