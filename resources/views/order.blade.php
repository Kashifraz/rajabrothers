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
                            <select id="status" name="status" class="w-48 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                                <option value="1" {{$stock != null && $stock==1 ? "selected":""}}>Pending</option>
                                <option value="2" {{$stock != null && $stock==2 ? "selected":""}}>In Progress</option>
                                <option value="2" {{$stock != null && $stock==2 ? "selected":""}}>Completed</option>
                            </select>

                            <label for="table-search" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{$search}}" id="table-search-users" class="block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for orders">
                            </div>

                            <x-primary-button class="py-2.5">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </x-primary-button>
                        </form>

                    </div>

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Order Id
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Customer Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Location
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Total Amount
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr class="bg-white border-b hover:bg-gray-50 ">
                               
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 ">
                                    {{$order->id}}
                                </th>
                                <td class="px-6 py-4">
                                    {{$order->name}}
                                </td>

                                <td class="px-6 py-4">
                                    {{$order->email}}
                                </td>

                                <td class="px-6 py-4">
                                    {{$order->location}}
                                </td>
                                <td class="px-6 py-4">
                                    £{{$order->total_amount}}
                                </td>
                                <td class="px-6 py-4">
                                    {{$order->order_status}}
                                </td>
                                <td class="px-6 py-4 inline-flex">
                                <!-- Status Dropdown -->
                                <form action="{{ route('updateOrderStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="order_status" class=" border rounded px-3 py-1 w-32" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in progress" {{ $order->order_status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                                <a class="ml-5 mt-2 underline" href="#" onclick="detailsModal(true,  '{{ $order->id }}', '{{ $order->name }}', '{{ $order->email }}','{{ $order->location }}','{{ $order->total_amount }}','{{ $order->products }}','{{ $order->order_status }}')">Details</i></a> 
                                </td>

                               
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>

            </div>
            <nav class="my-4" aria-label="Table navigation">
                {{ $orders->links() }}
            </nav>
        </div>
    </div>

     <!-- Show Order Details Modal  -->
     <div class="py-12 bg-gray-700 hidden transition duration-150 ease-in-out  z-10 fixed top-0 right-0 bottom-0 left-0" id="details_modal">
        <div role="alert" class="container mx-auto w-11/12 md:w-2/3 max-w-4xl max-h-2xl overflow-scroll scroll-smooth bg-white shadow-md rounded border border-gray-400">
            <div class="relative py-8 px-5 md:px-10 ">
                <h1 class="text-gray-800 font-lg text-2xl font-bold tracking-normal leading-tight mb-4">Order Details</h1>
                
                <!-- order-details.blade.php -->
                <div class="">
                    <p><strong>Order ID:</strong> <span class="ml-3 mb-2" id="order_id"></span> </p>
                    <p><strong>Customer Name:</strong> <span class="ml-3 mb-2" id="name"></span></p>
                    <p><strong>Email:</strong> <span class="ml-3 mb-2" id="email"></span></p>
                    <p><strong>Location:</strong> <span class="ml-3 mb-2" id="location"></span></p>
                    <p><strong>Total Amount:</strong><span class="ml-3 mb-2" id="amount"></span></p>

                    <h3 class="text-md font-bold mt-4">Products Ordered</h3>
                    
                  
                </div>

                <button class="cursor-pointer absolute top-0 right-0 mt-4 mr-5 text-gray-400 hover:text-gray-600 transition duration-150 ease-in-out rounded focus:ring-2 focus:outline-none focus:ring-gray-600" onclick="detailsModal()" aria-label="close modal" role="button">
                    <i class="fa-solid fa-circle-xmark text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        let details_modal = document.getElementById("details_modal");

        function detailsModal(val, id, name, email, location, amount, products, status) {
            $('#details_modal').removeClass('hidden');
            if (val) {
                fadeIn(details_modal);
                console.log(id);
                console.log(name);
                console.log(email);
                console.log(location);
                document.getElementById('order_id').innerText = id;
                document.getElementById('name').innerText = name;
                document.getElementById('email').innerText = email;
                document.getElementById('location').innerText = location;
                document.getElementById('amount').innerText = amount;
                document.getElementById('status').innerText = status;
            } else {
                fadeOut(details_modal);
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