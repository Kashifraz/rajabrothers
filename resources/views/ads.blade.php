<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-orange-700 p-4 mb-3" role="alert">
                <p class="font-bold text-lg">Errors while processing request.</p>
                <ul class="max-w-md space-y-1 list-disc list-inside ">
                    @foreach ($errors->all() as $error)
                    <li>
                        {{$error}}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(Session::has('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-3" role="alert">
                <p>{{ Session::get('success') }}</p>
            </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <div class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 p-4">
                        <x-primary-button class="ms-3" onclick="categoryModal(true)">
                            {{ __('create Ad') }}
                        </x-primary-button>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Category Image
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Category Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Created At
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ads as $category)
                            <tr class="bg-white border-b hover:bg-gray-50 ">
                                <td class="">
                                    <div class="flex items-center my-2 ">
                                        @if ($category->image != "NA")
                                        <img class="w-48 h-24 mb-2  ml-5 " src="{{ asset('images/ads/'.$category->image)}}" />
                                        @endif
                                    </div>
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 ">
                                    {{$category->title}}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $category->created_at->format('F j, Y, g:i A') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <!-- Edit Button -->
                                        <a onclick="updateModal(true, '{{ $category->id }}', '{{ $category->name }}', '{{ asset('images/'.$category->image) }}')" class="font-medium text-blue-600 text-lg hover:text-blue-800">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 text-lg hover:text-red-800">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
            <nav class="my-4" aria-label="Table navigation">
            </nav>
        </div>
    </div>

    <!-- Add category model  -->
    <div class="py-12 bg-gray-700 hidden transition duration-150 ease-in-out z-10 fixed top-0 right-0 bottom-0 left-0" id="category_modal">
        <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-4xl">
            <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                <h1 class="text-gray-800 font-lg text-2xl font-bold tracking-normal leading-tight mb-4">Create Ad</h1>
                <form method="post" action="{{route('discounts.store')}}" class="mt-6 space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="title" value="Ad title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <label for="ad_image" class="mb-2 inline-block text-neutral-700">Ad Image</label>
                        <input name="image" id="ad_image" class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none" type="file" />
                    </div>


                    <div class="flex items-center justify-start w-full">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>
                </form>
                <button class="cursor-pointer absolute top-0 right-0 mt-4 mr-5 text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out rounded focus:ring-2 focus:outline-none focus:ring-gray-600" onclick="categoryModal()" aria-label="close modal" role="button">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Update category model  -->
    <div class="py-12 bg-gray-700 hidden transition duration-150 ease-in-out z-10 fixed top-0 right-0 bottom-0 left-0" id="update_modal">
        <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-4xl">
            <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                <h1 class="text-gray-800 font-lg text-2xl font-bold tracking-normal leading-tight mb-4">Update Category</h1>
                <form method="post" id="updateCategoryForm" class="mt-6 space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Hidden input for Category ID -->
                    <input type="hidden" id="update_category_id" name="category_id">

                    <div>
                        <x-input-label for="update_name" value="Category Name" />
                        <x-text-input id="update_name" name="name" type="text" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <label for="update_category_image" class="mb-2 inline-block text-neutral-700">Upload New Category Image</label>
                        <input name="image" id="update_category_image" class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none" type="file" />
                    </div>

                    <!-- Display the current category image if exists -->
                    <div>
                        <label for="current_image" class="mb-2 inline-block text-neutral-700">Current Category Image</label>
                        <img id="current_image" class="w-32 h-24 mb-2" src="" style="display: none;" />
                    </div>

                    <div class="flex items-center justify-start w-full">
                        <x-primary-button>{{ __('Update') }}</x-primary-button>
                    </div>
                </form>
                <button class="cursor-pointer absolute top-0 right-0 mt-4 mr-5 text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out rounded focus:ring-2 focus:outline-none focus:ring-gray-600" onclick="updateModal()" aria-label="close modal" role="button">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        let category_modal = document.getElementById("category_modal");
        let update_modal = document.getElementById("update_modal");

        function categoryModal(val) {
            $('#category_modal').removeClass('hidden');
            if (val) {
                fadeIn(category_modal);
            } else {
                fadeOut(category_modal);
            }
        }

        function updateModal(val, id, name, imageUrl) {
            $('#update_modal').removeClass('hidden');

            if (val) {
                fadeIn(update_modal);
                // Pre-fill the form fields with category data
                document.getElementById('update_category_id').value = id;
                document.getElementById('update_name').value = name;

                let updateForm = document.getElementById('updateCategoryForm');
                updateForm.action = `/categories/${id}`; // Resource route

                // Display the current category image
                if (imageUrl && imageUrl !== "NA") {
                    document.getElementById('current_image').src = imageUrl;
                    document.getElementById('current_image').style.display = 'block';
                } else {
                    document.getElementById('current_image').style.display = 'none';
                }


            } else {
                fadeOut(update_modal);
            }
        }

        function fadeOut(el) {
            el.style.opacity = 0.6;
            (function fade() {
                if ((el.style.opacity -= 0.1) < 0) {
                    el.style.display = "none";
                    el.style.backgroundColor = ""; // Reset background color when fading out
                } else {
                    requestAnimationFrame(fade);
                }
            })();
        }

        function fadeIn(el, display) {
            el.style.opacity = 0;
            el.style.display = display || "flex";
            el.style.backgroundColor = "rgba(0, 0, 0, 0.5)"; // Partially transparent background
            (function fade() {
                let val = parseFloat(el.style.opacity);
                if (!((val += 0.2) > 1)) {
                    el.style.opacity = val;
                    requestAnimationFrame(fade);
                }
            })();
        }
    </script>
</x-app-layout>