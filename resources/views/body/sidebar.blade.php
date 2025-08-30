<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            {{-- @if (!empty($logo))
                <img src="{{ asset('/') . $logo }}" alt="" height="40">
            @else
                EIL<span>POS</span>
            @endif --}}
            <img src="{{ asset('/Logo-2.png') }}" alt="" height="40">
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>

    </div>
    <script></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            /* position: fixed;
                top: 0;
                left: 0;
                height: 100vh; */
            width: 250px;
            background: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            /* z-index: 1000; */
        }

        .sidebar-body {
            height: calc(100vh - 60px);
            /* Adjust for header */
            overflow-y: auto;
        }

        /* .main-content {
                margin-left: 250px;
                padding: 20px;
            } */

        .nav_active {
            background: #0d6efd !important;
            border-radius: 5px;
            color: #fff !important;
            border-left: 4px solid #fff;
        }

        .nav-link.nav_active .link-icon,
        .nav-link.nav_active .link-title {
            color: #ffffff !important;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
        }

        .nav-item.nav-category {
            font-weight: bold;
            padding: 10px 15px;
            color: #6c757d;
        }

        .sub-menu .nav-link {
            padding-left: 25px !important;
        }

        .nav-link:hover,
        .nav-link:focus {
            background-color: #408dff;
            color: #fff;
        }

        .sidebar-header {
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand img {
            height: 40px;
        }

        .link-icon {
            margin-right: 10px;
        }

        .link-title {
            flex: 1;
        }

        .link-arrow {
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                /* width: 100%; */
                height: auto;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    {{-- <style>
        .nav_active {
            background: #0d6efd;
            border-radius: 5px;
            color: #fff !important;
        }

        .nav-link.nav_active .link-icon,
        .nav-link.nav_active .link-title,
        .nav-link.nav_active {
            color: #ffffff !important;

        }

        .sub-menu .nav-item .nav-link {
            padding-left: 5px !important;
        }

        .nav-link:hover,
        .nav-link:focus {
            background-color: #408dff
        }
    </style> --}}
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'nav_active' : '' }}">
                    <i class="ms-2 link-icon" data-feather="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            {{-- ///////////////////////////////////// ---- POS Start ---- //////////////////////////////////// --}}
            @if (Auth::user()->can('pos.menu'))
                <li class="nav-item nav-category">POS (Point of Sale)</li>

                @if ($sale_page === 1)
                    @if (Auth::user()->can('sale'))
                        <li class="nav-item">
                            <a href="{{ route('sale') }}"
                                class="nav-link {{ request()->routeIs('sale') ? 'nav_active' : '' }}">
                                <i class="ms-2 link-icon" data-feather="shopping-cart"></i>
                                <span class="link-title">Sale</span>
                            </a>
                        </li>
                    @endif
                    @if (Auth::user()->can('sale.new'))
                        <li class="nav-item">
                            <a href="{{ route('sale.new') }}"
                                class="nav-link {{ request()->routeIs('sale.new') ? 'nav_active' : '' }}">
                                <i class="ms-2 link-icon" data-feather="shopping-cart"></i>
                                <span class="link-title">Sale </span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('sale.pharmacy') }}"
                            class="nav-link {{ request()->routeIs('sale.pharmacy') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="shopping-cart"></i>
                            <span class="link-title">Sale Pharmacy</span>
                        </a>
                    </li>
                @endif
            @endif
            <li class="nav-item">
                <a href="{{ route('sale.page') }}"
                    class="nav-link {{ request()->routeIs('sale.page') ? 'nav_active' : '' }}">
                    <i class="ms-2 link-icon" data-feather="shopping-cart"></i>
                    <span class="link-title">React Sale </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('sale.view.all') }}"
                    class="nav-link {{ request()->routeIs('sale.view.all') ? 'nav_active' : '' }}">
                    {{-- <i class="ms-2 link-icon" data-feather="settings"></i> --}}
                    <i class="ms-2 link-icon fa-solid fa-list-check"></i>
                    <span class="link-title">Sale Manage</span>
                </a>
            </li>

            {{-- @if (Auth::user()->can('pos-affliator-manage.menu')) --}}
            @php
                $affliator_setting = App\Models\PosSetting::where('affliate_program', 1)->first();
                $seller_setting = App\Models\PosSetting::where('sale_commission', 1)->first();
            @endphp
            @if ($affliator_setting)
                <li class="nav-item">
                    <a href="{{ route('affliator.index') }}"
                        class="nav-link {{ request()->routeIs('affliator.index') ? 'nav_active' : '' }}">
                        {{-- <i class="ms-2 link-icon" data-feather="settings"></i> --}}
                        <i class="ms-2 link-icon fa-solid fa-list-check"></i>
                        <span class="link-title">Affliator</span>
                    </a>
                </li>
            @endif
            {{-- @endif --}}
            {{-- @if (Auth::user()->can('pos-affliator-manage.menu')) --}}

            @if ($affliator_setting)
                <li class="nav-item">
                    <a href="{{ route('affliator.commission.manage') }}"
                        class="nav-link {{ request()->routeIs('affliator.commission.manage') ? 'nav_active' : '' }}">
                        {{-- <i class="ms-2 link-icon" data-feather="settings"></i> --}}
                        <i class="ms-2 link-icon fa-solid fa-list-check"></i>
                        <span class="link-title">Affliator Commission Manage</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->can('purchase.menu'))
                <li class="nav-item nav-category">Purchase Manage</li>
                @if ($purchase_page === 1)
                    @if (Auth::user()->can('purchase.add'))
                        <li class="nav-item">
                            <a href="{{ route('purchase') }}"
                                class="nav-link {{ request()->routeIs('purchase') ? 'nav_active' : '' }}">
                                <i class="ms-2 link-icon fa-solid fa-cart-plus"></i>
                                <span class="link-title">Purchase</span>
                            </a>
                        </li>
                    @endif
                @endif
                @if (Auth::user()->can('pre.order'))
                    <li class="nav-item">
                        <a href="{{ route('purchase2') }}"
                            class="nav-link {{ request()->routeIs('purchase2') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon fa-solid fa-cart-plus"></i>
                            <span class="link-title">Pre Order</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('purchase.list'))
                    <li class="nav-item">
                        <a href="{{ route('purchase.view') }}"
                            class="nav-link {{ request()->routeIs('purchase.view') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon fa-solid fa-bars-progress"></i>
                            <span class="link-title">Manage Purchase</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('purchase.view.all') }}"
                        class="nav-link {{ request()->routeIs('purchase.view.all') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon fa-solid fa-bars-progress"></i>
                        <span class="link-title">Manage Order </span>
                    </a>
                </li>
            @endif
            {{-- //Purchase End // --}}
            {{-- //Via Sale// --}}
            @if ($via_sale == 1)
                @if (Auth::user()->can('via.purchase'))
                    <li class="nav-item">
                        <a href="{{ route('via.sale') }}"
                            class="nav-link {{ request()->routeIs('via.sale') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="columns"></i>
                            <span class="link-title">Via Purchase</span>
                        </a>
                    </li>
                @endif
            @endif
            {{-- //Via Sale  End // --}}

            {{-- </ul>
            </div>
        </li> --}}
            {{-- ///////////////////////////////////////////---- POS End ----//////////////////////////////////////// --}}
            {{-- //////////////////////////////---- Store Management ----/////////////////////////////// --}}
            @if (Auth::user()->can('products.menu'))
                <li class="nav-item nav-category">Store Management</li>
                @if (Auth::user()->can('products.add'))
                    <li class="nav-item ">
                        <a href="{{ route('product') }}"
                            class="nav-link {{ request()->routeIs('product') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="plus"></i>
                            <span class="link-title">Add Product</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('products.list'))
                    <li class="nav-item">
                        <a href="{{ route('product.all.view') }}"
                            class="nav-link {{ request()->routeIs('product.all.view') ? 'nav_active' : '' }}">
                            {{-- <i class="ms-2 link-icon" data-feather="settings"></i> --}}
                            <i class="ms-2 link-icon fa-solid fa-store"></i>
                            <span class="link-title"> Manage Products</span>
                        </a>
                    </li>
                @endif

                @if ($bulk_update == 1)
                    <li class="nav-item">
                        <a href="{{ route('product.bulk_variation.view') }}"
                            class="nav-link {{ request()->routeIs('product.bulk_variation.view') ? 'nav_active' : '' }}">
                            {{-- <i class="ms-2 link-icon" data-feather="settings"></i> --}}
                            <i class="ms-2 link-icon fa-solid fa-store"></i>
                            <span class="link-title">Bulk Update</span>
                        </a>
                    </li>
                @endif
            @endif
            {{-- <li class="nav-item">
                <a href="{{ route('product.stock') }}"
                    class="nav-link {{ request()->routeIs('product.stock') ? 'nav_active' : '' }}">
                    <i class="ms-2 link-icon" data-feather="settings"></i>
                    <span class="link-title"> Stock Management</span>
                </a>
            </li> --}}
            @if (Auth::user()->can('category.menu'))
                <li class="nav-item">
                    <a href="{{ route('product.category') }}"
                        class="nav-link {{ request()->routeIs('product.category') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="grid"></i>
                        <span class="link-title">Category</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('subcategory.menu'))
                <li class="nav-item">
                    <a href="{{ route('product.subcategory') }}"
                        class="nav-link {{ request()->routeIs('product.subcategory') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="folder"></i>
                        <span class="link-title">Sub Category</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('brand.menu'))
                <li class="nav-item">
                    <a href="{{ route('product.brand') }}"
                        class="nav-link {{ request()->routeIs('product.brand') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="tag"></i>
                        <span class="link-title">Brand</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('unit.menu'))
                <li class="nav-item">
                    <a href="{{ route('product.unit') }}"
                        class="nav-link {{ request()->routeIs('product.unit') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="square"></i>
                        <span class="link-title">Unit</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('products-size.menu'))
                <li class="nav-item">
                    <a href="{{ route('product.size.view') }}"
                        class="nav-link {{ request()->routeIs('product.size.view') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="maximize"></i>
                        <span class="link-title">Product Size</span>
                    </a>
                </li>
            @endif
            @if ($tax == 1)
                @if (Auth::user()->can('tax.menu'))
                    <li class="nav-item">
                        <a href="{{ route('product.tax.add') }}"
                            class="nav-link {{ request()->routeIs('product.tax.add') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="dollar-sign"></i>

                            <span class="link-title">Tax</span></a>
                    </li>
                @endif
            @endif
            @if (Auth::user()->can('all.party'))
                <li class="nav-item nav-category">All Party</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('party.view') ? 'nav_active' : '' }}"
                        href="{{ route('party.view') }}" role="button" aria-controls="general-pages">
                        <i class="ms-2 fa-solid fa-handshake link-icon"></i>
                        <span class="link-title">All Party</span>
                    </a>
                </li>
            @endif
            <!----Supplier--->
            @if (Auth::user()->can('supplier.menu'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('supplier') ? 'nav_active' : '' }}"
                        href="{{ route('supplier') }}" role="button" aria-controls="general-pages">
                        <i class="ms-2 fa-solid fa-handshake link-icon"></i>
                        <span class="link-title">Supplier</span>
                    </a>
                </li>
            @endif
            <!----Supplier End --->
            <!----Customer Start --->
            @if (Auth::user()->can('crm.customize-customer'))
                <li class="nav-item">
                    <a href="{{ route('crm.customer.list.view') }}"
                        class="nav-link {{ request()->routeIs('crm.customer.list.view') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="users"></i>
                        <span class="link-title">Customer</span>
                    </a>
                </li>
            @endif
            <!----Customer End --->
            {{-- </ul>
                    </div>
                </li> --}}


            {{-- ////////////////////////////////////---- Store Management End----//////////////////////////////// --}}
            {{-- ////////////////////////////////////---- Accounting----//////////////////////////////// --}}
            @if (Auth::user()->can('bank.menu'))
                <li class="nav-item nav-category">Accounting</li>
                {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('Accounting*') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" href="#Accounting" role="button" aria-expanded="false"
                    aria-controls="emails">
                    <i class="ms-2 link-icon" data-feather="mail"></i>
                    <span class="link-title">Accounting</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('Accounting*') ? 'show' : '' }}" id="Accounting">
                    <ul class="nav sub-menu"> --}}
                <!---Bank--->
                <li class="nav-item">
                    <a href="{{ route('bank') }}"
                        class="nav-link {{ request()->routeIs('bank') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-building-columns link-icon"></i>
                        <span class="link-title">Bank</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bank.to.bank.transfer') }}"
                        class="nav-link {{ request()->routeIs('bank.to.bank.transfer') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-building-columns link-icon"></i>
                        <span class="link-title">Bank to Bank Transfer</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bank.adjustments') }}"
                        class="nav-link {{ request()->routeIs('bank.adjustments') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-building-columns link-icon"></i>
                        <span class="link-title">Bank Adjustments</span>
                    </a>
                </li>
                @if (Auth::user()->can('loan.management'))
                    <li class="nav-item">
                        <a href="{{ route('loan') }}"
                            class="nav-link {{ request()->routeIs('loan') ? 'nav_active' : '' }}">
                            <i class="ms-2 fa-solid fa-hand-holding-dollar link-icon"></i>
                            <span class="link-title">Loan Managment</span>
                        </a>
                    </li>
                @endif
            @endif
            <!---Bank End--->

            <!---Expense--->
            @if (Auth::user()->can('expense.menu'))
                <li class="nav-item">
                    <a href="{{ route('expense.view') }}"
                        class="nav-link {{ request()->routeIs('expense.view') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-money-bill-transfer link-icon"></i>
                        <span class="link-title">Expense</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('service.sale'))
                <li class="nav-item">
                    <a href="{{ route('service.sale') }}"
                        class="nav-link {{ request()->routeIs('service.sale') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-money-bill-transfer link-icon"></i>
                        <span class="link-title">Service Sale</span>

                    </a>
                </li>
            @endif
            <!---Expense End--->
            <!---Transaction--->
            @if (Auth::user()->can('transaction.menu'))
                <li class="nav-item">
                    <a href="{{ route('transaction.add') }}"
                        class="nav-link {{ request()->routeIs('transaction.add') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-receipt link-icon"></i>
                        <span class="link-title">Transaction</span>
                    </a>
                </li>
            @endif
            <!---Transaction End--->
            <!---Promotion--->
            @if (Auth::user()->can('promotion.menu'))
                <li class="nav-item">
                    <a href="{{ route('promotion.view') }}"
                        class="nav-link {{ request()->routeIs('promotion.view') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-tag link-icon"></i>
                        <span class="link-title">Promotion</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('promotion-details.menu'))
                <li class="nav-item">
                    <a href="{{ route('promotion.details.view') }}"
                        class="nav-link {{ request()->routeIs('promotion.details.view') ? 'nav_active' : '' }}">
                        <i class="ms-2 fa-solid fa-tags link-icon"></i>
                        <span class="link-title">Promotion Details</span>
                    </a>
                </li>
            @endif
            <!---Promotion End--->
            {{-- </ul>
                </div>
            </li> --}}
            {{-- ////////////////////////////////////---- Accounting End----//////////////////////////////// --}}

            {{-- ////////////////////////////////////---- Inventory----//////////////////////////////// --}}
            @if (Auth::user()->can('Inventory.menu'))
                <li class="nav-item nav-category">Inventory</li>
                {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('Inventory*') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" href="#Inventory" role="button" aria-expanded="false"
                    aria-controls="emails">
                    <i class="ms-2 link-icon" data-feather="mail"></i>
                    <span class="link-title">Inventory</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('Inventory*') ? 'show' : '' }}" id="Inventory">
                    <ul class="nav sub-menu"> --}}
                <!---Stock --->
                @if (Auth::user()->can('Inventory.stock.report'))
                    <li class="nav-item">
                        <a href="{{ route('report.stock') }}"
                            class="nav-link {{ request()->routeIs('report.stock') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="trending-up"></i>
                            <span class="link-title">Stock Report</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('Inventory.low.stock.report'))
                    <li class="nav-item">
                        <a href="{{ route('report.low.stock') }}"
                            class="nav-link {{ request()->routeIs('report.low.stock') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="trending-down"></i>
                            <span class="link-title"> Low Stock Report</span>
                        </a>
                    </li>
                @endif
                <!---Stock End--->
                {{-- //Damage // --}}
                @if (Auth::user()->can('damage.menu'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('damage') ? 'nav_active' : '' }}"
                            href="{{ route('damage') }}" role="button" aria-controls="general-pages">
                            <i class="ms-2 link-icon" data-feather="book"></i>
                            <span class="link-title">Damage</span>
                        </a>
                    </li>
                @endif
                {{-- //Damage End // --}}
                <!--- Damage Product --->
                @if (Auth::user()->can('Inventory.damage'))
                    <li class="nav-item">
                        <a href="{{ route('report.damage') }}"
                            class="nav-link {{ request()->routeIs('report.damage') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="alert-triangle"></i>
                            <span class="link-title"> Damage Report</span>
                        </a>
                    </li>
                @endif
            @endif
            <!---Damage End--->
            <!---Return --->
            @if (Auth::user()->can('return.menu'))
                <li class="nav-item">
                    <a href="{{ route('return.products.list') }}"
                        class="nav-link {{ request()->routeIs('return.products.list') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="corner-down-right"></i>
                        <span class="link-title">All Return</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->can('warehouse.list'))
                <li class="nav-item">
                    <a href="{{ route('wearhouse') }}"
                        class="nav-link {{ request()->routeIs('wearhouse') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="corner-down-right"></i>
                        <span class="link-title">Wearhouse/Go-Down</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('warehouse.racks'))
                <li class="nav-item">
                    <a href="{{ route('racks') }}"
                        class="nav-link {{ request()->routeIs('racks') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="corner-down-right"></i>
                        <span class="link-title">Wearhouse Racks</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('warehouse.assign.racks'))
                <li class="nav-item">
                    <a href="{{ route('racks.assign') }}"
                        class="nav-link {{ request()->routeIs('racks.assign') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="corner-down-right"></i>
                        <span class="link-title">Assign the Racks</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('stock.adjustment.add'))
                <li class="nav-item">
                    <a href="{{ route('stock.adjustment') }}"
                        class="nav-link {{ request()->routeIs('stock.adjustment') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="corner-down-right"></i>
                        <span class="link-title"> Stock Adjustment </span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('stock.transfer.menu'))
                @if (Auth::user()->can('stock.transfer.add'))
                    <li class="nav-item">
                        <a href="{{ route('stock.transfer') }}"
                            class="nav-link {{ request()->routeIs('stock.transfer') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="corner-down-right"></i>
                            <span class="link-title"> Stock Transfer</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('stock.transfer.view'))
                    <li class="nav-item">
                        <a href="{{ route('stock.transfer.view') }}"
                            class="nav-link {{ request()->routeIs('stock.transfer.view') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="corner-down-right"></i>
                            <span class="link-title"> Stock Transfer History</span>
                        </a>
                    </li>
                @endif
            @endif

            {{-- ////////////////////////Courier management//////////////// --}}

            @if ($courier_management === 1)
                <li class="nav-item nav-category">Courier Management</li>
                <li class="nav-item ">
                    <a href="{{ route('courier.add') }}"
                        class="nav-link {{ request()->routeIs('courier.add') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="plus"></i>
                        <span class="link-title"> Add Courier</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('courier.manage') }}"
                        class="nav-link {{ request()->routeIs('courier.manage') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="plus"></i>
                        <span class="link-title">Manage Courier</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link " data-bs-toggle="collapse" href="#CourierOrder" role="button"
                        aria-expanded="false" aria-controls="courier_order">
                        <i class="ms-2 fa-solid fa-users-gear link-icon"></i>
                        <span class="link-title">Courier Order</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse " id="CourierOrder">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('pending.courier.order') }}"
                                    class="nav-link {{ request()->routeIs('pending.courier.order') ? 'nav_active' : '' }}">
                                    Pending Order
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('processing.courier.order') }}"
                                    class="nav-link {{ request()->routeIs('processing.courier.order') ? 'nav_active' : '' }}">processing
                                    Order
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('complete.courier.order') }}"
                                    class="nav-link {{ request()->routeIs('complete.courier.order') ? 'nav_active' : '' }}">Complete
                                    Order
                                </a>
                            </li>



                            <li class="nav-item">
                                <a href="{{ route('cancel.courier.order') }}"
                                    class="nav-link {{ request()->routeIs('cancel.courier.order') ? 'nav_active' : '' }}">Cancel
                                    Order
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif






            <!---Damage End--->
            {{-- </ul>
                </div>
            </li> --}}
            {{-- ////////////////////////////////////---- Inventory End----//////////////////////////////// --}}
            {{-- ////////////////////////////////////---- Report Start----//////////////////////////////// --}}
            @if (Auth::user()->can('report.menu'))
                <li class="nav-item nav-category">All Reports</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#majid" role="button" aria-expanded="false"
                        aria-controls="forms">
                        <i class="ms-2 link-icon" data-feather="file-text"></i>
                        <span class="link-title">Reports</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('report*') ? 'show' : '' }}" id="majid">
                        <ul class="nav sub-menu">
                            @if (Auth::user()->can('toady.report'))
                                <li class="nav-item">
                                    <a id="report" href="{{ route('report.today') }}"
                                        class="nav-link {{ request()->routeIs('report.today') ? 'nav_active' : '' }}">Today
                                        Report</a>
                                </li>
                            @endif

                            <!------------New report ---------->
                            @if (Auth::user()->can('party.ways.discount.report'))
                                <li class="nav-item">
                                    <a id="report" href="{{ route('party.ways.discount.report') }}"
                                        class="nav-link {{ request()->routeIs('party.ways.discount.report') ? 'nav_active' : '' }}">
                                        Party Ways Discount Report</a>
                                </li>
                            @endif
                            {{-- @if (Auth::user()->can('seller.wise.report'))
                                <li class="nav-item">
                                    <a id="report" href="{{ route('saller.ways.report') }}"
                                        class="nav-link {{ request()->routeIs('saller.ways.report') ? 'nav_active' : '' }}">Saller
                                        Ways Report</a>
                                </li>
                            @endif --}}
                            @if (Auth::user()->can('product.info.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.product.info') }}"
                                        class="nav-link {{ request()->routeIs('report.product.info') ? 'nav_active' : '' }}">Product
                                        Info
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('summary.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.summary') }}"
                                        class="nav-link {{ request()->routeIs('report.summary') ? 'nav_active' : '' }}">Summary
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('customer.due.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.customer.due') }}"
                                        class="nav-link {{ request()->routeIs('report.customer.due') ? 'nav_active' : '' }}">Customer
                                        Due
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('supplier.due.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.supplier.due') }}"
                                        class="nav-link {{ request()->routeIs('report.supplier.due') ? 'nav_active' : '' }}">Supplier
                                        Due
                                        Report</a>
                                </li>
                            @endif

                            @if (Auth::user()->can('Inventory.stock.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.stock') }}"
                                        class="nav-link {{ request()->routeIs('report.stock') ? 'nav_active' : '' }}">

                                        <span class="link-link">Stock Report</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('Inventory.low.stock.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.low.stock') }}"
                                        class="nav-link {{ request()->routeIs('report.low.stock') ? 'nav_active' : '' }}">

                                        <span class="link-link"> Low Stock Report</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('stock.adjustment.report'))
                                <li class="nav-item">
                                    <a href="{{ route('stock.adjustment.report') }}"
                                        class="nav-link {{ request()->routeIs('stock.adjustment.report') ? 'nav_active' : '' }}">

                                        <span class="link-link"> Stock Adjustment Report</span>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::user()->can('customer.ledger.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.customer.ledger') }}"
                                        class="nav-link {{ request()->routeIs('report.customer.ledger') ? 'nav_active' : '' }}">Customer
                                        Ledger</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('supplier.ledger.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.suppliers.ledger') }}"
                                        class="nav-link {{ request()->routeIs('report.suppliers.ledger') ? 'nav_active' : '' }}">Supplier
                                        Ledger</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('account.transaction.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.account.transaction') }}"
                                        class="nav-link {{ request()->routeIs('report.account.transaction') ? 'nav_active' : '' }}">Account
                                        Transaction</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('expense.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.expense') }}"
                                        class="nav-link {{ request()->routeIs('report.expense') ? 'nav_active' : '' }}">Expense
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('employee.salary.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.employee.salary.view') }}"
                                        class="nav-link {{ request()->routeIs('report.employee.salary.view') ? 'nav_active' : '' }}">Employee
                                        Salary
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('sms.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.sms') }}"
                                        class="nav-link {{ request()->routeIs('report.sms') ? 'nav_active' : '' }}">Sms
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('monthly.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.monthly') }}"
                                        class="nav-link {{ request()->routeIs('report.monthly') ? 'nav_active' : '' }}">Monthly
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('yearly.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.yearly') }}"
                                        class="nav-link {{ request()->routeIs('report.yearly') ? 'nav_active' : '' }}">Yearly
                                        Report</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            {{-- ////////////////////////////////////----Purchase  Report End----//////////////////////////////// --}}
            @if (Auth::user()->can('purchase.report.menu'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('purchase*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#purchase" role="button" aria-expanded="false"
                        aria-controls="forms">
                        <i class="ms-2 link-icon" data-feather="file-text"></i>
                        <span class="link-title">Purchase Reports</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('purchase*') ? 'show' : '' }}" id="purchase">
                        <ul class="nav sub-menu">
                            @if (Auth::user()->can('purchase.report'))
                                <li class="nav-item">
                                    <a href="{{ route('report.purchase') }}"
                                        class="nav-link {{ request()->routeIs('report.purchase') ? 'nav_active' : '' }}">Purchase
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('date.wise.purchase.report'))
                                <li class="nav-item mt-1">
                                    <a id="report" href="{{ route('report.datewise') }}"
                                        class="nav-link {{ request()->routeIs('report.datewise') ? 'nav_active' : '' }}">DateWise
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('supplier.wise.purchase.report'))
                                <li class="nav-item mt-1">
                                    <a id="report" href="{{ route('supplier.report') }}"
                                        class="nav-link {{ request()->routeIs('supplier.report') ? 'nav_active' : '' }}">Supplier
                                        Report</a>
                                </li>
                            @endif
                            {{-- @if (Auth::user()->can('product.wise.purchase.report'))
                                <li class="nav-item mt-1">
                                    <a id="report" href="{{ route('product.purchase.report') }}"
                                        class="nav-link {{ request()->routeIs('product.purchase.report') ? 'nav_active' : '' }}">Product
                                        Report
                                    </a>
                                </li>
                            @endif --}}
                        </ul>
                    </div>
                </li>
            @endif

            {{-- commission report --}}




            @if (Auth::user()->can('sales.report.menu'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#sales" role="button" aria-expanded="false"
                        aria-controls="forms">
                        <i class="ms-2 link-icon" data-feather="file-text"></i>
                        <span class="link-title">Sales Reports</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('sales*') ? 'show' : '' }}" id="sales">
                        <ul class="nav sub-menu">
                            {{-- @if (Auth::user()->can('daily.sale.report')) --}}
                            <li class="nav-item">
                                <a id="report" href="{{ route('variation.top.sale') }}"
                                    class="nav-link {{ request()->routeIs('variation.top.sale') ? 'nav_active' : '' }}">Top
                                    Sale
                                    Report</a>
                            </li>
                            {{-- @endif --}}
                            @if (Auth::user()->can('daily.sale.report'))
                                <li class="nav-item">
                                    <a id="report" href="{{ route('daily.sale.report') }}"
                                        class="nav-link {{ request()->routeIs('daily.sale.report') ? 'nav_active' : '' }}">Daily
                                        Sale Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('salesman.wise.report'))
                                <li class="nav-item mt-1">
                                    <a id="report" href="{{ route('report.salesman.wise.report') }}"
                                        class="nav-link {{ request()->routeIs('report.salesman.wise.report') ? 'nav_active' : '' }}">Salesman
                                        Wise
                                        Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('sale.Inv.discount.report'))
                                <li class="nav-item">
                                    <a id="report" href="{{ route('sales.invoice.discount.report') }}"
                                        class="nav-link {{ request()->routeIs('sales.invoice.discount.report') ? 'nav_active' : '' }}">
                                        Sales Inv. Discount Report</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('sales.Items.discount.report'))
                                <li class="nav-item">
                                    <a id="report" href="{{ route('sales.items.discount.report') }}"
                                        class="nav-link {{ request()->routeIs('sales.items.discount.report') ? 'nav_active' : '' }}">
                                        Sales Items Discount Report</a>
                                </li>
                            @endif
                            {{-- @if (Auth::user()->can('sales.Items.discount.report')) --}}
                            <li class="nav-item">
                                <a id="report" href="{{ route('sale.invoice.filter') }}"
                                    class="nav-link {{ request()->routeIs('sale.invoice.filter') ? 'nav_active' : '' }}">
                                    Sales Item History</a>
                            </li>
                            <li class="nav-item">
                                <a id="report" href="{{ route('sale.main.invoice.filter') }}"
                                    class="nav-link {{ request()->routeIs('sale.main.invoice.filter') ? 'nav_active' : '' }}">
                                    Sales History</a>
                            </li>
                            {{-- @endif --}}

                        </ul>
                    </div>
                </li>

            @endif

            @if ($affliator_setting)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('AffliateCommission*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#affiliate" role="button" aria-expanded="false"
                        aria-controls="forms">
                        <i class="ms-2 link-icon" data-feather="file-text"></i>
                        <span class="link-title">Affliate Commission Report</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('AffliateCommission*') ? 'show' : '' }}"
                        id="affiliate">
                        <ul class="nav sub-menu">
                            {{-- @if (Auth::user()->can('daily.sale.report')) --}}
                            <li class="nav-item">
                                <a id="report" href="{{ route('affiliate.commission.report') }}"
                                    class="nav-link {{ request()->routeIs('daily.sale.report') ? 'nav_active' : '' }}">
                                    Affiliate Commission</a>
                            </li>
                            {{-- @endif --}}
                        </ul>
                    </div>
                </li>
            @endif



            <!--//end sale report--->
            {{-- //////////////////////--- Human Resource Management (HRM)----///////////////////// --}}
            @if (Auth::user()->can('employee.menu'))
                <li class="nav-item nav-category">HRM</li>
                {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('employee*') ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" href="#employee" role="button" aria-expanded="false"
                    aria-controls="emails">
                    <i class="ms-2 link-icon" data-feather="mail"></i>
                    <span class="link-title">HRM</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('employee*') ? 'show' : '' }}" id="employee">
                    <ul class="nav sub-menu"> --}}

                <li class="nav-item ">
                    <a href="{{ route('employee.add') }}"
                        class="nav-link {{ request()->routeIs('employee.add') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="plus"></i>
                        <span class="link-title"> Add Employee</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('employee-salary.menu'))
                <li class="nav-item">
                    <a href="{{ route('employee.salary.add') }}"
                        class="nav-link {{ request()->routeIs('employee.salary.add') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="dollar-sign"></i>
                        <span class="link-title">Add Employee Salary</span></a>
                </li>
            @endif
            @if (Auth::user()->can('advanced-employee-salary.menu'))
                <li class="nav-item">
                    <a href="{{ route('advanced.employee.salary.add') }}"
                        class="nav-link {{ request()->routeIs('advanced.employee.salary.add') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="plus"></i>
                        <span class="link-title">Advanced Employee Salary</span>
                    </a>
                </li>
            @endif

            {{-- @if (Auth::user()->can('sale.commission')) --}}
            @if ($seller_setting && $seller_setting->sale_commission == 1)
                <li class="nav-item">
                    <a href="{{ route('advanced.employee.sale.commission') }}"
                        class="nav-link {{ request()->routeIs('advanced.employee.sale.commission') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="plus"></i>
                        <span class="link-title">Seller Commission</span>
                    </a>
                </li>
            @endif
            {{-- @endif --}}


            {{-- </ul>
                </div>
            </li> --}}
            {{-- ///////////////////---- Human Resource Management (HRM) End----////////////////// --}}
            {{-- ///////////////////---- Customer Relationship Manager (CRM)----////////////////// --}}
            @if (Auth::user()->can('crm.menu'))
                <li class="nav-item nav-category">CRM</li>
                {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('crm*') ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                    href="#advancedUI" role="button" aria-expanded="false" aria-controls="advancedUI">
                    <i class="ms-2 link-icon" data-feather="anchor"></i>
                    <span class="link-title">CRM</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ request()->routeIs('crm*') ? 'show' : '' }}" id="advancedUI">
                    <ul class="nav sub-menu"> --}}

                {{-- @if (Auth::user()->can('customer.menu'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer.view') ? 'nav_active' : '' }}"
                            href="{{ route('customer.view') }}" role="button" aria-controls="general-pages">
                            <i class="ms-2 link-icon" data-feather="users"></i>
                            <span class="link-title">Customer</span>
                        </a>
                    </li>
                @endif --}}

                {{-- @if (Auth::user()->can('crm.customize-customer'))
                    <li class="nav-item">
                        <a href="{{ route('crm.customer.list.view') }}"
                            class="nav-link {{ request()->routeIs('crm.customer.list.view') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="users"></i>
                            <span class="link-title"> Customize Customer</span>
                        </a>
                    </li>
                @endif --}}
                @if (Auth::user()->can('crm.email-marketing'))
                    <li class="nav-item">
                        <a href="{{ route('crm.email.To.Customer.Page') }}"
                            class="nav-link {{ request()->routeIs('crm.email.To.Customer.Page') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="mail"></i>
                            <span class="link-title"> Email Marketing</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('crm.sms-marketing'))
                    <li class="nav-item">
                        <a href="{{ route('crm.sms.To.Customer.Page') }}"
                            class="nav-link {{ request()->routeIs('crm.sms.To.Customer.Page') ? 'nav_active' : '' }}">
                            <i class="ms-2 link-icon" data-feather="message-square"></i>
                            <span class="link-title"> SMS Marketing</span>
                        </a>
                    </li>
                @endif
                {{-- </ul>
                </div>
            </li> --}}
            @endif
            {{-- ///////////////////---- Customer Relationship Manager (CRM) End----////////////////// --}}

            @if (Auth::user()->can('role-and-permission.menu'))
                <li class="nav-item nav-category">Software Control</li>
                <!---Role & Permission--->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('role*') ? 'collapsed' : '' }}"
                        data-bs-toggle="collapse" href="#role_permission" role="button" aria-expanded="false"
                        aria-controls="role_permission">
                        <i class="ms-2 fa-solid fa-users-gear link-icon"></i>
                        <span class="link-title">Role & Permission</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('role*') ? 'show' : '' }}" id="role_permission">
                        <ul class="nav sub-menu">
                            @if (Auth::user()->can('role-and-permission.all-permission'))
                                <li class="nav-item">
                                    <a href="{{ route('all.permission') }}"
                                        class="nav-link {{ request()->routeIs('all.permission') ? 'nav_active' : '' }}">All
                                        Permisiion</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('role-and-permission.all-role'))
                                <li class="nav-item">
                                    <a href="{{ route('all.role') }}"
                                        class="nav-link {{ request()->routeIs('all.role') ? 'nav_active' : '' }}">All
                                        Role</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('role-and-permission.role-in-permission'))
                                <li class="nav-item">
                                    <a href="{{ route('add.role.permission') }}"
                                        class="nav-link {{ request()->routeIs('add.role.permission') ? 'nav_active' : '' }}">Role
                                        In
                                        Permission</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('role-and-permission-check-role-permission'))
                                <li class="nav-item">
                                    <a href="{{ route('all.role.permission') }}"
                                        class="nav-link {{ request()->routeIs('all.role.permission') ? 'nav_active' : '' }}">Check
                                        All Role
                                        Permission</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            <!---Admin Manage--->
            @if (Auth::user()->can('admin-manage.menu'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#admin-manage" role="button" aria-expanded="false"
                        aria-controls="emails">
                        <i class="ms-2 fa-solid fa-users-gear link-icon"></i>
                        <span class="link-title">Admin/User Manage</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin*') ? 'show' : '' }}" id="admin-manage">
                        <ul class="nav sub-menu">
                            @if (Auth::user()->can('admin-manage.list'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.all') }}"
                                        class="nav-link {{ request()->routeIs('admin.all') ? 'nav_active' : '' }}">All
                                        Admin/User</a>
                                </li>
                            @endif
                            @if (Auth::user()->can('admin-manage.add'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.add') }}"
                                        class="nav-link {{ request()->routeIs('admin.add') ? 'nav_active' : '' }}">Add
                                        Admin/User</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            <!---Admin Manage--->
            @if (Auth::user()->can('settings.menu'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('setting*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#setting" role="button" aria-expanded="false"
                        aria-controls="emails">
                        <i class="ms-2 fa-solid fa-users-gear link-icon"></i>
                        <span class="link-title">Settings Management</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('setting*') ? 'show' : '' }}" id="setting">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('pos.settings.add') }}"
                                    class="nav-link {{ request()->routeIs('pos.settings.add') ? 'nav_active' : '' }}">Generel
                                    Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pos.invoice.settings') }}"
                                    class="nav-link {{ request()->routeIs('pos.invoice.settings') ? 'nav_active' : '' }}">Invoice
                                    Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pos.sale.settings') }}"
                                    class="nav-link {{ request()->routeIs('pos.sale.settings') ? 'nav_active' : '' }}">Sale
                                    Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pos.purchase.settings') }}"
                                    class="nav-link {{ request()->routeIs('pos.purchase.settings') ? 'nav_active' : '' }}">Purchase
                                    Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pos.product.stock.settings') }}"
                                    class="nav-link {{ request()->routeIs('pos.product.stock.settings') ? 'nav_active' : '' }}">Product
                                    & Stock
                                    Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pos.system.settings') }}"
                                    class="nav-link {{ request()->routeIs('pos.system.settings') ? 'nav_active' : '' }}">System
                                    Settings</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pos.sms.settings') }}"
                                    class="nav-link {{ request()->routeIs('pos.system.settings') ? 'nav_active' : '' }}">SMS
                                    Settings</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            <!---Admin Manage--->
            {{-- @if (Auth::user()->can('setting.manage'))
                <li class="nav-item">
                    <a href="{{ route('pos.settings.add') }}"
                        class="nav-link {{ request()->routeIs('pos.settings.add') ? 'nav_active' : '' }}">
                        <i class="ms-2 link-icon" data-feather="settings"></i>
                        <span class="link-title">Setting Manage</span>
                    </a>
                </li>
            @endif --}}




            {{-- @if (Auth::user()->can('settings.menu'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('setting*') ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" href="#setting-manage" role="button" aria-expanded="false"
                        aria-controls="emails">
                        <i class="ms-2 link-icon" data-feather="settings"></i>
                        <span class="link-title">Setting Manage</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('setting*') ? 'show' : '' }}" id="setting-manage">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('pos.settings.add') }}"
                                    class="nav-link {{ request()->routeIs('pos.settings.add') ? 'nav_active' : '' }}">
                                    <span class="link-title">Settings</span>
                                </a>
                            </li> --}}
            {{-- <li class="nav-item">
                                <a href="{{ route('invoice.settings') }}"
                                    class="nav-link {{ request()->routeIs('invoice.settings') ? 'nav_active' : '' }}">
                                    <span class="link-title">Invoice-1</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('invoice2.settings') }}"
                                    class="nav-link {{ request()->routeIs('invoice2.settings') ? 'nav_active' : '' }}">
                                    <span class="link-title">Invoice-2</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('invoice3.settings') }}"
                                    class="nav-link {{ request()->routeIs('invoice3.settings') ? 'nav_active' : '' }}">
                                    <span class="link-title">Invoice-3</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('invoice4.settings') }}"
                                    class="nav-link {{ request()->routeIs('invoice4.settings') ? 'nav_active' : '' }}">
                                    <span class="link-title">Invoice-4</span>
                                </a>
                            </li> --}}
            {{--
                        </ul>
                    </div>
                </li>
            @endif --}}
            @if (Auth::user()->can('limit.user'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.limit') ? 'nav_active' : '' }}"
                        href="{{ route('user.limit') }}" role="button" aria-controls="general-pages">
                        <i class="ms-2 link-icon" data-feather="sliders"></i>
                        <span class="link-title">User Limit</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('branch.menu'))
                <li class="nav-item nav-category">Branch</li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('branch.view') ? 'nav_active' : '' }}"
                        href="{{ route('branch.view') }}" role="button" aria-controls="general-pages">
                        <i class="ms-2 link-icon" data-feather="sliders"></i>
                        <span class="link-title">Branches</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('excel.file.import'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.imports') ? 'nav_active' : '' }}"
                        href="{{ route('products.imports') }}" role="button" aria-controls="general-pages">
                        <i class="ms-2 link-icon" data-feather="file-plus"></i>
                        <span class="link-title">Excel File Import</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>
<script src="https://unpkg.com/feather-icons"></script>

<script>
    //For Fix Sidebar//
    // Cookie handling functions
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) {
                const value = c.substring(nameEQ.length, c.length);
                return value;
            }
        }

        return null;
    }

    // Initialize Feather Icons
    feather.replace();

    document.addEventListener('DOMContentLoaded', function() {
        const sidebarBody = document.querySelector('.sidebar-body');
        const activeLink = document.querySelector('.nav-link.nav_active');
        const collapseLinks = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');
        // Restore scroll position from cookie
        const savedScrollPosition = getCookie('sidebarScrollPosition');
        if (savedScrollPosition && sidebarBody) {
            sidebarBody.scrollTop = parseInt(savedScrollPosition, 10);
        }

        // Scroll to active link
        if (activeLink) {
            const parentCollapse = activeLink.closest('.collapse');
            if (parentCollapse) {
                parentCollapse.classList.add('show');
                const toggleLink = document.querySelector(`[data-bs-target="#${parentCollapse.id}"]`);
                if (toggleLink) {
                    toggleLink.setAttribute('aria-expanded', 'true');
                }

            }
            setTimeout(() => {
                activeLink.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 100); // Delay for collapse animation
        }

        // Prevent default for collapsible links
        collapseLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#' || this.getAttribute('href') === '') {
                    e.preventDefault();

                }
            });
        });

        // Save scroll position to cookie before unload
        window.addEventListener('beforeunload', function() {
            if (sidebarBody) {
                setCookie('sidebarScrollPosition', sidebarBody.scrollTop,
                    7); // Cookie expires in 7 days

            }
        });

    });
</script>
