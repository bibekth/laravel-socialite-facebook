<?php
    use Livewire\Volt\Component;
    use Livewire\WithFileUploads;
    use App\Models\Post;
    use App\Models\PostOption;
    use Illuminate\Support\Facades\Auth;

    new class extends Component {
        use WithFileUploads;

        public $title = "";
        public $image;
        public $description = "";
        public $options = [];

        public function mount()
        {
            // Initialize with one empty option by default
            $this->options[] = '';
        }


        public function savePost()
        {
            // Validate input
            $this->validate([
                'title' => 'required|string|max:255',
                'image' => 'nullable|image|max:2048', // Max 2MB file
                'description' => 'nullable|string',
                'options' => 'required|array|min:1', // Ensure there's at least one option
                'options.*' => 'required|string|min:1',
            ]);

            // Store image if uploaded
            $imagePath = $this->image ? $this->image->store('uploads', 'public') : null;

            // Save post logic (adjust according to your database structure)
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'image' => $imagePath,
                'description' => $this->description,
            ]);

            foreach ($this->options as $option) {
                PostOption::create([
                    'post_id'=>$post->id,
                    'option'=>$option,
                ]);
            }

            // Clear form fields
            $this->reset(['title', 'image', 'description','options']);

            // Success message
            // session()->flash('message', 'Post saved successfully!');
        }

        public function addOption()
        {
            $this->options[] = '';
        }

        public function removeOption($index)
        {
            unset($this->options[$index]);
            $this->options = array_values($this->options); // Re-index array
        }

        public function deletePost($postId){
            Post::destroy($postId);
        }
    };
?>
<x-layouts.app title="Dashboard">
    @volt
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:menu.radio.group>
            <div class="p-0 text-sm font-normal">
                <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                        <span
                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                            <img src="https://graph.facebook.com/v18.0/me/picture?type=large&access_token={{ auth()->user()->token }}"
                                alt="">
                        </span>
                    </span>

                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </flux:menu.radio.group>

        <!-- Success Message -->
        @if (session()->has('message'))
        <div class="p-3 text-green-600 bg-green-100 rounded-md">
            {{ session('message') }}
        </div>
        @endif

        <!-- Form -->
        <form wire:submit="savePost" class="space-y-4" enctype="multipart/form-data">
            <flux:input wire:model.defer="title" label="Title" description="The title of the post." class="mb-5" />

            <!-- File Upload for Image -->
            <flux:input type="file" wire:model="image" label="Upload Image" accept="image/*" class="mb-5" />

            <!-- Preview Image -->
            @if ($image)
            <div class="mt-2">
                <img src="{{ $image->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-md">
            </div>
            @endif

            <!-- Description Field -->
            <div class="mb-5">
                <label for="description" class="block text-sm font-medium text-white-700">Description</label>
                <textarea id="description" wire:model.defer="description"
                    class="w-full rounded-md border-white-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                    rows="3"></textarea>
                {{-- <p class="mt-1 text-xs text-white-500">Enter a brief description of the post.</p> --}}
            </div>

            <!-- Dynamic Voting Options -->
            <div>
                <label for="options" class="block text-sm font-medium text-white">Voting Options</label>

                @foreach ($this->options as $index => $option)
                <div class="flex items-center gap-4 mb-4">
                    <!-- Option Input Field -->
                    <input type="text" wire:model.defer="options.{{ $index }}"
                        class="w-full p-2 rounded-md border border-gray-300 shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                        placeholder="Option {{ $index + 1 }}">

                    <!-- Remove Option Button -->
                    @if ($index > 0)
                    <!-- Only show the button if it's not the first option -->
                    <button type="button" wire:click="removeOption({{ $index }})"
                        class="text-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    @endif
                </div>
                @endforeach

                <!-- Add New Option Button -->
                <button type="button" wire:click="addOption"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    Add Option
                </button>
            </div>

            <flux:button type="submit">Post</flux:button>
        </form>


        <div class="display-posts">
            <div class="my-5">
                <flux:heading size="xl">Your Posts</flux:heading>
                <flux:subheading>List of the posts that you have created.</flux:subheading>
            </div>
            @if(count(auth()->user()->posts) > 0)
            <table>
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(auth()->user()->posts as $post)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ asset($post->slug) }}" target="_blank" rel="noopener noreferrer">{{ $post->title
                                }}</a></td>
                        <td>
                            <button class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400" type="button" wire:click="deletePost({{ $post->id }})">DELETE</button>
                        </td>
                    </tr>
                    {{-- <div class=""><a href="{{ asset($post->slug) }}" target="_blank" rel="noopener noreferrer">{{
                            $loop->iteration }} . {{ $post->title }}</a></div> --}}
                    @endforeach

                </tbody>
            </table>
            @endif
            {{-- @foreach(auth()->user()->posts as $post)
            <div class=""><a href="{{ asset($post->slug) }}" target="_blank" rel="noopener noreferrer">{{
                    $loop->iteration }} . {{ $post->title }}</a></div>
            @endforeach --}}
        </div>
    </div>
    @endvolt
</x-layouts.app>
