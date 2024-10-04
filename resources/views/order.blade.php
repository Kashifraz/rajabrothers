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
                                <td class="px-6 py-4">
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
</x-app-layout>