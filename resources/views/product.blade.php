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
                    <div class="flex items-center justify-between flex-wrap md:flex-nowrap space-y-4 md:space-y-0 p-4">

                        <form action="{{route('products.index')}}" method="GET" class="flex items-center space-x-4 w-full md:w-auto">
                            <!-- Increased width of the dropdown -->
                            <select id="stock" name="stock" class="w-48 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                                <option value="1" {{$stock != null && $stock==1 ? "selected":""}}>In Stock</option>
                                <option value="2" {{$stock != null && $stock==2 ? "selected":""}}>Out of Stock</option>
                            </select>

                            <label for="table-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{$search}}" id="table-search-users" class="block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for products">
                            </div>

                            <x-primary-button class="py-2.5">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </x-primary-button>
                        </form>

                        <div>
                            <x-primary-button class="py-2.5" onclick="categoryModal(true)">
                                {{ __('Add Product') }}
                            </x-primary-button>
                        </div>

                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>

                                <th scope="col" class="px-6 py-3">
                                    Product Image
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product Quantity
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product Unit
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Product Price
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr class="bg-white border-b hover:bg-gray-50 ">
                                <td class="">
                                    <div class="flex items-center my-2">
                                        @if ($product->image != "NA")
                                        <img class="w-24 h-16 mb-2  ml-5 " src="{{ asset('images/'.$product->image)}}" />
                                        @endif
                                    </div>
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 ">
                                    {{$product->name}}
                                </th>
                                <td class="px-6 py-4">
                                    {{$product->quantity}}
                                </td>

                                <td class="px-6 py-4">
                                    {{$product->unit}}
                                </td>

                                <td class="px-6 py-4">
                                    £{{$product->price}}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <!-- Edit Button -->
                                        <a onclick="updateModal(true, '{{ $product->id }}', '{{ $product->name }}', '{{ $product->category_id }}','{{ $product->unit }}','{{ $product->quantity }}','{{ $product->price }}','{{ asset('images/'.$product->image) }}','{{ $product->description }}')" class="font-medium text-blue-600 text-lg hover:text-blue-800">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
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
                {{ $products->links() }}
            </nav>
        </div>
    </div>

    <!-- Add Product model  -->
    <div class="py-12 bg-gray-700 hidden transition duration-150 ease-in-out  z-10 fixed top-0 right-0 bottom-0 left-0" id="category_modal">
        <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-4xl max-h-2xl overflow-scroll scroll-smooth">
            <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                <h1 class="text-gray-800 font-lg text-2xl font-bold tracking-normal leading-tight mb-4">Add Product</h1>
                <form method="post" action="{{route('products.store')}}" class="mt-6 space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="id" value="Product ID" />
                        <x-text-input id="id" name="id" type="text" class="mt-1 block w-full" placeholder="Product ID" />
                    </div>
                    <div>
                        <x-input-label for="name" value="Product Name" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Product name" />
                    </div>

                    <!-- Row for Product Category and Product Unit -->
                    <div class="flex space-x-4">
                        <div class="relative w-1/2 ">
                            <label for="category" class="block text-sm font-medium text-gray-700">Product Category</label>

                            <!-- Searchable Input Field -->
                            <input type="text" id="categorySearch" autocomplete="off" onclick="toggleDropdown()" onkeyup="filterCategories()" placeholder="Search category"
                                class="w-full mt-1 border-gray-300 bg-transparent  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">

                            <!-- Hidden Input to Store the Selected Category ID -->
                            <input type="hidden" id="selectedCategoryId" name="category_id">

                            <!-- Dropdown List (Initially Hidden) -->
                            <div id="dropdownContainer" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow-lg hidden">
                                <ul id="categoryDropdown" class="max-h-60 overflow-auto">
                                    <!-- Example Categories with IDs -->
                                    @foreach ($categories as $category )
                                    <li onclick="selectCategory(this)" data-id="{{$category->id}}" class="cursor-pointer px-4 py-2 hover:bg-gray-200">{{$category->name}}</li>
                                    @endforeach
                                    <!-- Add more categories with corresponding IDs -->
                                </ul>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <x-input-label for="unit" value="Product Unit" />
                            <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full" placeholder="Product unit" />
                        </div>
                    </div>

                    <!-- Row for Product Quantity and Product Price -->
                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <x-input-label for="quantity" value="Product Quantity" />
                            <x-text-input id="quantity" name="quantity" type="number" class="mt-1 block w-full" placeholder="Product quantity" />
                        </div>
                        <div class="w-1/2">
                            <x-input-label for="price" value="Product Price" />
                            <x-text-input id="price" name="price" step="0.01" type="number" class="mt-1 block w-full" placeholder="Product price" />
                        </div>
                    </div>

                    <div>
                        <label for="product_image" class="mb-2 inline-block text-neutral-700">Product Image</label>
                        <input name="image" id="product_image" class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none" type="file" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Product Description" />
                        <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Product description"></textarea>
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

    <!-- Update Product model  -->
    <div class="py-12 bg-gray-700 hidden transition duration-150 ease-in-out z-10 fixed top-0 right-0 bottom-0 left-0" id="update_modal">
        <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-4xl max-h-2xl scrol">
            <div class="relative py-8 px-5 md:px-10 bg-white shadow-md rounded border border-gray-400">
                <h1 class="text-gray-800 font-lg text-2xl font-bold tracking-normal leading-tight mb-4">Add Product</h1>
                <form method="post" class="mt-6 space-y-6" id="updateProductsForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Hidden input for Category ID -->
                    <input type="hidden" id="update_product_id" name="product_id">

                    <div>
                        <x-input-label for="name" value="Product Name" />
                        <x-text-input id="update_name" name="name" type="text" class="mt-1 block w-full" />
                    </div>

                    <!-- Row for Product Category and Product Unit -->
                    <div class="flex space-x-4">
                        <div class="relative w-1/2 ">
                            <label for="category" class="block text-sm font-medium text-gray-700">Product Category</label>

                            <!-- Searchable Input Field -->
                            <input type="text" id="updateCategorySearch" autocomplete="off" onclick="toggleUpdateDropdown()" onkeyup="filterUpdateCategories()" placeholder="Search category"
                                class="w-full mt-1 border-gray-300 bg-transparent  focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">

                            <!-- Hidden Input to Store the Selected Category ID -->
                            <input type="hidden" id="updateSelectedCategoryId" name="category_id">

                            <!-- Dropdown List (Initially Hidden) -->
                            <div id="updateDropdownContainer" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow-lg hidden">
                                <ul id="updateCategoryDropdown" class="max-h-60 overflow-auto">
                                    <!-- Example Categories with IDs -->
                                    @foreach ($categories as $category )
                                    <li onclick="selectUpdateCategory(this)" data-id="{{$category->id}}" class="cursor-pointer px-4 py-2 hover:bg-gray-200">{{$category->name}}</li>
                                    @endforeach
                                    <!-- Add more categories with corresponding IDs -->
                                </ul>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <x-input-label for="unit" value="Product Unit" />
                            <x-text-input id="update_unit" name="unit" type="text" class="mt-1 block w-full" placeholder="Product unit" />
                        </div>
                    </div>

                    <!-- Row for Product Quantity and Product Price -->
                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <x-input-label for="quantity" value="Product Quantity" />
                            <x-text-input id="update_quantity" name="quantity" type="number" class="mt-1 block w-full" placeholder="Product quantity" />
                        </div>
                        <div class="w-1/2">
                            <x-input-label for="price" value="Product Price" />
                            <x-text-input id="update_price" name="price" step="0.01" type="number" class="mt-1 block w-full" placeholder="Product price" />
                        </div>
                    </div>

                    <div>
                        <label for="product_image" class="mb-2 inline-block text-neutral-700">Product Image</label>
                        <input name="image" id="product_image" class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none" type="file" />
                    </div>

                    <div>
                        <label for="current_image" class="mb-2 inline-block text-neutral-700">Current Category Image</label>
                        <img id="current_image" class="w-32 h-24 mb-2" src="" style="display: none;" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Product Description" />
                        <textarea id="update_description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
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

    <!-- Jquery code for Add product modal -->
    <script>
        let category_modal = document.getElementById("category_modal");

        function categoryModal(val) {
            $('#category_modal').removeClass('hidden');
            if (val) {
                fadeIn(category_modal);
            } else {
                fadeOut(category_modal);
            }
        }

        function updateModal(val, id, name, category, unit, quantity, price, imageUrl, description) {
            $('#update_modal').removeClass('hidden');

            if (val) {
                fadeIn(update_modal);
                // Pre-fill the form fields with products data
                document.getElementById('update_product_id').value = id;
                document.getElementById('update_name').value = name;
                document.getElementById('updateSelectedCategoryId').value = category;
                document.getElementById('update_unit').value = unit;
                document.getElementById('update_quantity').value = quantity;
                document.getElementById('update_price').value = price;
                document.getElementById('update_description').value = description;

                // Display the current category image
                if (imageUrl && imageUrl !== "NA") {
                    document.getElementById('current_image').src = imageUrl;
                    document.getElementById('current_image').style.display = 'block';
                } else {
                    document.getElementById('current_image').style.display = 'none';
                }

                let updateForm = document.getElementById('updateProductsForm');
                updateForm.action = `/products/${id}`; // Resource route

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

    <!-- Jquery code for searchable dropdown for categories -->
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownContainer');
            dropdown.classList.toggle('hidden'); // Toggle visibility
        }

        function filterCategories() {
            const searchInput = document.getElementById('categorySearch').value.toLowerCase();
            const dropdown = document.getElementById('categoryDropdown');
            const items = dropdown.getElementsByTagName('li');

            for (let i = 0; i < items.length; i++) {
                const itemText = items[i].textContent || items[i].innerText;
                if (itemText.toLowerCase().indexOf(searchInput) > -1) {
                    items[i].style.display = "";
                } else {
                    items[i].style.display = "none";
                }
            }
        }

        function selectCategory(element) {
            const categoryId = element.getAttribute('data-id'); // Get the category ID
            const categoryName = element.textContent; // Get the category name

            document.getElementById('categorySearch').value = categoryName; // Set input value to the selected category name
            document.getElementById('selectedCategoryId').value = categoryId; // Set hidden input to the selected category ID
            toggleDropdown(); // Close the dropdown
        }

        // Close dropdown if clicked outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownContainer');
            const searchInput = document.getElementById('categorySearch');

            if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    <script>
        function toggleUpdateDropdown() {
            const dropdown = document.getElementById('updateDropdownContainer');
            dropdown.classList.toggle('hidden'); // Toggle visibility
        }

        function filterUpdateCategories() {
            const searchInput = document.getElementById('updateCategorySearch').value.toLowerCase();
            const dropdown = document.getElementById('updateCategoryDropdown');
            const items = dropdown.getElementsByTagName('li');

            for (let i = 0; i < items.length; i++) {
                const itemText = items[i].textContent || items[i].innerText;
                if (itemText.toLowerCase().indexOf(searchInput) > -1) {
                    items[i].style.display = "";
                } else {
                    items[i].style.display = "none";
                }
            }
        }

        function selectUpdateCategory(element) {
            const categoryId = element.getAttribute('data-id'); // Get the category ID
            const categoryName = element.textContent; // Get the category name

            document.getElementById('updateCategorySearch').value = categoryName; // Set input value to the selected category name
            document.getElementById('updateSelectedCategoryId').value = categoryId; // Set hidden input to the selected category ID
            toggleUpdateDropdown(); // Close the dropdown
        }

        // Close dropdown if clicked outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('updateDropdownContainer');
            const searchInput = document.getElementById('updateCategorySearch');

            if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>