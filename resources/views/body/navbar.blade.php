<style>
    .search_result {
        max-height: 400px;
        overflow-y: scroll;
        width: 83%;
        position: absolute;
        margin-top: 50px;
        display: none;
        /* border-width: 0px 1px 1px 1px; */
        /* border-style: solid; */
        /* border-color: #00a9f1; */
        box-shadow: 1px 1px 3px gray;
    }

    .search_result.dark {
        background-color: #c7f1ff;
    }

    .search_result.light {
        background-color: #00a9f1;
    }

    /* Scroll bar Style  */
    /* width */
    ::-webkit-scrollbar {
        width: 7px;
        height: 9px;

    }

    /* Track */
    ::-webkit-scrollbar-track {
        /* box-shadow: inset 0 0 5px grey;
   */

        box-shadow: inset 0 0 5px transparent;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #00a9f1;
        border-radius: 10px;
    }
</style>
<nav class="navbar {{ Route::currentRouteName() === 'sale.new' ? 'no-head' : '' }}">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        @if(Route::currentRouteName() == 'sale.new')
           <div style="align-content: center;margin-right:5px">
            <a class="btn btn-primary" href="{{ route('sale.view.all') }}">Back</a>
           </div>
           @endif
        <div class="search-form">
            <div class="input-group">
                {{-- <div class="input-group-text">
                    <i data-feather="search"></i>
                </div> --}}
                <input type="text" style="border: 1px solid #0d6efd" class="form-control py-2" id="global_search"
                    placeholder="Search here..." autocomplete="off">
            </div>
        </div>
        <div class="search_result">
            <table class="table">
                <thead class="table_header">
                    <tr>
                        <th>Product Name</th>
                        <th>Available Stock</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody class="findData">
                </tbody>
            </table>
        </div>
        <ul class="navbar-nav">
            @php
            if(Auth::user()->role === 'superadmin' || Auth::user()->role === 'admin') {
            $lowStockProducts = App\Models\Stock::where('stock_quantity', '<', 10)
                ->with('product') // Assuming 'stock' has a relationship with 'product'
                ->orderBy('stock_quantity', 'asc')
                ->get();
        } else {
            $lowStockProducts = App\Models\Stock::where('stock_quantity', '<', 10)
                ->where('branch_id', Auth::user()->branch_id)
                ->with('product') // Assuming 'stock' has a relationship with 'product'
                ->orderBy('stock_quantity', 'asc')
                ->get();
        }

        @endphp
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle my_nav" href="#" id="notificationDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell"></i>

                    <div class="indicator">

                        <div class="circle"></div>
                    </div>
                </a>
                <style>

                </style>
                {{-- <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p>6 New Notifications</p>
                        <a href="javascript:;" class="text-muted">Clear all</a>
                    </div>
                    <div class="p-1">
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="gift"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>New Order Recieved</p>
                                <p class="tx-12 text-muted">30 min ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="alert-circle"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>Server Limit Reached!</p>
                                <p class="tx-12 text-muted">1 hrs ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="user"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>New customer registered</p>
                                <p class="tx-12 text-muted">2 sec ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="layers"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>Apps are ready for update</p>
                                <p class="tx-12 text-muted">5 hrs ago</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="download"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>Download completed</p>
                                <p class="tx-12 text-muted">6 hrs ago</p>
                            </div>
                        </a>
                    </div>
                    <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                        <a href="javascript:;">View all</a>
                    </div>
                </div> --}}

               <!-- resources/views/notifications/index.blade.php -->
               <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                    <p>{{ $lowStockProducts->count() }} New Notifications</p>
                    {{-- <a href="javascript:;" class="text-muted">Clear all</a> --}}
                </div>
                <div class="p-1">
                    @foreach($lowStockProducts->take(5) as $stock) <!-- Limit to 5 items -->
                        <a href="{{ url('/product/ledger/' . $stock->product_id) }}" class="dropdown-item d-flex align-items-center py-2">
                            <div class="wd-30 ht-30 d-flex align-items-center justify-content-center bg-danger rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="alert-circle"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>Low stock: {{ $stock->product->name }}</p> <!-- Display product name -->
                                <p class="tx-12 text-muted">{{ $stock->stock_quantity }} units left</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                    <a href="{{ route('report.low.stock') }}">View all</a> <!-- Add route for 'View all' -->
                </div>
            </div>
            </li>
            @php
                $user = Illuminate\Support\Facades\Auth::user();
            @endphp
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle my_nav" href="#" id="profileDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="wd-30 ht-30 rounded-circle"
                        src="{{ $user->photo ? asset('uploads/profile/' . $user->photo) : asset('assets/images/default-user.svg') }}"
                        alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        <div class="mb-3">
                            <img class="wd-80 ht-80 rounded-circle"
                                src="{{ $user->photo ? asset('uploads/profile/' . $user->photo) : asset('assets/images/default-user.svg') }}"
                                alt="Profile">
                        </div>
                        <div class="text-center">
                             <p class="tx-16 fw-bolder">{{ Auth::user()->name }}</p>
                            <p class="tx-12 text-muted">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <ul class="list-unstyled p-1">
                        <a href="{{ route('user.profile') }}" class="text-body ms-0">
                        <li class="dropdown-item py-2">
                        <i class="me-2 icon-md" data-feather="user"></i>
                        <span>Profile</span>
                        </li>
                      </a>
                      <a href="{{ route('user.change.password') }}" class="text-body ms-0">
                        <li class="dropdown-item py-2">
                                <i class="me-2 icon-md" data-feather="repeat"></i>
                                <span>Change Password</span>
                        </li>
                      </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <li class="dropdown-item py-2">
                                <button class="text-body ms-0 btn">
                                    <i class="me-2 icon-md" data-feather="log-out"></i>
                                    <span class="">Log Out</span>
                                </button>
                            </li>
                        </form>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
@php
    $mode = App\models\PosSetting::all()->first();
@endphp
<script>
    const darkMode = "{{ $mode->dark_mode }}";
    const searchResult = document.querySelector('.search_result');

    if (darkMode == 1) {
        searchResult.classList.add('dark');
    } else {
        searchResult.classList.add('light');
    }
</script>
